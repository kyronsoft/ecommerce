<?php

namespace App\Http\Controllers;

use App\Mail\EntrepreneurWelcomeMail;
use App\Mail\PaymentTransactionStatusMail;
use App\Models\Store;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Services\CartService;
use App\Services\EpaycoService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EpaycoController extends Controller
{
    public function __construct(
        private readonly EpaycoService $epayco,
        private readonly CartService $cart,
    ) {
    }

    public function checkout(Request $request): View
    {
        $transaction = PaymentTransaction::query()
            ->with('order.customer')
            ->where('order_ref', $request->string('order_ref'))
            ->firstOrFail();

        $order = $transaction->order;
        abort_unless($order, 404);
        $paymentContext = $this->resolvePaymentContext($transaction);

        $currency = strtoupper((string) $transaction->currency);
        $usesZeroDecimals = in_array($currency, ['COP'], true);
        $amountValue = $usesZeroDecimals ? round((float) $transaction->amount) : round((float) $transaction->amount, 2);
        $amount = $usesZeroDecimals
            ? number_format($amountValue, 0, '.', '')
            : number_format($amountValue, 2, '.', '');
        $tax = $usesZeroDecimals ? '0' : '0.00';
        $taxBase = $amount;

        $minAmount = config('epayco.min_amount');
        $maxAmount = config('epayco.max_amount');
        $rangeError = null;

        if ($minAmount !== null && $minAmount !== '' && $amountValue < (float) $minAmount) {
            $rangeError = 'El total del pedido está por debajo del monto mínimo permitido para este comercio en ePayco.';
        }

        if ($maxAmount !== null && $maxAmount !== '' && $amountValue > (float) $maxAmount) {
            $rangeError = 'El total del pedido supera el monto máximo permitido para este comercio en ePayco.';
        }

        if ($rangeError !== null) {
            $limits = [];

            if ($minAmount !== null && $minAmount !== '') {
                $limits[] = 'mínimo $'.number_format((float) $minAmount, 0, ',', '.');
            }

            if ($maxAmount !== null && $maxAmount !== '') {
                $limits[] = 'máximo $'.number_format((float) $maxAmount, 0, ',', '.');
            }

            if ($limits !== []) {
                $rangeError .= ' Rango configurado: '.implode(' y ', $limits).'.';
            }
        }

        $orderData = [
            'id' => $transaction->order_ref,
            'description' => 'Pago del pedido '.$order->number.' - La Tienda de Mi Abue',
            'amount' => $amount,
            'tax' => $tax,
            'tax_base' => $taxBase,
            'currency' => $currency,
            'name' => 'Pedido '.$order->number,
            'email' => $order->customer?->email ?? '',
        ];

        return view('epayco.checkout', [
            'order' => $orderData,
            'localOrder' => $order,
            'transaction' => $transaction,
            'paymentContext' => $paymentContext,
            'responseUrl' => config('epayco.response_url') ?: route('epayco.response'),
            'confirmationUrl' => config('epayco.confirmation_url') ?: route('epayco.confirmation'),
            'rangeError' => $rangeError,
        ]);
    }

    public function response(Request $request): View
    {
        $gatewayData = $this->resolveResponsePayload($request);
        $transaction = $this->syncTransactionFromPayload(
            $gatewayData !== [] ? $gatewayData : $request->all(),
            'response_payload'
        );

        $statusView = $this->resolveResponseViewData($gatewayData, $transaction);

        if ($statusView['key'] === 'approved') {
            $this->removeApprovedOrderProductsFromCart($transaction);
        }

        return view('epayco.response', [
            'data' => $gatewayData !== [] ? $gatewayData : $request->all(),
            'requestData' => $request->all(),
            'responseReference' => (string) ($request->input('ref_payco')
                ?: ($gatewayData['ref_payco'] ?? $gatewayData['x_ref_payco'] ?? '')),
            'lookupFailed' => $request->filled('ref_payco') && $gatewayData === [],
            'statusView' => $statusView,
            'transaction' => $transaction,
            'order' => $transaction?->order,
            'paymentContext' => $transaction ? $this->resolvePaymentContext($transaction) : null,
        ]);
    }

    protected function resolvePaymentContext(PaymentTransaction $transaction): array
    {
        $payload = $transaction->request_payload ?? [];
        $flow = $payload['flow'] ?? 'store_order';

        if ($flow === 'entrepreneur_plan') {
            $plan = $payload['plan'] ?? [];
            $entrepreneur = $payload['entrepreneur'] ?? [];

            return [
                'flow' => 'entrepreneur_plan',
                'title' => 'Estamos preparando tu activacion emprendedora',
                'intro' => 'Tu solicitud para el '.$plan['name'].' ya fue registrada como pendiente. Vamos a abrir el checkout de ePayco para completar el pago y activar el proceso comercial.',
                'reference_label' => 'Solicitud',
                'customer_label' => 'Emprendedor',
                'customer_value' => trim((string) (($entrepreneur['first_name'] ?? '').' '.($entrepreneur['last_name'] ?? ''))) ?: ($transaction->order?->customer?->full_name ?? $transaction->order?->customer?->email),
                'total_label' => 'Valor del plan',
                'name' => $plan['name'] ?? 'Plan emprendedor',
                'retry_url' => isset($plan['slug']) ? route('store.entrepreneur.apply', $plan['slug']) : route('store.entrepreneur'),
                'primary_approved_url' => route('store.entrepreneur'),
                'primary_approved_label' => 'Volver a planes',
                'secondary_url' => route('store.home'),
                'secondary_label' => 'Volver al inicio',
            ];
        }

        return [
            'flow' => 'store_order',
            'title' => 'Estamos preparando tu pago',
            'intro' => 'Tu pedido '.$transaction->order?->number.' ya fue registrado como pendiente. Vamos a abrir el checkout de ePayco para que completes el pago.',
            'reference_label' => 'Pedido',
            'customer_label' => 'Cliente',
            'customer_value' => $transaction->order?->customer?->full_name ?? $transaction->order?->customer?->email,
            'total_label' => 'Total',
            'name' => $transaction->order?->number ?? $transaction->order_ref,
            'retry_url' => route('store.checkout.index'),
            'primary_approved_url' => route('store.shop'),
            'primary_approved_label' => 'Seguir comprando',
            'secondary_url' => route('store.home'),
            'secondary_label' => 'Volver al inicio',
        ];
    }

    public function confirmation(Request $request)
    {
        Log::info('ePayco confirmation', $request->all());

        $transaction = $this->syncTransactionFromPayload($request->all(), 'confirmation_payload');

        if (! $transaction) {
            Log::warning('ePayco confirmation could not be linked to a local transaction', $request->all());
        }

        return response('OK', 200);
    }

    protected function resolveResponsePayload(Request $request): array
    {
        $requestPayload = $this->normalizeGatewayPayload($request->all());
        $refPayco = (string) $request->input('ref_payco');

        if ($refPayco === '') {
            return $requestPayload;
        }

        if ($this->payloadHasEnoughTransactionData($requestPayload)) {
            return $requestPayload;
        }

        try {
            $payload = $this->epayco->getTransactionByReference($refPayco);
        } catch (\Throwable $exception) {
            Log::warning('ePayco response lookup failed', [
                'ref_payco' => $refPayco,
                'message' => $exception->getMessage(),
            ]);

            return [];
        }

        if ($payload === []) {
            return [];
        }

        return $this->normalizeGatewayPayload(array_merge($request->all(), $payload, [
            'ref_payco' => $refPayco,
        ]));
    }

    protected function payloadHasEnoughTransactionData(array $payload): bool
    {
        return $this->extractOrderReference($payload) !== ''
            && (
                isset($payload['x_cod_transaction_state'])
                || isset($payload['x_cod_response'])
                || isset($payload['x_response'])
            );
    }

    protected function syncTransactionFromPayload(array $payload, string $payloadField): ?PaymentTransaction
    {
        $normalized = $this->normalizeGatewayPayload($payload);
        $orderRef = $this->extractOrderReference($normalized);

        if ($orderRef === '') {
            return null;
        }

        $transaction = PaymentTransaction::query()
            ->with('order')
            ->where('order_ref', $orderRef)
            ->first();

        if (! $transaction) {
            return null;
        }

        $previousStatus = (string) $transaction->status;

        $storedPayload = array_merge($payload, $normalized);
        $incomingAmount = isset($normalized['x_amount']) ? round((float) $normalized['x_amount'], 2) : null;
        $expectedAmount = round((float) $transaction->amount, 2);
        $incomingCurrency = strtoupper((string) ($normalized['x_currency_code'] ?? $transaction->currency));
        $expectedCurrency = strtoupper((string) $transaction->currency);
        $stateCode = (int) ($normalized['x_cod_transaction_state'] ?? $normalized['x_cod_response'] ?? 0);

        $validAmount = $incomingAmount === null || ($incomingAmount > 0 && abs($incomingAmount - $expectedAmount) < 0.01);
        $validCurrency = $incomingCurrency === $expectedCurrency;
        $validSignature = $this->hasValidSignature($normalized);
        $signatureBypassedForTestMode = ! $validSignature && $this->shouldBypassSignatureValidation($normalized);

        if (! $validAmount || ! $validCurrency || (! $validSignature && ! $signatureBypassedForTestMode)) {
            Log::warning('ePayco payload validation failed', [
                'order_ref' => $orderRef,
                'valid_amount' => $validAmount,
                'valid_currency' => $validCurrency,
                'valid_signature' => $validSignature,
                'configured_test_mode' => config('epayco.test'),
                'gateway_test_request' => $normalized['x_test_request'] ?? null,
                'merchant_id' => $normalized['x_cust_id_cliente'] ?? null,
                'ref_payco' => $normalized['x_ref_payco'] ?? $normalized['ref_payco'] ?? null,
                'transaction_id' => $normalized['x_transaction_id'] ?? null,
            ]);

            $transaction->update([
                $payloadField => $storedPayload,
                'status' => 'failed_validation',
            ]);

            if ($transaction->order) {
                $transaction->order->update(['status' => 'pending']);
            }

            return $transaction->fresh('order');
        }

        if ($signatureBypassedForTestMode) {
            Log::notice('ePayco signature validation bypassed for a test transaction.', [
                'order_ref' => $orderRef,
                'merchant_id' => $normalized['x_cust_id_cliente'] ?? null,
                'ref_payco' => $normalized['x_ref_payco'] ?? $normalized['ref_payco'] ?? null,
                'transaction_id' => $normalized['x_transaction_id'] ?? null,
            ]);
        }

        $status = $stateCode !== 0
            ? $this->mapGatewayStatus($stateCode)
            : $this->mapGatewayStatusFromText((string) ($normalized['x_response'] ?? $normalized['x_transaction_state'] ?? ''));

        $transaction->update([
            $payloadField => $storedPayload,
            'status' => $status,
        ]);

        if ($transaction->order) {
            $transaction->order->update([
                'status' => $status === 'approved'
                    ? 'paid'
                    : ($status === 'pending' ? 'pending' : 'cancelled'),
            ]);
        }

        $transaction = $transaction->fresh(['order.customer', 'order.items']);

        if ($transaction && in_array($status, ['approved', 'rejected'], true)) {
            $this->sendTransactionStatusEmailIfNeeded($transaction, $previousStatus);
        }

        return $transaction?->fresh('order');
    }

    protected function normalizeGatewayPayload(array $payload): array
    {
        $refPayco = $payload['ref_payco'] ?? $payload['x_ref_payco'] ?? null;
        $responseText = $payload['x_response'] ?? $payload['x_respuesta'] ?? $payload['response'] ?? $payload['x_transaction_state'] ?? null;

        return array_filter([
            'ref_payco' => $refPayco,
            'x_ref_payco' => $payload['x_ref_payco'] ?? $refPayco,
            'x_cust_id_cliente' => $payload['x_cust_id_cliente'] ?? $payload['x_cliente_id_cliente'] ?? $payload['p_cust_id_cliente'] ?? null,
            'x_id_factura' => $payload['x_id_factura'] ?? $payload['x_id_invoice'] ?? $payload['invoice'] ?? null,
            'x_id_invoice' => $payload['x_id_invoice'] ?? $payload['x_id_factura'] ?? null,
            'x_extra1' => $payload['x_extra1'] ?? $payload['extra1'] ?? null,
            'x_amount' => $payload['x_amount'] ?? $payload['amount'] ?? null,
            'x_currency_code' => $payload['x_currency_code'] ?? $payload['currency'] ?? null,
            'x_cod_transaction_state' => $payload['x_cod_transaction_state'] ?? $payload['x_cod_response'] ?? $payload['x_cod_respuesta'] ?? null,
            'x_cod_response' => $payload['x_cod_response'] ?? $payload['x_cod_respuesta'] ?? $payload['x_cod_transaction_state'] ?? null,
            'x_transaction_id' => $payload['x_transaction_id'] ?? $payload['transaction_id'] ?? null,
            'x_response' => $responseText,
            'x_response_reason_text' => $payload['x_response_reason_text'] ?? $payload['response_reason_text'] ?? null,
            'x_transaction_date' => $payload['x_transaction_date'] ?? $payload['x_fecha_transaccion'] ?? $payload['date'] ?? null,
            'x_franchise' => $payload['x_franchise'] ?? $payload['franchise'] ?? null,
            'x_business' => $payload['x_business'] ?? $payload['business'] ?? null,
            'x_customer_email' => $payload['x_customer_email'] ?? $payload['email'] ?? null,
            'x_customer_name' => $payload['x_customer_name'] ?? $payload['name'] ?? null,
            'x_signature' => $payload['x_signature'] ?? null,
            'x_test_request' => $payload['x_test_request'] ?? null,
        ], static fn ($value) => $value !== null && $value !== '');
    }

    protected function extractOrderReference(array $payload): string
    {
        return (string) ($payload['x_extra1'] ?? $payload['x_id_factura'] ?? $payload['x_id_invoice'] ?? '');
    }

    protected function hasValidSignature(array $payload): bool
    {
        $signature = (string) ($payload['x_signature'] ?? '');

        if ($signature === '') {
            return true;
        }

        $merchantId = (string) ($payload['x_cust_id_cliente'] ?? '');
        $refPayco = (string) ($payload['x_ref_payco'] ?? $payload['ref_payco'] ?? '');
        $transactionId = (string) ($payload['x_transaction_id'] ?? '');
        $amount = (string) ($payload['x_amount'] ?? '');
        $currency = (string) ($payload['x_currency_code'] ?? '');

        if ($merchantId === '' || $refPayco === '' || $transactionId === '' || $amount === '' || $currency === '') {
            return false;
        }

        $generated = hash('sha256', implode('^', [
            $merchantId,
            (string) config('epayco.private_key'),
            $refPayco,
            $transactionId,
            $amount,
            $currency,
        ]));

        return hash_equals(strtolower($generated), strtolower($signature));
    }

    protected function shouldBypassSignatureValidation(array $payload): bool
    {
        if (! config('epayco.test')) {
            return false;
        }

        return $this->isTruthyValue($payload['x_test_request'] ?? null);
    }

    protected function isTruthyValue(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        $normalized = mb_strtoupper(trim((string) $value));

        return in_array($normalized, ['1', 'TRUE', 'YES', 'SI'], true);
    }

    protected function mapGatewayStatus(int $stateCode): string
    {
        return match ($stateCode) {
            1 => 'approved',
            2, 4, 6, 9, 10, 11 => 'rejected',
            default => 'pending',
        };
    }

    protected function mapGatewayStatusFromText(string $status): string
    {
        return match (mb_strtolower(trim($status))) {
            'aceptada', 'aceptado', 'approved' => 'approved',
            'rechazada', 'rechazado', 'fallida', 'fallido', 'failed', 'cancelada', 'cancelado', 'expirada', 'expirado', 'reversada', 'reversado' => 'rejected',
            default => 'pending',
        };
    }

    protected function resolveResponseViewData(array $gatewayData, ?PaymentTransaction $transaction): array
    {
        $status = $transaction?->status ?? $this->mapGatewayStatusFromText((string) ($gatewayData['x_response'] ?? ''));
        $gatewayReportedStatus = isset($gatewayData['x_cod_transaction_state']) || isset($gatewayData['x_cod_response'])
            ? $this->mapGatewayStatus((int) ($gatewayData['x_cod_transaction_state'] ?? $gatewayData['x_cod_response'] ?? 0))
            : $this->mapGatewayStatusFromText((string) ($gatewayData['x_response'] ?? ''));

        if ($status === 'failed_validation') {
            $status = match ($gatewayReportedStatus) {
                'approved' => 'approved',
                'rejected' => 'rejected',
                default => 'failed_validation',
            };
        }

        return match ($status) {
            'approved' => [
                'key' => 'approved',
                'eyebrow' => 'Pago confirmado',
                'title' => 'Tu pago fue aprobado',
                'message' => 'La transacción quedó registrada correctamente. Ya puedes continuar con confianza y seguir comprando.',
                'badge' => 'Aprobado',
            ],
            'rejected' => [
                'key' => 'rejected',
                'eyebrow' => 'Pago no aprobado',
                'title' => 'No pudimos aprobar este pago',
                'message' => 'ePayco reportó que la transacción no fue aprobada. Puedes revisar el motivo y volver a intentarlo con otro medio de pago.',
                'badge' => 'Rechazado',
            ],
            'failed_validation' => [
                'key' => 'warning',
                'eyebrow' => 'Validación pendiente',
                'title' => 'Recibimos la respuesta, pero necesita revisión',
                'message' => 'La respuesta de ePayco llegó con datos que no coincidieron por completo con la orden local. El pedido no se marcó como pagado.',
                'badge' => 'Por revisar',
            ],
            default => [
                'key' => 'pending',
                'eyebrow' => 'Pago en proceso',
                'title' => 'Tu pago sigue pendiente',
                'message' => 'ePayco todavía no reporta una aprobación final. Conservamos la orden en estado pendiente mientras llega la confirmación del webhook.',
                'badge' => 'Pendiente',
            ],
        };
    }

    protected function sendTransactionStatusEmailIfNeeded(PaymentTransaction $transaction, string $previousStatus): void
    {
        $customerEmail = $transaction->order?->customer?->email;

        if (! $customerEmail) {
            Log::warning('Skipping payment status email because the order customer has no email address.', [
                'transaction_id' => $transaction->id,
                'order_ref' => $transaction->order_ref,
                'status' => $transaction->status,
            ]);

            return;
        }

        $alreadyNotifiedForCurrentStatus = $transaction->customer_notified_at !== null
            && $transaction->customer_notification_status === $transaction->status;

        if ($alreadyNotifiedForCurrentStatus) {
            return;
        }

        $this->runAfterResponse(function () use ($transaction, $customerEmail, $previousStatus): void {
            $freshTransaction = PaymentTransaction::query()
                ->with(['order.customer', 'order.items'])
                ->find($transaction->id);

            if (! $freshTransaction) {
                return;
            }

            $alreadyNotifiedForCurrentStatus = $freshTransaction->customer_notified_at !== null
                && $freshTransaction->customer_notification_status === $freshTransaction->status;

            if ($alreadyNotifiedForCurrentStatus) {
                return;
            }

            try {
                if ($this->isEntrepreneurPlanApproval($freshTransaction)) {
                    [$entrepreneurUser, $entrepreneurStore, $plainPassword] = $this->provisionEntrepreneurBackofficeAccess($freshTransaction);

                    Mail::to($customerEmail)->send(
                        new EntrepreneurWelcomeMail($freshTransaction, $entrepreneurUser, $entrepreneurStore, $plainPassword)
                    );
                } else {
                    Mail::to($customerEmail)->send(new PaymentTransactionStatusMail($freshTransaction));
                }

                $freshTransaction->forceFill([
                    'customer_notification_status' => $freshTransaction->status,
                    'customer_notified_at' => now(),
                ])->save();

                Log::info('Payment status email sent to customer.', [
                    'transaction_id' => $freshTransaction->id,
                    'order_ref' => $freshTransaction->order_ref,
                    'status' => $freshTransaction->status,
                    'previous_status' => $previousStatus,
                    'customer_email' => $customerEmail,
                    'flow' => $freshTransaction->request_payload['flow'] ?? 'store_order',
                ]);
            } catch (\Throwable $exception) {
                Log::error('Payment status email could not be sent.', [
                    'transaction_id' => $freshTransaction->id,
                    'order_ref' => $freshTransaction->order_ref,
                    'status' => $freshTransaction->status,
                    'previous_status' => $previousStatus,
                    'customer_email' => $customerEmail,
                    'message' => $exception->getMessage(),
                ]);
            }
        });
    }

    protected function isEntrepreneurPlanApproval(PaymentTransaction $transaction): bool
    {
        return ($transaction->request_payload['flow'] ?? null) === 'entrepreneur_plan'
            && $transaction->status === 'approved';
    }

    protected function provisionEntrepreneurBackofficeAccess(PaymentTransaction $transaction): array
    {
        $payload = $transaction->request_payload ?? [];
        $entrepreneur = $payload['entrepreneur'] ?? [];
        $customer = $transaction->order?->customer;

        $firstName = (string) ($entrepreneur['first_name'] ?? $customer?->first_name ?? 'Emprendedor');
        $lastName = (string) ($entrepreneur['last_name'] ?? $customer?->last_name ?? '');
        $email = (string) ($entrepreneur['email'] ?? $customer?->email ?? '');
        $storeName = (string) ($entrepreneur['store_name'] ?? 'Mi tienda');
        $fullName = trim($firstName.' '.$lastName);
        $plainPassword = 'Emp'.random_int(1000, 9999).Str::upper(Str::random(4));

        $user = User::query()->firstOrNew(['email' => $email]);
        $user->fill([
            'name' => $fullName !== '' ? $fullName : $storeName,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $entrepreneur['phone'] ?? $customer?->phone,
            'department' => $entrepreneur['department'] ?? $customer?->department,
            'city' => $entrepreneur['city'] ?? $customer?->city,
            'password' => $plainPassword,
            'is_admin' => true,
        ]);
        $user->save();

        $store = Store::query()->firstOrNew(['email' => $email]);
        $store->fill([
            'name' => $storeName,
            'slug' => $this->generateUniqueStoreSlug($storeName, $store->exists ? $store->id : null),
            'owner_name' => $fullName !== '' ? $fullName : $storeName,
            'email' => $email,
            'phone' => $entrepreneur['phone'] ?? $customer?->phone,
            'location' => trim((string) (($entrepreneur['city'] ?? '').', '.($entrepreneur['department'] ?? '')), ', '),
            'short_description' => Str::limit((string) ($entrepreneur['description'] ?? ''), 160, ''),
            'description' => $entrepreneur['description'] ?? null,
            'website' => $entrepreneur['website'] ?? null,
            'instagram_url' => $this->normalizeInstagramUrl($entrepreneur['instagram'] ?? null),
            'whatsapp' => $entrepreneur['phone'] ?? null,
            'is_active' => true,
        ]);
        $store->save();

        return [$user, $store, $plainPassword];
    }

    protected function generateUniqueStoreSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base !== '' ? $base : 'tienda-emprendedora';
        $candidate = $slug;
        $counter = 2;

        while (
            Store::query()
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->where('slug', $candidate)
                ->exists()
        ) {
            $candidate = $slug.'-'.$counter;
            $counter++;
        }

        return $candidate;
    }

    protected function normalizeInstagramUrl(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $trimmed = trim($value);

        if ($trimmed === '') {
            return null;
        }

        if (Str::startsWith($trimmed, ['http://', 'https://'])) {
            return $trimmed;
        }

        return 'https://instagram.com/'.ltrim($trimmed, '@');
    }

    protected function runAfterResponse(Closure $callback): void
    {
        app()->terminating($callback);
    }

    protected function removeApprovedOrderProductsFromCart(?PaymentTransaction $transaction): void
    {
        $productIds = $transaction?->order?->items?->pluck('product_id') ?? collect();

        if ($productIds->isEmpty()) {
            return;
        }

        $this->cart->removePurchasedProducts($productIds);
    }
}

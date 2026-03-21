<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Support\OrderNumber;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::query()
            ->with(['customer', 'paymentTransactions'])
            ->withCount('items')
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', [
            'orders' => $orders,
            'statusLabels' => $this->statusOptions(),
            'stats' => [
                'total' => Order::count(),
                'pending' => Order::where('status', 'pending')->count(),
                'paid' => Order::where('status', 'paid')->count(),
                'sales' => (float) Order::sum('total'),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.orders.form', $this->formData(
            new Order([
                'customer_id' => request()->integer('customer') ?: null,
                'status' => 'pending',
                'payment_method' => 'epayco',
                'shipping' => 0,
                'tax' => 0,
            ])
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $order = DB::transaction(function () use ($data) {
            $customer = Customer::query()->findOrFail($data['customer_id']);

            $order = Order::query()->create([
                'customer_id' => $customer->id,
                'number' => 'TMP-'.Str::uuid(),
                'status' => $data['status'],
                'subtotal' => $data['subtotal'],
                'shipping' => $data['shipping'],
                'tax' => $data['tax'],
                'total' => $data['total'],
                'payment_method' => $data['payment_method'],
                'shipping_address' => $data['shipping_address'] ?: $this->customerAddress($customer),
                'notes' => $data['notes'],
            ]);

            $order->update(['number' => OrderNumber::format($order->id)]);
            $this->syncItems($order, $data['items']);

            return $order;
        });

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('status', 'Pedido creado correctamente.');
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load(['customer', 'items.product', 'paymentTransactions']),
            'statusLabels' => $this->statusOptions(),
            'paymentLabels' => $this->paymentMethodOptions(),
        ]);
    }

    public function edit(Order $order): View
    {
        return view('admin.orders.form', $this->formData($order->load('items')));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($data, $order) {
            $customer = Customer::query()->findOrFail($data['customer_id']);

            $order->update([
                'customer_id' => $customer->id,
                'status' => $data['status'],
                'subtotal' => $data['subtotal'],
                'shipping' => $data['shipping'],
                'tax' => $data['tax'],
                'total' => $data['total'],
                'payment_method' => $data['payment_method'],
                'shipping_address' => $data['shipping_address'] ?: $this->customerAddress($customer),
                'notes' => $data['notes'],
            ]);

            $this->syncItems($order, $data['items']);

            $order->paymentTransactions()->update([
                'order_ref' => $order->number,
                'amount' => $order->total,
            ]);
        });

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('status', 'Pedido actualizado correctamente.');
    }

    public function destroy(Order $order): RedirectResponse
    {
        if ($order->paymentTransactions()->where('status', 'approved')->exists()) {
            return redirect()
                ->route('admin.orders.show', $order)
                ->with('status', 'No puedes eliminar un pedido con una transacción aprobada.');
        }

        DB::transaction(function () use ($order) {
            $order->paymentTransactions()->delete();
            $order->delete();
        });

        return redirect()
            ->route('admin.orders.index')
            ->with('status', 'Pedido eliminado.');
    }

    protected function formData(Order $order): array
    {
        return [
            'order' => $order,
            'customers' => Customer::query()->orderBy('first_name')->orderBy('last_name')->get(),
            'products' => Product::query()->with('category')->orderBy('name')->get(),
            'statusOptions' => $this->statusOptions(),
            'paymentMethodOptions' => $this->paymentMethodOptions(),
        ];
    }

    protected function validated(Request $request): array
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'status' => ['required', Rule::in(array_keys($this->statusOptions()))],
            'payment_method' => ['nullable', Rule::in(array_keys($this->paymentMethodOptions()))],
            'shipping' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'shipping_address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array'],
        ]);

        $items = $this->normalizeItems($request->input('items', []));

        if ($items->isEmpty()) {
            throw ValidationException::withMessages([
                'items' => 'Debes agregar al menos un producto al pedido.',
            ]);
        }

        $itemValidator = Validator::make(
            ['items' => $items->all()],
            [
                'items' => ['required', 'array', 'min:1'],
                'items.*.product_id' => ['nullable', 'integer', 'exists:products,id'],
                'items.*.name' => ['required', 'string', 'max:150'],
                'items.*.sku' => ['required', 'string', 'max:80'],
                'items.*.quantity' => ['required', 'integer', 'min:1'],
                'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            ]
        );

        $validatedItems = collect($itemValidator->validate()['items'])
            ->map(function (array $item): array {
                $quantity = (int) $item['quantity'];
                $unitPrice = round((float) $item['unit_price'], 2);

                return [
                    'product_id' => $item['product_id'] ?: null,
                    'name' => $item['name'],
                    'sku' => $item['sku'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total' => round($quantity * $unitPrice, 2),
                ];
            });

        $subtotal = round((float) $validatedItems->sum('total'), 2);
        $shipping = round((float) ($validated['shipping'] ?? 0), 2);
        $tax = round((float) ($validated['tax'] ?? 0), 2);

        return [
            'customer_id' => (int) $validated['customer_id'],
            'status' => $validated['status'],
            'payment_method' => $validated['payment_method'] ?: null,
            'shipping' => $shipping,
            'tax' => $tax,
            'subtotal' => $subtotal,
            'total' => round($subtotal + $shipping + $tax, 2),
            'shipping_address' => $validated['shipping_address'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'items' => $validatedItems->all(),
        ];
    }

    protected function normalizeItems(array $items): Collection
    {
        $productIds = collect($items)
            ->pluck('product_id')
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value) => (int) $value)
            ->unique()
            ->values();

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        return collect($items)
            ->map(function ($item) use ($products): array {
                $productId = filled($item['product_id'] ?? null) ? (int) $item['product_id'] : null;
                $product = $productId ? $products->get($productId) : null;

                return [
                    'product_id' => $productId,
                    'name' => trim((string) ($item['name'] ?? ($product?->name ?? ''))),
                    'sku' => trim((string) ($item['sku'] ?? ($product?->sku ?? ''))),
                    'quantity' => (int) ($item['quantity'] ?? 1),
                    'unit_price' => $item['unit_price'] !== null && $item['unit_price'] !== ''
                        ? (float) $item['unit_price']
                        : (float) ($product?->price ?? 0),
                ];
            })
            ->filter(function (array $item): bool {
                return $item['product_id']
                    || $item['name'] !== ''
                    || $item['sku'] !== ''
                    || $item['quantity'] > 0
                    || $item['unit_price'] > 0;
            })
            ->values();
    }

    protected function syncItems(Order $order, array $items): void
    {
        $order->items()->delete();

        foreach ($items as $item) {
            $order->items()->create($item);
        }
    }

    protected function customerAddress(Customer $customer): string
    {
        return collect([
            $customer->address,
            $customer->city,
            $customer->department,
        ])->filter()->implode(', ');
    }

    protected function statusOptions(): array
    {
        return [
            'pending' => 'Pendiente',
            'paid' => 'Pagado',
            'processing' => 'En preparación',
            'shipped' => 'Enviado',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
        ];
    }

    protected function paymentMethodOptions(): array
    {
        return [
            'epayco' => 'ePayco',
            'bank_transfer' => 'Transferencia bancaria',
            'cash' => 'Efectivo',
            'contraentrega' => 'Contraentrega',
            'manual' => 'Registro manual',
        ];
    }
}

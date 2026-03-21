<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;
use App\Support\ColombiaDivipola;
use App\Support\OrderNumber;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function __construct(private readonly CartService $cart)
    {
    }

    public function index(): View|RedirectResponse
    {
        if ($this->cart->count() === 0) {
            return redirect()->route('store.shop')->with('status', 'Agrega productos antes de ir al checkout.');
        }

        return view('store.checkout', [
            'items' => $this->cart->items(),
            'cartCount' => $this->cart->count(),
            'subtotal' => $this->cart->subtotal(),
            'tax' => $this->cart->tax(),
            'shipping' => $this->cart->shipping(),
            'total' => $this->cart->total(),
            'departments' => ColombiaDivipola::departments(),
            'citiesByDepartment' => ColombiaDivipola::citiesByDepartment(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:50'],
            'department' => ['required', 'string', 'size:2'],
            'city' => ['required', 'string', 'max:10'],
            'address' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'string', 'max:50'],
        ]);

        $department = ColombiaDivipola::findDepartment($validated['department']);
        $city = ColombiaDivipola::findCity($validated['department'], $validated['city']);

        if (! $department) {
            throw ValidationException::withMessages([
                'department' => 'Selecciona un departamento válido según el listado oficial del DANE.',
            ]);
        }

        if (! $city) {
            throw ValidationException::withMessages([
                'city' => 'Selecciona una ciudad válida para el departamento elegido.',
            ]);
        }

        $items = $this->cart->items();
        abort_if($items->isEmpty(), 422, 'El carrito está vacío.');

        $order = DB::transaction(function () use ($validated, $items, $department, $city) {
            $customer = Customer::query()->updateOrCreate(
                ['email' => $validated['email']],
                [
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'phone' => $validated['phone'] ?? null,
                    'department' => $department['name'],
                    'city' => $city['name'],
                    'address' => $validated['address'],
                ]
            );

            $order = Order::query()->create([
                'customer_id' => $customer->id,
                'number' => 'TMP-'.Str::upper((string) Str::uuid()),
                'status' => 'pending',
                'subtotal' => $this->cart->subtotal(),
                'shipping' => $this->cart->shipping(),
                'tax' => $this->cart->tax(),
                'total' => $this->cart->total(),
                'payment_method' => $validated['payment_method'],
                'shipping_address' => $validated['address'].', '.$city['name'].', '.$department['name'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $order->update(['number' => OrderNumber::format($order->id)]);

            $order->refresh();

            foreach ($items as $item) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'name' => $item['name'],
                    'sku' => $item['sku'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['line_total'],
                ]);
            }

            PaymentTransaction::query()->create([
                'order_id' => $order->id,
                'order_ref' => $order->number,
                'gateway' => 'epayco',
                'status' => 'pending',
                'amount' => $order->total,
                'currency' => config('epayco.currency', 'COP'),
                'request_payload' => [
                    'customer' => [
                        'email' => $customer->email,
                        'first_name' => $customer->first_name,
                        'last_name' => $customer->last_name,
                        'phone' => $customer->phone,
                        'department' => $customer->department,
                        'city' => $customer->city,
                        'address' => $customer->address,
                    ],
                    'items' => $items->values()->all(),
                    'payment_method' => $validated['payment_method'],
                ],
            ]);

            return $order;
        });

        return redirect()
            ->route('epayco.checkout', ['order_ref' => $order->number])
            ->with('status', 'Tu pedido fue creado. Continúa con el pago en ePayco.');
    }
}

<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Support\ColombiaDivipola;
use App\Support\EntrepreneurPlans;
use App\Support\OrderNumber;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EntrepreneurController extends Controller
{
    public function show(string $plan): View
    {
        $selectedPlan = EntrepreneurPlans::find($plan);
        abort_unless($selectedPlan, 404);

        return view('store.entrepreneur-apply', [
            'plan' => $selectedPlan,
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
            'departments' => ColombiaDivipola::departments(),
            'citiesByDepartment' => ColombiaDivipola::citiesByDepartment(),
        ]);
    }

    public function store(Request $request, string $plan): RedirectResponse
    {
        $selectedPlan = EntrepreneurPlans::find($plan);
        abort_unless($selectedPlan, 404);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:50'],
            'store_name' => ['required', 'string', 'max:150'],
            'business_category' => ['required', 'string', 'exists:categories,name'],
            'department' => ['required', 'string', 'size:2'],
            'city' => ['required', 'string', 'max:10'],
            'address' => ['required', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:120'],
            'website' => ['nullable', 'url', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'accept_terms' => ['accepted'],
        ], [
            'accept_terms.accepted' => 'Debes aceptar los terminos y condiciones para continuar.',
        ]);

        $department = ColombiaDivipola::findDepartment($validated['department']);
        $city = ColombiaDivipola::findCity($validated['department'], $validated['city']);

        if (! $department) {
            throw ValidationException::withMessages([
                'department' => 'Selecciona un departamento valido segun el listado oficial del DANE.',
            ]);
        }

        if (! $city) {
            throw ValidationException::withMessages([
                'city' => 'Selecciona una ciudad valida para el departamento elegido.',
            ]);
        }

        $order = DB::transaction(function () use ($validated, $selectedPlan, $department, $city) {
            $customer = Customer::query()->updateOrCreate(
                ['email' => $validated['email']],
                [
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'phone' => $validated['phone'],
                    'department' => $department['name'],
                    'city' => $city['name'],
                    'address' => $validated['address'],
                ]
            );

            $order = Order::query()->create([
                'customer_id' => $customer->id,
                'number' => 'TMP-ENTREPRENEUR',
                'status' => 'pending',
                'subtotal' => $selectedPlan['price'],
                'shipping' => 0,
                'tax' => 0,
                'total' => $selectedPlan['price'],
                'payment_method' => 'epayco',
                'shipping_address' => $validated['address'].', '.$city['name'].', '.$department['name'],
                'notes' => 'Solicitud emprendedor | '.$selectedPlan['name'].' | Tienda: '.$validated['store_name'],
            ]);

            $order->update(['number' => OrderNumber::format($order->id)]);
            $order->refresh();

            PaymentTransaction::query()->create([
                'order_id' => $order->id,
                'order_ref' => $order->number,
                'gateway' => 'epayco',
                'status' => 'pending',
                'amount' => $selectedPlan['price'],
                'currency' => config('epayco.currency', 'COP'),
                'request_payload' => [
                    'flow' => 'entrepreneur_plan',
                    'plan' => [
                        'slug' => $selectedPlan['slug'],
                        'name' => $selectedPlan['name'],
                        'price' => $selectedPlan['price'],
                    ],
                    'entrepreneur' => [
                        'first_name' => $validated['first_name'],
                        'last_name' => $validated['last_name'],
                        'email' => $validated['email'],
                        'phone' => $validated['phone'],
                        'store_name' => $validated['store_name'],
                        'business_category' => $validated['business_category'],
                        'department' => $department['name'],
                        'city' => $city['name'],
                        'address' => $validated['address'],
                        'instagram' => $validated['instagram'] ?? null,
                        'website' => $validated['website'] ?? null,
                        'description' => $validated['description'],
                    ],
                    'accepted_terms' => true,
                ],
            ]);

            return $order;
        });

        return redirect()
            ->route('epayco.checkout', ['order_ref' => $order->number])
            ->with('status', 'Tu solicitud emprendedora fue registrada. Continua con el pago en ePayco.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Support\ColombiaDivipola;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    public function index(): View
    {
        return view('admin.customers.index', [
            'customers' => Customer::withCount('orders')->latest()->paginate(15),
            'stats' => [
                'total' => Customer::count(),
                'with_orders' => Customer::has('orders')->count(),
                'without_orders' => Customer::doesntHave('orders')->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.customers.form', $this->formData(new Customer()));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        Customer::query()->create($data);

        return redirect()->route('admin.customers.index')->with('status', 'Cliente creado.');
    }

    public function show(Customer $customer): View
    {
        return view('admin.customers.show', [
            'customer' => $customer->load(['orders' => fn ($query) => $query->latest()]),
        ]);
    }

    public function edit(Customer $customer): View
    {
        return view('admin.customers.form', $this->formData($customer));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $data = $this->validated($request, $customer);
        $customer->update($data);

        return redirect()->route('admin.customers.show', $customer)->with('status', 'Cliente actualizado.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        if ($customer->orders()->exists()) {
            return redirect()
                ->route('admin.customers.index')
                ->with('status', 'No puedes eliminar un cliente que ya tiene pedidos registrados.');
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')->with('status', 'Cliente eliminado.');
    }

    protected function formData(Customer $customer): array
    {
        $departments = ColombiaDivipola::departments();
        $citiesByDepartment = ColombiaDivipola::citiesByDepartment();
        $selectedDepartment = old('department_code') ?: $this->resolveDepartmentCode($customer->department);
        $selectedCity = old('city_code') ?: $this->resolveCityCode($selectedDepartment, $customer->city);

        return [
            'customer' => $customer,
            'departments' => $departments,
            'citiesByDepartment' => $citiesByDepartment,
            'selectedDepartmentCode' => $selectedDepartment,
            'selectedCityCode' => $selectedCity,
        ];
    }

    protected function validated(Request $request, ?Customer $customer = null): array
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('customers', 'email')->ignore($customer),
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'department_code' => ['required', 'string', 'size:2'],
            'city_code' => ['required', 'string', 'max:10'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $department = ColombiaDivipola::findDepartment($validated['department_code']);
        $city = ColombiaDivipola::findCity($validated['department_code'], $validated['city_code']);

        if (! $department) {
            throw ValidationException::withMessages([
                'department_code' => 'Selecciona un departamento válido según el listado oficial del DANE.',
            ]);
        }

        if (! $city) {
            throw ValidationException::withMessages([
                'city_code' => 'Selecciona una ciudad válida para el departamento elegido.',
            ]);
        }

        return [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'department' => $department['name'],
            'city' => $city['name'],
            'address' => $validated['address'] ?? null,
        ];
    }

    protected function resolveDepartmentCode(?string $departmentName): ?string
    {
        if (! $departmentName) {
            return null;
        }

        foreach (ColombiaDivipola::departments() as $department) {
            if (($department['name'] ?? null) === $departmentName) {
                return $department['code'];
            }
        }

        return null;
    }

    protected function resolveCityCode(?string $departmentCode, ?string $cityName): ?string
    {
        if (! $departmentCode || ! $cityName) {
            return null;
        }

        foreach (ColombiaDivipola::citiesByDepartment()[$departmentCode] ?? [] as $city) {
            if (($city['name'] ?? null) === $cityName) {
                return $city['code'];
            }
        }

        return null;
    }
}

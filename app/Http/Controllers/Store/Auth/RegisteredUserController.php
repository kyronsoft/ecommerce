<?php

namespace App\Http\Controllers\Store\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ColombiaDivipola;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('store.auth.register', [
            'departments' => ColombiaDivipola::departments(),
            'citiesByDepartment' => ColombiaDivipola::citiesByDepartment(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'department_code' => ['required', 'string', 'max:10'],
            'city_code' => ['required', 'string', 'max:10'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        $department = ColombiaDivipola::findDepartment($validated['department_code']);
        $city = ColombiaDivipola::findCity($validated['department_code'], $validated['city_code']);

        if (! $department || ! $city) {
            throw ValidationException::withMessages([
                'city_code' => 'Selecciona un departamento y una ciudad válidos.',
            ]);
        }

        $user = User::query()->create([
            'name' => trim($validated['first_name'].' '.$validated['last_name']),
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'department' => (string) ($department['name'] ?? ''),
            'city' => (string) ($city['name'] ?? ''),
            'department_code' => $validated['department_code'],
            'city_code' => $validated['city_code'],
            'password' => $validated['password'],
            'is_admin' => false,
            'last_login_at' => now(),
        ]);

        $request->session()->regenerate();
        $request->session()->put([
            'store_user_id' => $user->id,
            'store_user_name' => $user->name,
            'store_user_email' => $user->email,
        ]);

        return redirect()->route('store.home')->with('status', 'Tu cuenta fue creada correctamente. Ya puedes comprar con tu perfil.');
    }
}

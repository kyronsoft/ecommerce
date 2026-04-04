<?php

namespace App\Http\Controllers\Store\Auth;

use App\Http\Controllers\Controller;
use App\Mail\CustomerWelcomeMail;
use App\Models\User;
use App\Support\ColombiaDivipola;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
            'first_name.required' => 'Por favor ingresa tus nombres.',
            'first_name.max' => 'Los nombres no pueden superar los 120 caracteres.',
            'last_name.required' => 'Por favor ingresa tus apellidos.',
            'last_name.max' => 'Los apellidos no pueden superar los 120 caracteres.',
            'email.required' => 'Por favor ingresa tu correo electrónico.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.max' => 'El correo electrónico no puede superar los 255 caracteres.',
            'email.unique' => 'Este correo ya está registrado. Puedes ingresar con tu cuenta o usar otro correo.',
            'phone.required' => 'Por favor ingresa tu número de teléfono.',
            'phone.max' => 'El teléfono no puede superar los 30 caracteres.',
            'department_code.required' => 'Selecciona un departamento.',
            'city_code.required' => 'Selecciona una ciudad.',
            'password.required' => 'Crea una contraseña para tu cuenta.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ], [
            'first_name' => 'nombres',
            'last_name' => 'apellidos',
            'email' => 'correo electrónico',
            'phone' => 'teléfono',
            'department_code' => 'departamento',
            'city_code' => 'ciudad',
            'password' => 'contraseña',
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

        app()->terminating(function () use ($user): void {
            $freshUser = User::query()->find($user->id);

            if (! $freshUser) {
                return;
            }

            try {
                Mail::to($freshUser->email)->send(new CustomerWelcomeMail($freshUser));

                Log::info('Customer welcome email sent.', [
                    'user_id' => $freshUser->id,
                    'email' => $freshUser->email,
                ]);
            } catch (\Throwable $exception) {
                Log::error('Customer welcome email could not be sent.', [
                    'user_id' => $freshUser->id,
                    'email' => $freshUser->email,
                    'message' => $exception->getMessage(),
                ]);
            }
        });

        return redirect()->route('store.home')->with('status', 'Tu cuenta fue creada correctamente. Ya puedes comprar con tu perfil.');
    }
}

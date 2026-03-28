<?php

namespace App\Http\Controllers\Store\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('store.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()
            ->where('email', $credentials['email'])
            ->where('is_admin', false)
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Las credenciales del cliente no son correctas.',
            ]);
        }

        $request->session()->regenerate();
        $request->session()->put([
            'store_user_id' => $user->id,
            'store_user_name' => $user->name,
            'store_user_email' => $user->email,
        ]);

        $user->forceFill(['last_login_at' => now()])->save();

        return redirect()->intended(route('store.home'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget([
            'store_user_id',
            'store_user_name',
            'store_user_email',
        ]);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('store.home')->with('status', 'Sesión cerrada correctamente.');
    }
}

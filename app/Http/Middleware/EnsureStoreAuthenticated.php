<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class EnsureStoreAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $storeUserId = $request->session()->get('store_user_id');
        $storeUser = $storeUserId ? User::query()->whereKey($storeUserId)->where('is_admin', false)->first() : null;

        if (! $storeUser) {
            $request->session()->forget([
                'store_user_id',
                'store_user_name',
                'store_user_email',
            ]);

            return redirect()->guest(route('store.login'));
        }

        $request->attributes->set('storeUser', $storeUser);
        View::share('currentStoreUser', $storeUser);

        return $next($request);
    }
}

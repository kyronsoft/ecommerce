<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfStoreAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $storeUserId = $request->session()->get('store_user_id');

        if ($storeUserId && User::query()->whereKey($storeUserId)->where('is_admin', false)->exists()) {
            return redirect()->route('store.home');
        }

        return $next($request);
    }
}

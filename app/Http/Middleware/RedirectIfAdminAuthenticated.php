<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAdminAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $adminId = $request->session()->get('admin_user_id');

        if ($adminId && User::query()->whereKey($adminId)->where('is_admin', true)->exists()) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}

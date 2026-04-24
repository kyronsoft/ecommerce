<?php

namespace App\Http\Middleware;

use App\Models\Store;
use App\Models\User;
use App\Support\AdminPanelScope;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $adminId = $request->session()->get('admin_user_id');
        $adminUser = $adminId ? User::query()->whereKey($adminId)->where('is_admin', true)->first() : null;

        if (! $adminUser) {
            $request->session()->forget([
                'admin_user_id',
                'admin_user_name',
                'admin_user_email',
            ]);

            return redirect()->guest(route('admin.login'));
        }

        $isSuperAdmin = AdminPanelScope::isSuperAdmin($adminUser);
        $adminStore = AdminPanelScope::resolveStoreForUser($adminUser);

        $request->attributes->set('adminUser', $adminUser);
        $request->attributes->set('adminStore', $adminStore);
        $request->attributes->set('adminIsSuperAdmin', $isSuperAdmin);
        View::share('currentAdminUser', $adminUser);
        View::share('currentAdminStore', $adminStore);
        View::share('currentAdminIsSuperAdmin', $isSuperAdmin);

        return $next($request);
    }
}

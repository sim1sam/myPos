<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolePermissionMiddleware
{
    private const ROUTE_PERMISSION_MAP = [
        'dashboard' => 'dashboard.view',
        'pos.sales' => 'sales.create',
        'pos.purchases' => 'purchases.view',
        'pos.purchases.index' => 'purchases.view',
        'pos.purchases.create' => 'purchases.create',
        'pos.purchases.store' => 'purchases.create',
        'pos.purchases.edit' => 'purchases.edit',
        'pos.purchases.update' => 'purchases.edit',
        'pos.purchases.destroy' => 'purchases.delete',
        'pos.vendors' => 'vendors.view',
        'pos.vendors.index' => 'vendors.view',
        'pos.vendors.create' => 'vendors.create',
        'pos.vendors.store' => 'vendors.create',
        'pos.vendors.edit' => 'vendors.edit',
        'pos.vendors.update' => 'vendors.edit',
        'pos.vendors.destroy' => 'vendors.delete',
        'pos.products' => 'invoices.dashboard',
        'pos.invoices.index' => 'invoices.view',
        'pos.invoices.show' => 'invoices.view',
        'pos.invoices.create' => 'invoices.create',
        'pos.invoices.store' => 'invoices.create',
        'pos.invoices.edit' => 'invoices.edit',
        'pos.invoices.update' => 'invoices.edit',
        'pos.invoices.destroy' => 'invoices.delete',
        'pos.customers' => 'customers.view',
        'pos.customers.index' => 'customers.view',
        'pos.customers.summary' => 'customers.view',
        'pos.customers.ledger' => 'customers.view',
        'pos.customers.summary-details' => 'customers.view',
        'pos.customers.create' => 'customers.create',
        'pos.customers.store' => 'customers.create',
        'pos.customers.edit' => 'customers.edit',
        'pos.customers.update' => 'customers.edit',
        'pos.customers.destroy' => 'customers.delete',
        'pos.inventory' => 'payments.create',
        'pos.payments.index' => 'payments.view',
        'pos.payments.store' => 'payments.create',
        'pos.reports' => 'reports.view',
        'pos.expenses' => 'expenses.view',
        'pos.expenses.list' => 'expenses.view',
        'pos.expenses.create' => 'expenses.create',
        'pos.expenses.store' => 'expenses.create',
        'pos.expenses.heads' => 'expenses.create',
        'pos.expenses.heads.store' => 'expenses.create',
        'pos.expenses.edit' => 'expenses.edit',
        'pos.expenses.update' => 'expenses.edit',
        'pos.expenses.destroy' => 'expenses.delete',
        'pos.settings' => 'settings.view',
        'pos.settings.gst-rates' => 'settings.gst_rates',
        'pos.settings.gst-rates.store' => 'settings.gst_rates',
        'pos.settings.payment-modes.index' => 'settings.payment_modes',
        'pos.settings.payment-modes.create' => 'settings.payment_modes',
        'pos.settings.payment-modes.store' => 'settings.payment_modes',
        'pos.settings.company-profile' => 'settings.company_profile',
        'pos.settings.company-profile.update' => 'settings.company_profile',
        'pos.settings.users' => 'settings.users',
        'pos.settings.users.store' => 'settings.users',
        'pos.settings.users.edit' => 'settings.users',
        'pos.settings.users.update' => 'settings.users',
        'pos.settings.users.destroy' => 'settings.users',
        'pos.settings.roles' => 'settings.roles',
        'pos.settings.roles.store' => 'settings.roles',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        // Keep existing/admin users working even if no role assigned yet.
        if (!$user->role) {
            return $next($request);
        }

        $routeName = (string) optional($request->route())->getName();
        $requiredPermission = $this->resolvePermission($routeName);

        if (!$requiredPermission) {
            return $next($request);
        }

        $permissions = $user->role->permissions ?? [];
        if (!in_array($requiredPermission, $permissions, true)) {
            abort(403, 'You do not have access to this feature.');
        }

        return $next($request);
    }

    private function resolvePermission(string $routeName): ?string
    {
        return self::ROUTE_PERMISSION_MAP[$routeName] ?? null;
    }
}

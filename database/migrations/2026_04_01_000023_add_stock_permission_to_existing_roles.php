<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Role::query()->each(function (Role $role): void {
            $permissions = $role->permissions ?? [];

            if ($permissions === []) {
                return;
            }

            $hasStock = in_array('stock', $permissions, true)
                || in_array('stock.view', $permissions, true);

            if ($hasStock) {
                return;
            }

            $shouldGrant = in_array('settings', $permissions, true)
                || in_array('purchases', $permissions, true);

            if (!$shouldGrant) {
                return;
            }

            $permissions[] = 'stock';

            $role->update([
                'permissions' => array_values(array_unique($permissions)),
            ]);
        });
    }

    public function down(): void
    {
        Role::query()->each(function (Role $role): void {
            $permissions = $role->permissions ?? [];

            if ($permissions === []) {
                return;
            }

            $role->update([
                'permissions' => array_values(array_filter(
                    $permissions,
                    static fn (string $permission) => $permission !== 'stock'
                )),
            ]);
        });
    }
};

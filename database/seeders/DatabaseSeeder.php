<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    private const ADMIN_EMAIL = 'admin@mypos.test';
    private const ADMIN_PASSWORD = 'Admin@12345';

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => self::ADMIN_EMAIL],
            [
                'name' => 'Admin',
                'password' => self::ADMIN_PASSWORD,
                'email_verified_at' => now(),
            ],
        );
    }
}

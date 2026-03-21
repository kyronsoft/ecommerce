<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_DEFAULT_EMAIL', 'jaruizr74@gmail.com');
        $password = env('ADMIN_DEFAULT_PASSWORD', 'KyronAdmin2026!');
        $name = env('ADMIN_DEFAULT_NAME', 'Administrador');

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'is_admin' => true,
            ]
        );
    }
}

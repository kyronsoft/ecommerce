<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('demo:reset', function () {
    $this->call('migrate:fresh', ['--seed' => true]);
    $this->info('Datos demo regenerados.');
});

Artisan::command('admin:ensure-default', function () {
    $email = env('ADMIN_DEFAULT_EMAIL', 'jaruizr74@gmail.com');
    $password = env('ADMIN_DEFAULT_PASSWORD', 'KyronAdmin2026!');
    $name = env('ADMIN_DEFAULT_NAME', 'Administrador');

    $user = User::query()->updateOrCreate(
        ['email' => $email],
        [
            'name' => $name,
            'password' => Hash::make($password),
            'is_admin' => true,
        ]
    );

    $this->info('Administrador listo: '.$user->email);
});

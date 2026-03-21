<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('demo:reset', function () {
    $this->call('migrate:fresh', ['--seed' => true]);
    $this->info('Datos demo regenerados.');
});

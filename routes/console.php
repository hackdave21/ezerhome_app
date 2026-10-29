<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Rappel mensuel des dons réguliers (phase 1 : notification, sans prélèvement).
Schedule::command('donations:monthly-reminder')->monthlyOn(1, '08:00');

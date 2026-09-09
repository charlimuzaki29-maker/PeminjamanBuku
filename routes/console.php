<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes (Perintah Artisan Berbasis Terminal)
|--------------------------------------------------------------------------
|
| File ini digunakan untuk mendefinisikan perintah CLI / Console berbasis Closure
| yang dapat dijalankan melalui terminal dengan mengetikkan `php artisan <nama_command>`.
|
*/

// Contoh perintah `php artisan inspire` untuk menampilkan kutipan inspiratif di terminal
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Menampilkan kutipan inspiratif di terminal');


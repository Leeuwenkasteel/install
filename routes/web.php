<?php
use Illuminate\Support\Facades\Route;
use Leeuwenkasteel\Install\Http\Controllers\InstallController;
use Leeuwenkasteel\Install\Http\Middleware\EnsureTokenIsValid;

Route::middleware(['web'])->group(function () {
	
	Route::get('code', [InstallController::class, 'code'])->name('code');
	Route::post('code/send', [InstallController::class, 'send'])->name('codeSend');
	
	Route::middleware([EnsureTokenIsValid::class])->group(function () {
		Route::get('install', [InstallController::class, 'install'])->name('install');
		Route::post('install/env', [InstallController::class, 'env'])->name('env');
		Route::get('install/github', [InstallController::class, 'github'])->name('github');
		Route::get('install/database', [InstallController::class, 'database'])->name('database');
		Route::post('install/db', [InstallController::class, 'db'])->name('db');
		Route::get('install/settings', [InstallController::class, 'settings'])->name('settings');
		Route::get('install/account', [InstallController::class, 'account'])->name('account');
		Route::get('install/domains', [InstallController::class, 'domains'])->name('domains');
		Route::get('install/languages', [InstallController::class, 'lang'])->name('lang');
		Route::get('install/app', [InstallController::class, 'app'])->name('app');
		Route::get('install/finish', [InstallController::class, 'finish'])->name('finish');
		Route::get('install/finis', [InstallController::class, 'finis'])->name('finis');
	});
	
	Route::get('/install/assets/{file}', function ($file) {
		$path = __DIR__ . "/../resources/assets/{$file}";

		if (! file_exists($path)) {
			abort(404);
		}

		$mime = mime_content_type($path);
		return Response::file($path, ['Content-Type' => $mime]);
	})->where('file', '.*');

});
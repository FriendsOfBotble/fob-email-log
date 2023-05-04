<?php

declare(strict_types=1);

use Datlechin\EmailLog\Http\Controllers\EmailLogController;
use Illuminate\Support\Facades\Route;
use Botble\Base\Facades\BaseHelper;

Route::group([
    'permission' => 'email-logs.index',
    'prefix' => BaseHelper::getAdminPrefix(),
    'middleware' => ['web', 'core', 'auth'],
], function () {
    Route::resource('email-logs', EmailLogController::class)
        ->only('index', 'destroy');

    Route::get('email-logs/{email_log}', [EmailLogController::class, 'show'])
        ->name('email-logs.show');

    Route::group(['permission' => 'email-logs.destroy'], function () {
        Route::delete('email-logs/deletes', [EmailLogController::class, 'deletes'])
            ->name('email-logs.deletes');
    });
});

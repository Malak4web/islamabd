<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware([\App\Http\Middleware\SetLocale::class])->group(function () {
    Route::get('/settings', [\App\Http\Controllers\Api\SettingController::class, 'index']);
    Route::get('/pages/{slug}', [\App\Http\Controllers\Api\PageController::class, 'show']);
    Route::get('/services', [\App\Http\Controllers\Api\ServiceController::class, 'index']);
    Route::get('/services/{id}', [\App\Http\Controllers\Api\ServiceController::class, 'show']);

    Route::get('/projects', [\App\Http\Controllers\Api\ProjectController::class, 'index']);
    Route::get('/projects/{id}', [\App\Http\Controllers\Api\ProjectController::class, 'show']);

    Route::post('/contacts', [\App\Http\Controllers\Api\ContactController::class, 'store'])
         ->middleware('throttle:5,1');

    Route::get('/code-injections', [\App\Http\Controllers\Api\CodeInjectionController::class, 'index']);
});

Route::prefix('admin')->group(function () {
    // Guest admin routes
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    // Authenticated admin routes
    Route::middleware(['auth:sanctum', \App\Http\Middleware\SetLocale::class])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);

        // Settings
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index']);
        Route::put('/settings/{key}', [\App\Http\Controllers\Admin\SettingController::class, 'update']);
        Route::post('/settings/bulk', [\App\Http\Controllers\Admin\SettingController::class, 'bulkUpdate']);
        Route::post('/settings/image/{key}', [\App\Http\Controllers\Admin\SettingController::class, 'uploadImage']);

        // Pages
        Route::get('/pages', [\App\Http\Controllers\Admin\PageController::class, 'index']);
        Route::put('/pages/{id}', [\App\Http\Controllers\Admin\PageController::class, 'update']);

        // Sections
        Route::get('/sections/{pageId}', [\App\Http\Controllers\Admin\SectionController::class, 'index']);
        Route::put('/sections/{id}', [\App\Http\Controllers\Admin\SectionController::class, 'update']);
        Route::patch('/sections/reorder', [\App\Http\Controllers\Admin\SectionController::class, 'reorder']);
        Route::patch('/sections/{id}/toggle', [\App\Http\Controllers\Admin\SectionController::class, 'toggle']);
        // Services
        Route::patch('/services/reorder', [\App\Http\Controllers\Admin\ServiceController::class, 'reorder']);
        Route::apiResource('/services', \App\Http\Controllers\Admin\ServiceController::class);
        Route::patch('/services/{id}/toggle', [\App\Http\Controllers\Admin\ServiceController::class, 'toggle']);
        Route::post('/services/{id}/image', [\App\Http\Controllers\Admin\ServiceController::class, 'uploadImage']);
        Route::post('/services/{id}/icon', [\App\Http\Controllers\Admin\ServiceController::class, 'uploadIcon']);

        // Projects
        Route::patch('/projects/reorder', [\App\Http\Controllers\Admin\ProjectController::class, 'reorder']);
        Route::apiResource('/projects', \App\Http\Controllers\Admin\ProjectController::class);
        Route::patch('/projects/{id}/toggle', [\App\Http\Controllers\Admin\ProjectController::class, 'toggle']);
        Route::patch('/projects/{id}/feature', [\App\Http\Controllers\Admin\ProjectController::class, 'feature']);
        Route::post('/projects/{id}/cover', [\App\Http\Controllers\Admin\ProjectController::class, 'uploadCover']);
        Route::post('/projects/{id}/gallery', [\App\Http\Controllers\Admin\ProjectController::class, 'uploadGallery']);
        Route::delete('/projects/{id}/gallery', [\App\Http\Controllers\Admin\ProjectController::class, 'removeGallery']);

        // Contacts
        Route::delete('/contacts/bulk', [\App\Http\Controllers\Admin\ContactController::class, 'bulkDestroy']);
        Route::get('/contacts', [\App\Http\Controllers\Admin\ContactController::class, 'index']);
        Route::get('/contacts/{id}', [\App\Http\Controllers\Admin\ContactController::class, 'show']);
        Route::patch('/contacts/{id}/read', [\App\Http\Controllers\Admin\ContactController::class, 'markRead']);
        Route::patch('/contacts/{id}/replied', [\App\Http\Controllers\Admin\ContactController::class, 'markReplied']);
        Route::delete('/contacts/{id}', [\App\Http\Controllers\Admin\ContactController::class, 'destroy']);

        // Code Injections
        Route::apiResource('/code-injections', \App\Http\Controllers\Admin\CodeInjectionController::class);
        Route::patch('/code-injections/{id}/toggle', [\App\Http\Controllers\Admin\CodeInjectionController::class, 'toggle']);

        // Dashboard
        Route::get('/dashboard/stats', [\App\Http\Controllers\Admin\DashboardController::class, 'stats']);

        // Media Library
        Route::get('/media', [\App\Http\Controllers\Admin\MediaController::class, 'index']);
        Route::post('/media', [\App\Http\Controllers\Admin\MediaController::class, 'store']);
        Route::delete('/media/{id}', [\App\Http\Controllers\Admin\MediaController::class, 'destroy']);
    });
});







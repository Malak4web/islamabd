<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('app', function ($view) {
            $favicon = \App\Models\Setting::get('favicon');
            if (!$favicon) {
                $favicon = 'https://indesign-co.com/wp-content/uploads/2023/07/cropped-Fav-32x32.png';
            }
            if ($favicon && !str_starts_with($favicon, 'http')) {
                $favicon = asset('storage/' . ltrim($favicon, '/'));
            }
            $view->with('favicon', $favicon);
        });
    }
}

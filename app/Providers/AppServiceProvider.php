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
            $favicon = null;
            try {
                $favicon = \App\Models\Setting::get('favicon');
            } catch (\Exception $e) {
                // Database not migrated or missing
            }
            if (!$favicon || str_contains($favicon, 'indesign')) {
                $favicon = '/images/defaults/about_fallback.jpg';
            }
            if ($favicon && !str_starts_with($favicon, 'http')) {
                $path = ltrim($favicon, '/');
                if (str_starts_with($path, 'images/')) {
                    $favicon = asset($path);
                } elseif (str_starts_with($path, 'storage/')) {
                    $favicon = asset($path);
                } else {
                    $favicon = asset('storage/' . $path);
                }
            }
            $view->with('favicon', $favicon);
        });
    }
}


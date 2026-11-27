<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Throwable;

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
        // On serverless hosting (Vercel), build-time env vars are not exposed to
        // the PHP runtime, so the migration script runs against local SQLite.
        // Run migrations/seeding once at runtime when the production database
        // is still empty. This is a no-op after the first successful request.
        if (app()->environment('production') && ! app()->runningInConsole()) {
            try {
                if (! Schema::hasTable('hero_slides')) {
                    Artisan::call('migrate', ['--force' => true, '--no-interaction' => true]);
                    Artisan::call('db:seed', ['--force' => true, '--no-interaction' => true]);
                }
            } catch (Throwable $exception) {
                report($exception);
            }
        }
    }
}

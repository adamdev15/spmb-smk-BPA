<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use App\Models\Setting;

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
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        try {
            if (Schema::hasTable('settings')) {
                $settingsArray = Setting::where('key', 'like', 'midtrans_%')->pluck('value', 'key')->toArray();
                
                if (isset($settingsArray['midtrans_merchant_id']) && $settingsArray['midtrans_merchant_id'] !== '') {
                    config(['services.midtrans.merchant_id' => $settingsArray['midtrans_merchant_id']]);
                }
                if (isset($settingsArray['midtrans_server_key']) && $settingsArray['midtrans_server_key'] !== '') {
                    config(['services.midtrans.server_key' => $settingsArray['midtrans_server_key']]);
                }
                if (isset($settingsArray['midtrans_client_key']) && $settingsArray['midtrans_client_key'] !== '') {
                    config(['services.midtrans.client_key' => $settingsArray['midtrans_client_key']]);
                }
                if (isset($settingsArray['midtrans_is_production']) && $settingsArray['midtrans_is_production'] !== '') {
                    config(['services.midtrans.is_production' => ($settingsArray['midtrans_is_production'] == '1' || $settingsArray['midtrans_is_production'] == 'true')]);
                }
            }
        } catch (\Throwable $e) {
            // Safe fallback if database isn't migrated yet
        }

        // Share $settings with all Blade views dynamically
        View::composer('*', function ($view) {
            try {
                if (Schema::hasTable('settings')) {
                    $settings = Setting::all()->pluck('value', 'key');
                    $view->with('settings', $settings);
                } else {
                    $view->with('settings', collect());
                }
            } catch (\Throwable $e) {
                $view->with('settings', collect());
            }
        });
    }
}

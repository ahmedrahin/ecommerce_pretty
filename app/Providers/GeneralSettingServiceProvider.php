<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class GeneralSettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('settings')) {
            $settings = DB::table('settings')->first();

            // Site title, logo, favicon set
            if ($settings) {
                config(['app.name' => $settings->site_title ?? config('app.name')]);
                config(['app.logo' => $settings->website_logo ?? null]);
                config(['app.favicon' => $settings->fav_icon ?? null]);
                config(['app.admin-logo' => $settings->logo ?? null]);
                config(['app.footer_logo' => $settings->website_footer_logo ?? null]); 
                config(['app.dark_logo' => $settings->dark_logo ?? null]);
                config(['app.email' => $settings->email ?? null]);
                config(['app.phone' => $settings->number1 ?? null]);
                config(['app.phone2' => $settings->number2 ?? null]);
                config(['app.address' => $settings->address ?? null]);
                config(['app.state' => $settings->state ?? null]);
                config(['app.facebook' => $settings->facebook ?? null]);
                config(['app.instra' => $settings->instagram ?? null]);
                config(['app.whatsapp' => $settings->whatsapp ?? null]);
                config(['app.youtube' => $settings->youtube ?? null]);
            }
            
            config(['app.error', 'tostar']);
        }
    }
}

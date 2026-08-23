<?php

namespace App\Providers;

use App\Core\KTBootstrap;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\ServiceProvider;
use App\Services\ProductFilterService;
use App\Services\ProductDetailService;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Update defaultStringLength
        Builder::defaultStringLength(191);

        // Initialize KTBootstrap
        KTBootstrap::init();

        if (Schema::hasTable('website_settings')) {
            $settings = DB::table('website_settings')->first();

            // Website settings configuration
            if ($settings) {
                // for home page
                config(['website_settings.marquee_text' => $settings->marquee_text ?? '']);


                // for product
                config(['website_settings.product_count_enabled' => $settings->product_count_enabled ?? 1]);
                config(['website_settings.item_per_page' => $settings->item_per_page ?? null ]);
                config(['website_settings.show_review' => $settings->show_review ?? 1]);
                config(['website_settings.allow_guest_reviews' => $settings->allow_guest_review ?? 1]);
                config(['website_settings.show_wishlist' => $settings->show_wishlist ?? 1]);
                config(['website_settings.show_expire_date' => $settings->show_expire_date ?? 1]);
                config(['website_settings.allow_review' => $settings->allow_review ?? 0]);
                config(['website_settings.buy_now_button' => $settings->buy_now_button ?? 1]);

                // for customer
                config(['website_settings.guest_checkout' => $settings->guest_checkout ?? 1]);
                config(['session.lifetime' => $settings->login_session * 60]);
                config(['website_settings.cart_session' => $settings->guest_checkout ?? 1]);
                config(['website_settings.login_attemp' => $settings->login_attemp ?? 1]);

                // product details
                config(['website_settings.show_order_count' => $settings->show_order_count ?? 1]);
                config(['website_settings.show_size_chart' => $settings->show_size_chart ?? 1]);
                config(['website_settings.ask_qustion' => $settings->ask_qustion ?? 1]);
                config(['website_settings.share' => $settings->share ?? 1]);
                config(['website_settings.product_info' => $settings->product_info ?? 1]);
                config(['website_settings.show_expire' => $settings->show_expire ?? 1]);
            }
        }

    }
}

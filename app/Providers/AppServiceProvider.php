<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;

// модели
use App\AdminModels\AdminSetting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('number_format', function ($number) {
            return "<?php echo number_format($number, 0, ',', ' ') ?>";
        });

        // true // false
        if (true) {
            // расшарить настройки сайта
            if(Schema::hasTable('admin_settings')) {
                $settings = AdminSetting::all();
                foreach ($settings as $items) {
                    // расшарить на все вьюшки
                    view()->share($items->key, $items->value);
                }
            } else {
                $settings = [
                    ["setting_title","Электрические сети | 2021 г."],
                    ["setting_paginate_admin","50"],
                    ["setting_timezone","5"],
                    ["setting_map_center","57.342954, 61.347649"],
                    ["setting_map_scale","17"],
                    ["setting_map_scale_one","17"]
                ];
                foreach ($settings as $items) {
                    // расшарить на все вьюшки
                    view()->share($items[0], $items[1]);
                }
            }

        }
    }
}

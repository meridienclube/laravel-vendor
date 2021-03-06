<?php

namespace ConfrariaWeb\Vendor\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class VendorServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../Views', 'vendor');
        $this->loadTranslationsFrom(__DIR__ . '/../Translations', 'vendor');

        Blade::directive('datetime', function ($expression) {
            return "<?php echo ($expression)->format('d/m/Y H:i'); ?>";
        });

        Blade::directive('date', function ($expression) {
            return "<?php echo ($expression)->format('d/m/Y'); ?>";
        });

        Blade::directive('time', function ($expression) {
            return "<?php echo ($expression)->format('H:i'); ?>";
        });

        Blade::component('vendor::components.buttons.form', 'formButtons');
    }

    public function register()
    {

    }

}

<?php

namespace Spatie\Honeypot;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Spatie\Honeypot\SpamResponder\SpamResponder;

class HoneypotServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/honeypot.php' => config_path('honeypot.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/honeypot'),
            ], 'views');
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'honeypot');

        View::composer('honeypot::honeypotFormFields', HoneypotViewComposer::class);

        Blade::directive('honeypot', function () {
            return "<?php echo view('honeypot::honeypotFormFields'); ?>";
        });

        $this->app->bind(SpamResponder::class, config('honeypot.respond_to_spam_with'));
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/honeypot.php', 'honeypot');
    }
}

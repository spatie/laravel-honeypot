<?php

namespace Spatie\Honeypot;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Honeypot\SpamResponder\SpamResponder;
use Spatie\Honeypot\View\HoneypotComponent;
use Spatie\Honeypot\View\HoneypotViewComposer;

class HoneypotServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this
                ->registerPublishables()
                ->registerBladeClasses();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'honeypot');

        $this->app->bind(SpamResponder::class, config('honeypot.respond_to_spam_with'));

        $this->app->bind(Honeypot::class, function () {
            $config = config('honeypot');

            return new Honeypot($config);
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/honeypot.php', 'honeypot');
    }

    protected function registerPublishables(): self
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/honeypot.php' => config_path('honeypot.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/honeypot'),
            ], 'views');
        }

        return $this;
    }

    protected function registerBladeClasses(): self
    {
        View::composer('honeypot::honeypotFormFields', HoneypotViewComposer::class);
        Blade::component('honeypot', HoneypotComponent::class);
        Blade::directive('honeypot', function () {
            return "<?php echo view('honeypot::honeypotFormFields'); ?>";
        });

        return $this;
    }
}

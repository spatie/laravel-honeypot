<?php

namespace Spatie\Honeypot;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Spatie\Honeypot\SpamResponder\SpamResponder;
use Spatie\Honeypot\View\HoneypotComponent;
use Spatie\Honeypot\View\HoneypotViewComposer;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class HoneypotServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-honeypot')
            ->hasConfigFile()
            ->hasViews();
    }

    public function packageBooted()
    {
        $this
            ->registerBindings()
            ->registerBladeClasses();
    }

    protected function registerBindings(): self
    {
        $this->app->bind(SpamResponder::class, config('honeypot.respond_to_spam_with'));

        $this->app->bind(SpamProtection::class, config('honeypot.spam_protection'));

        $this->app->bind(Honeypot::class, fn () => new Honeypot(config('honeypot')));

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

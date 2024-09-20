<?php

namespace Spatie\Honeypot;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\Compilers\BladeCompiler;
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

    protected function registerBladeClasses(): void
    {
        $this->callAfterResolving('view', static function (Factory $view) {
            $view->composer('honeypot::honeypotFormFields', HoneypotViewComposer::class);
        });

        $this->callAfterResolving('blade.compiler', static function (BladeCompiler $blade) {
            $blade->component('honeypot', HoneypotComponent::class);
            $blade->directive('honeypot', static fn () => "<?php echo view('honeypot::honeypotFormFields'); ?>");
        });
    }
}

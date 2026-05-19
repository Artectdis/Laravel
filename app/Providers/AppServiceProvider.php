<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Blade;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

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
        Volt::mount([
        resource_path('views/livewire'),
        resource_path('views/pages'), // Optional, if you use full-page components
        ]);

        Blade::directive('safeHtml', function ($expression) {
            return "<?php 
                \$config = (new \Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig())->allowSafeElements();
                echo (new \Symfony\Component\HtmlSanitizer\HtmlSanitizer(\$config))->sanitize($expression); 
            ?>";
        });
    }

}

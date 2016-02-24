<?php namespace Alfredoem\Ragnarok;

/**
 *
 * @author Alfredo Espiritu <alfredo.espiritu.m@gmail.com>
 */

use Alfredoem\Ragnarok\Listeners\RagnarokListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Alfredoem\Ragnarok\Console\Install;


class RagnarokServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $this->app->booted(function() {
            $this->defineRoutes();
        });

        $this->defineViews();
        $this->defineAssets();
        $this->defineLanguages();
        $this->defineListeners();

    }

    protected function defineRoutes()
    {
        if (! $this->app->routesAreCached()) {
            $router = app('router');

            $router->group(['namespace' => 'Alfredoem\Ragnarok\Http\Controllers'], function($router) {
                require __DIR__ . '/Http/routes.php';
            });
        }
    }

    protected function defineViews()
    {
        $this->loadViewsFrom(RAGNAROK . '/resources/views', 'Ragnarok');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                RAGNAROK . '/resources/views' => base_path('resources/views/vendor/Ragnarok'),
            ]);

            $this->publishes([
                RAGNAROK . '/resources/views' => base_path('resources/views/vendor/Ragnarok'),
            ]);
        }
    }

    public function defineLanguages()
    {
        $this->loadTranslationsFrom(RAGNAROK . '/resources/lang', 'Ragnarok');
    }

    public function defineAssets()
    {
        $this->publishes([
            RAGNAROK.'/public/css' => public_path('css/alfredoem/ragnarok/'),
        ], 'public');
    }

    public function defineListeners()
    {
        Event::subscribe(new RagnarokListener);
    }

    public function register()
    {
        if (! defined('RAGNAROK')) {
            define('RAGNAROK', realpath(__DIR__ . '/../'));
        }

        if ($this->app->runningInConsole()) {
            $this->commands([Install::class]);
        }
    }

}
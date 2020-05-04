<?php

declare(strict_types = 1);

namespace Modules\Product\Providers;

use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Modules\Product\Helpers\PriceFormatter;
use Modules\Product\Repositories\CategoryRepository;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Repositories\SupplyRepository;
use Modules\Product\Services\CategoryService;
use Modules\Product\Services\ImagesManager;
use Modules\Product\Services\ProductService;

/**
 * Class ProductServiceProvider
 * @package Modules\Product\Providers
 */
class ProductServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Product';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'product';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        $this->registerRepositories();
        $this->registerServices();

        $this->bindFacades();
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath,
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (!app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path($this->moduleName, 'Database/factories'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * @return array
     */
    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }

        return $paths;
    }

    /**
     * @return void
     */
    private function bindFacades(): void
    {
        $this->app->bind('price-formatter', function () {
            return new PriceFormatter();
        });
    }

    /**
     * @return void
     */
    private function registerRepositories(): void
    {
        $this->app->singleton(CategoryRepository::class);
        $this->app->singleton(ProductRepository::class);
        $this->app->singleton(SupplyRepository::class);
    }

    /**
     * @return void
     */
    private function registerServices(): void
    {
        $this->app->singleton(CategoryService::class);
        $this->app->singleton(ProductService::class);
    }
}

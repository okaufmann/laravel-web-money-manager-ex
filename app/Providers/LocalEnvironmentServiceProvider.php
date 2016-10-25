<?php
 
namespace App\Providers;
 
use Illuminate\Support\Facades\App;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
 
class LocalEnvironmentServiceProvider extends ServiceProvider
{
    /**
     * Service Providers only should be loaded in development.
     *
     * @var array
     */
    protected $localProviders = [
        \Barryvdh\Debugbar\ServiceProvider::class,
        \Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
    ];
 
    /**
     * Facade aliases only should be loaded in development
     *
     * @var array
     */
    protected $facadeAliases = [
        'Debugbar' => \Barryvdh\Debugbar\Facade::class,
    ];
 
    /**
     * Register Service Providers
     */
    protected function registerServiceProviders()
    {
        foreach ($this->localProviders as $provider) {
            $this->app->register($provider);
        }
    }
 
    /**
     * Register Facade aliases
     * Base file Alias load is /config/app.php => aliases.
     */
    public function registerFacadeAliases()
    {
        $loader = AliasLoader::getInstance();
        foreach ($this->facadeAliases as $alias => $facade) {
            $loader->alias($alias, $facade);
        }
    }
 
    /**
     * Register the specific service prodiders and facade aliases only if in .env file APP_ENV = local.
     *
     * @return void
     */
    public function boot()
    {
        if (App::environment('local')) {
            $this->registerServiceProviders();
            $this->registerFacadeAliases();
        }
    }
 
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
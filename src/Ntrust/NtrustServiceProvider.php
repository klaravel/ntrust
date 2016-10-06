<?php 

namespace Klaravel\Ntrust;

/**
 * This file is part of Ntrust,
 * a role & permission management solution for Laravel.
 */
use Illuminate\Support\ServiceProvider;

class NtrustServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // files to publish
        $this->publishes($this->getPublished());

        // Register blade directives
        $this->bladeDirectives();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerNtrust();
        
        $this->commands($this->commands);
        
        $this->mergeConfig();
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    private function registerNtrust()
    {
        $this->app->bind('ntrust', function ($app) {
            return new Ntrust($app);
        });
        
        $this->app->alias('ntrust', 'Klaravel\Ntrust');
    }

    protected $commands = [
        'Klaravel\Ntrust\Commands\MigrationCommand',
    ];

    /**
     * Register the blade directives
     *
     * @return void
     */
    private function bladeDirectives()
    {
        // Call to Ntrust::hasRole
        \Blade::directive('role', function($expression) {
            return "<?php if (\\Ntrust::hasRole({$expression})) : ?>";
        });
        \Blade::directive('endrole', function($expression) {
            return "<?php endif; // Ntrust::hasRole ?>";
        });
        // Call to Ntrust::can
        \Blade::directive('permission', function($expression) {
            return "<?php if (\\Ntrust::can({$expression})) : ?>";
        });
        \Blade::directive('endpermission', function($expression) {
            return "<?php endif; // Ntrust::can ?>";
        });
        // Call to Ntrust::ability
        \Blade::directive('ability', function($expression) {
            return "<?php if (\\Ntrust::ability({$expression})) : ?>";
        });
        \Blade::directive('endability', function($expression) {
            return "<?php endif; // Ntrust::ability ?>";
        });
    }

    /**
     * Get files to be published
     *
     * @return array
     */
    protected function getPublished()
    {
        return [
            realpath(__DIR__ .
                '/../config/ntrust.php') =>
                (function_exists('config_path') ?
                    config_path('ntrust.php') :
                    base_path('config/ntrust.php')),
        ];
    }

    /**
     * Merges user's and ntrust's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/ntrust.php', 'ntrust'
        );
    }
}
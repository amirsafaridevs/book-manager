<?php
namespace BookManager\App;

use Rabbit\Application;
use Rabbit\Plugin;
use Rabbit\Utils\Singleton;
use Exception;
use BookManager\Providers\BookServiceProvider;
use BookManager\Providers\AdminServiceProvider;
use BookManager\Services\EloquentService;
use Rabbit\Database\DatabaseServiceProvider;
use Rabbit\Templates\TemplatesServiceProvider;
use Rabbit\Templates\Engine;
use Illuminate\Database\Capsule\Manager as DB;
use BookManager\Providers\MigrationsServiceProvider;

/**
 * Main Application Class
 * 
 * Implements Singleton Pattern to ensure only one instance exists.
 * Manages plugin initialization, service providers, and core functionality.
 * 
 * @package BookManager\App
 */
class App extends Singleton 
{
    /**
     * Plugin instance (Container)
     * 
     * @var Plugin
     */
    private $plugin;
    
    /**
     * Base path of the plugin
     * 
     * @var string
     */
    private $basePath;
    
    /**
     * File path of the main plugin file
     * 
     * @var string
     */
    private $filePath;
    
    /**
     * Eloquent service instance
     * 
     * @var EloquentService
     */
    protected EloquentService $eloquent;

    /**
     * Initialize the plugin application
     * 
     * Uses Singleton Pattern - constructor is called only once via Singleton::get()
     */
    public function __construct(){
        // Use plugin root (two levels up from this file) so Rabbit can find the config folder
        $this->prepareFilePath();
        $app = Application::get();
        $this->plugin = $app->loadPlugin( $this->basePath, $this->filePath, 'config' );
        $this->init();
    }

    /**
     * Prepare file paths for the plugin
     * 
     * @return void
     */
    private function prepareFilePath(){
        $this->basePath = dirname(__DIR__, 2);
        $this->filePath = $this->basePath . DIRECTORY_SEPARATOR . 'book-manager.php';
    }

    /**
     * Initialize core plugin functionality
     * 
     * @return void
     */
    private function init(){
        $this->addServiceProvider();
        $this->loadPluginTextDomain();
        $this->registerViewMacro();
    }

    /**
     * Register all service providers
     * 
     * Service providers use Dependency Injection Container to manage dependencies
     * 
     * @return void
     */
    public function addServiceProvider(){
        try {
            $this->plugin->addServiceProvider( DatabaseServiceProvider::class );
            $this->plugin->addServiceProvider( TemplatesServiceProvider::class );
            $this->plugin->addServiceProvider( MigrationsServiceProvider::class );
            $this->plugin->addServiceProvider( BookServiceProvider::class );
            $this->plugin->addServiceProvider( AdminServiceProvider::class );
        } catch (\Exception $e) {
            boman_handle_try_catch_error($e);
        }
    }

    /**
     * Get the plugin container instance
     * 
     * @return Plugin
     */
    public function getApplication()
    {
        return $this->plugin;
    }

    /**
     * Register view macro for template rendering
     * 
     * Note: Engine is a value object that requires dynamic view and data parameters,
     * so using 'new Engine()' in the closure is acceptable here.
     * 
     * @return void
     */
    public function registerViewMacro(){
        $this->plugin->boot(function (Plugin $plugin) {
            $plugin::macro('view', function (string $view, array $data = []) {
                $engine = new Engine($view, $data);
                echo $engine->render();
            });
        });
    }
    
    /**
     * Load plugin text domain for translations
     * 
     * @return void
     */
    public function loadPluginTextDomain()
    {
        add_action('plugins_loaded', function() {
            load_plugin_textdomain(
                'book-manager',
                false,
                dirname(plugin_basename($this->filePath)) . '/languages'
            );
        });
    }
}



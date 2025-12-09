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
use Illuminate\Database\Capsule\Manager as DB;
use BookManager\Providers\MigrationsServiceProvider;
/**
 * Class BookManager
 * @package BookManager
 */
class App extends Singleton 
{
    /**
     * Plugin instance
     * @var Plugin
     */
    private $plugin;
    private $basePath;
    private $filePath;
    protected EloquentService $eloquent;

    /**
     * Initialize the plugin
     */
    public function __construct(){
        // Use plugin root (two levels up from this file) so Rabbit can find the config folder
        $this->prepareFilePath();
        $app = Application::get();
        $this->plugin = $app->loadPlugin( $this->basePath, $this->filePath, 'config' );
        $this->init();

    }

    private function prepareFilePath(){
        $this->basePath = dirname(__DIR__, 2);
        $this->filePath = $this->basePath . DIRECTORY_SEPARATOR . 'book-manager.php';
    }


    private function init(){
       
        $this->addServiceProvider();
        $this->loadPluginTextDomain();
       
     
    }

    
 
    /**
     * Initialize the plugin
     */
    public function addServiceProvider(){
        try {
            $this->plugin->addServiceProvider( DatabaseServiceProvider::class );
            $this->plugin->addServiceProvider( MigrationsServiceProvider::class );
            $this->plugin->addServiceProvider( BookServiceProvider::class );
            $this->plugin->addServiceProvider( AdminServiceProvider::class );
        } catch (Exception $e) {
            boman_handle_try_catch_error($e);
        }
    }

      /**
     * @return Container
     */
    public function getApplication()
    {
        return $this->plugin;
    }

    public function loadPluginTextDomain()
    {
        $this->plugin->boot(
            function( $plugin ) {
                $plugin->loadPluginTextDomain();
            }
        );
    }
}



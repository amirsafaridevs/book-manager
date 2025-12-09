<?php
namespace BookManager\App;

use Rabbit\Application;
use Rabbit\Plugin;
use Rabbit\Utils\Singleton;
use Exception;
use BookManager\Providers\BookServiceProvider;
use BookManager\Providers\AdminServiceProvider;
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
    /**
     * Initialize the plugin
     */
    public function __construct(){
        $app = Application::get();
        // Use plugin root (two levels up from this file) so Rabbit can find the config folder
        $basePath = dirname(__DIR__, 2);
        $filePath = $basePath . DIRECTORY_SEPARATOR . 'book-manager.php';
        $this->plugin = $app->loadPlugin( $basePath, $filePath, 'config' );
        $this->addServiceProvider();
        $this->loadPluginTextDomain();

    }
    /**
     * Initialize the plugin
     */
    public function addServiceProvider(){
        try {
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



<?php
namespace BookManager\App;

use Rabbit\Application;
use BookManager\Providers\BookServiceProvider;
use BookManager\Providers\AdminServiceProvider;
use Rabbit\Utils\Singleton;
use Exception;


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
        $this->plugin = $app->loadPlugin(dirname( __FILE__ ), __FILE__);
        $this->init();

    }
    /**
     * Initialize the plugin
     */
    public function init(){
        try {
            // register available service providers for the plugin
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

    /**
     * Proxy check for a service in the underlying plugin container.
     *
     * @param string $id
     * @return bool
     */
    public function has($id)
    {
        return $this->plugin->has($id);
    }

    /**
     * Proxy retrieval for a service from the underlying plugin container.
     *
     * @param string $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->plugin->get($id);
    }
}



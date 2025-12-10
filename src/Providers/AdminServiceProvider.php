<?php
namespace BookManager\Providers;

use Rabbit\Contracts\BootablePluginProviderInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use BookManager\Services\SaveMetabox;
/**
 * Admin Service Provider
 */
class AdminServiceProvider extends AbstractServiceProvider implements BootablePluginProviderInterface
{
    protected $provides = [];
    public function register( )
    {

        $container = $this->getContainer();

        
    }
    public function boot()
    {

    }
    public function bootPlugin()
    {
        
    }
}
<?php
namespace BookManager\Providers;

use Rabbit\Contracts\BootablePluginProviderInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Admin Service Provider
 */
class AdminServiceProvider extends AbstractServiceProvider implements BootablePluginProviderInterface
{
    protected $provides = [];
    public function register()
    {
        
    }
    public function boot()
    {

    }
    public function bootPlugin()
    {
    }
}
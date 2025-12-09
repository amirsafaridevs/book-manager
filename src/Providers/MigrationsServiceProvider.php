<?php
namespace BookManager\Providers;


use Rabbit\Contracts\BootablePluginProviderInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use BookManager\Services\MigrationsService;

class MigrationsServiceProvider extends AbstractServiceProvider implements BootablePluginProviderInterface, BootableServiceProviderInterface
{
    protected $provides = [MigrationsService::class];
    
    public function register()
    {
        $container = $this->getContainer();
        $container->add(MigrationsService::class);
    }
    
    public function boot()
    {
        // This method is called automatically when the service provider is added
        // because we implement BootableServiceProviderInterface
        $container = $this->getContainer();
        // Ensure the service is registered first
        if (!$container->has(MigrationsService::class)) {
            $this->register();
        }

        // Run migrations
        $container->onActivation(function () use ($container) {
            try {
                $migrationsService = $container->get(MigrationsService::class);
                $migrationsService->setContainer($container)->boot();
            } catch (Exception $e) {
                boman_handle_try_catch_error($e);
            }
        });
    }
    
    public function bootPlugin()
    {
        // This is called by Rabbit framework on plugins_loaded hook
    }
}
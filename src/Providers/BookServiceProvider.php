<?php
namespace BookManager\Providers;

use Rabbit\Contracts\BootablePluginProviderInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use BookManager\Services\BookPostTypeService;
use BookManager\Services\BookTaxonomyService;
use BookManager\Services\BookMetaBoxService;
use BookManager\Models\BookInfo;


/**
 * Book Service Provider
 */
class BookServiceProvider extends AbstractServiceProvider implements BootablePluginProviderInterface
{
    protected $provides = [
        'book.post_type',
        'book.taxonomy',
        'book.meta_box',
    ];
    
    public function register()
    {
        $container = $this->getContainer();

        // Register BookInfo Model as Singleton
        $container->share(BookInfo::class);
        
        // Register Book Post Type Service as Singleton
        $container->share('book.post_type', BookPostTypeService::class);
        
        // Register Book Taxonomy Service as Singleton
        $container->share('book.taxonomy', BookTaxonomyService::class);
        
        // Register Book Meta Box Service with Dependency Injection
        $container->share('book.meta_box', BookMetaBoxService::class)
            ->addArgument(BookInfo::class);
    }
    public function boot()
    {
       
    }
    public function bootPlugin()
    {
        $container = $this->getContainer();
        // Get services once
        $postTypeService = $container->get('book.post_type');
        $taxonomyService = $container->get('book.taxonomy');
        $metaBoxService = $container->get('book.meta_box');
        $metaBoxService->setContainer($container);
        
        // Initialize Book Post Type and Taxonomy
        add_action('init', function() use ($postTypeService, $taxonomyService) {
            $postTypeService->boot();
            $taxonomyService->boot();
        });

        // Initialize Meta Box
        add_action('add_meta_boxes', [$metaBoxService, 'boot']);

        // Save Meta Box
        add_action('save_post_book', [$metaBoxService, 'save']);
    }
}
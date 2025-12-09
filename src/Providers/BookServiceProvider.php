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

        // Register Book Post Type Service
        $container->add('book.post_type', BookPostTypeService::class);
        
        // Register Book Taxonomy Service
        $container->add('book.taxonomy', BookTaxonomyService::class);


        // Register BookInfo Model
        $container->add(BookInfo::class);
        // Register Book Meta Box Service with dependency injection
        $container->add('book.meta_box', BookMetaBoxService::class)
        ->addArgument(BookInfo::class);

    }
    public function boot()
    {
       
    }
    public function bootPlugin()
    {

        $container = $this->getContainer();

        // Initialize Book Post Type
        add_action('init', function() use ($container) {
            $postTypeService = $container->get('book.post_type');
            $postTypeService->boot();
            $taxonomyService = $container->get('book.taxonomy');
            $taxonomyService->boot();
        });

         // Initialize Meta Box
         add_action('add_meta_boxes', function() use ($container) {
            $metaBoxService = $container->get('book.meta_box');
            $metaBoxService->setContainer($container);
            $metaBoxService->boot();
        });

    }
}
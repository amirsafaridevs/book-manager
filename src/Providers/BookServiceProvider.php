<?php
namespace BookManager\Providers;

use Rabbit\Contracts\BootablePluginProviderInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use BookManager\Services\BookPostTypeService;
use BookManager\Services\BookTaxonomyService;



/**
 * Book Service Provider
 */
class BookServiceProvider extends AbstractServiceProvider implements BootablePluginProviderInterface
{
    protected $provides = [
        'book.post_type',
        'book.taxonomy'
    ];
    
    public function register()
    {

         $container = $this->getContainer();

        // Register Book Post Type Service
        $container->add('book.post_type', BookPostTypeService::class);
        
        // Register Book Taxonomy Service
        $container->add('book.taxonomy', BookTaxonomyService::class);


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
            $postTypeService->register();
        });

         // Initialize Taxonomies
         add_action('init', function() use ($container) {
            $taxonomyService = $container->get('book.taxonomy');
            $taxonomyService->register();
        });


    }
}
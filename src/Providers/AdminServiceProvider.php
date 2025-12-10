<?php
namespace BookManager\Providers;

use Rabbit\Contracts\BootablePluginProviderInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use BookManager\Services\BookInfoPage;
use BookManager\Models\BookInfo;
/**
 * Admin Service Provider
 */
class AdminServiceProvider extends AbstractServiceProvider implements BootablePluginProviderInterface
{
    protected $provides = ['Admin.bookInfoPage'];
    public function register( )
    {

        $container = $this->getContainer();

        // Register BookInfo Model
        $container->share(BookInfo::class);
        $container->share('Admin.bookInfoPage', BookInfoPage::class)->addArgument(BookInfo::class);
    }
    public function boot()
    {

    }
    public function bootPlugin()
    {
        $container = $this->getContainer();
        $bookInfoPage = $container->get('Admin.bookInfoPage');
        add_action('admin_menu', [$bookInfoPage, 'addMenuPage']);
    }
}
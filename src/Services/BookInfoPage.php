<?php
namespace BookManager\Services;

use BookManager\Services\AbstractService;
use BookManager\Models\BookInfo;
use BookManager\Helpers\Table;

class BookInfoPage extends AbstractService 
{
    protected $bookInfo;

    public function __construct(BookInfo $bookInfo)
    {
        $this->bookInfo = $bookInfo;
    }

    public function boot()
    {
       
    }

    public function addMenuPage()
    {
        add_menu_page('Book Info', 'Book Info', 'manage_options', 'book-info', [$this, 'renderPage']);
    }
    
    public function renderPage()
    {
            $books = $this->bookInfo->getAll();
            
            // Define column labels (key => label)
            $columns = [
                'id' => __('ID', 'book-manager'),
                'post_id' => __('Post ID', 'book-manager'),
                'isbn' => __('ISBN', 'book-manager'),
            ];
            
            // Render view
            $this->getContainer()->view('bookinfo-page', ['books' => $books, 'columns' => $columns]);
            
    }
}
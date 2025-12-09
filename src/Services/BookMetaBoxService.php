<?php

namespace BookManager\Services;

use BookManager\Models\BookInfo;
use Rabbit\Contracts\FormRendererInterface;
use BookManager\Services\AbstractService;

/**
 * Service for Book ISBN Meta Box
 */
class BookMetaBoxService extends AbstractService implements FormRendererInterface
{
    /**
     * @var BookInfo
     */
    protected $bookInfo;

    /**
     * @var \WP_Post|null
     */
    protected $post;

    /**
     * Constructor with dependency injection
     *
     * @param BookInfo $bookInfo
     */
    public function __construct(BookInfo $bookInfo)
    {
        $this->bookInfo = $bookInfo;
    }

    /**
     * Register the meta box
     *
     * @return void
     */
    public function boot()
    {
        add_meta_box(
            'book_isbn_meta_box',
            __('Book ISBN', 'book-manager'),
            [$this, 'renderMetaBox'],
            'book',
            'normal',
            'high'
        );
    }

    /**
     * Render the meta box content (WordPress callback)
     *
     * @param \WP_Post $post
     * @return void
     */
    public function renderMetaBox($post)
    {
        $this->post = $post;
        echo $this->render();
    }

    /**
     * Render the form through a custom layout.
     * Implements FormRendererInterface
     *
     * @return string
     */
    public function render()
    {
        if (!$this->post || $this->post->post_type !== 'book') {
            return '';
        }

        // Get current ISBN value
        $isbn = $this->bookInfo->getIsbnByPostId($this->post->ID);

        
        return $this->getContainer()->view('book-meta-box', ['isbn' => $isbn]) ?: '';
    }
}


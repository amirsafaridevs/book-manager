<?php

namespace BookManager\Services;

use BookManager\Models\BookInfo;
use Rabbit\Contracts\FormRendererInterface;
use BookManager\Services\AbstractService;
use Rabbit\Utils\RequestFactory;
use Rabbit\Utils\Sanitizer;
use Rabbit\Nonces\NonceFactory;
use Rabbit\Exceptions\InvalidNonceException;

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
        $this->render();
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


    public function save($postId)
    {
        try {
            $this->post = get_post($postId);
            if (! $this->preconditionsPass($postId)) {
                return;
            }

            $isbn = $this->extractIsbn();

            $this->persistIsbn($isbn);

        } catch (\Exception $e) {
            boman_handle_try_catch_error($e);
            return;
        }
    }



    protected function preconditionsPass($postId): bool
    {
       
        
        if ($this->isAutosave()) {
            return false;
        }

        if (! $this->canEditPost()) {
            return false;
        }

        return $this->validateNonceOrFail();
    }



    protected function isAutosave(): bool
    {
        return defined('DOING_AUTOSAVE') && DOING_AUTOSAVE;
    }

    protected function canEditPost(): bool
    {
        return $this->post && current_user_can('edit_post', $this->post->ID);
    }

    public function validateNonceOrFail()
    {
        $slug =  'book_isbn_meta_box';
        return NonceFactory::verify($slug);
    }

    protected function extractIsbn(): string
    {
        $request = RequestFactory::getPostedData();
        $isbn = $request->get('book_isbn');
        return $this->sanitize($isbn);
    }

    protected function persistIsbn(string $isbn): void
    {
        $this->bookInfo->saveIsbn($this->post->ID, $isbn);
    }

    public function sanitize($value)
    {
        return Sanitizer::clean($value);
    }
}


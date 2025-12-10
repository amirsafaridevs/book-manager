<?php
namespace BookManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class BookInfo extends Model {

    protected $table = 'books_info';
    public $timestamps = false;
    protected $fillable = ['post_id', 'isbn'];

    /**
     * One-to-one relationship with Book
     * Each BookInfo belongs to a book post
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'post_id', 'ID')->where('post_type', 'book');
    }

    /**
     * Get ISBN by post ID
     */
    public function getIsbnByPostId($postId)
    {
        return $this->where('post_id', $postId)->first()->isbn ?? null;
    }

    /**
     * Save or update ISBN for a post
     */
    public function saveIsbn($post_id, $isbn)
    {
        return $this->updateOrCreate([
            'post_id' => $post_id,
        ], [
            'isbn' => $isbn
        ]);
    }

    /**
     * Get all book info records with related book data
     */
    public function getAll()
    {
        return $this->with('book')->get();
    }
}

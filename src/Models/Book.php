<?php
namespace BookManager\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model {

    protected $table = 'posts';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    /**
     * Automatically limit queries to posts with type 'book'
     */
    protected static function booted()
    {
        static::addGlobalScope('book_type', function ($query) {
            $query->where('post_type', 'book');
        });
    }

    /**
     * Scope to get only books
     */
    public function scopeBooks($query)
    {
        return $query->where('post_type', 'book');
    }

    /**
     * One-to-one relationship with BookInfo
     * Each book can have one BookInfo record
     */
    public function bookInfo()
    {
        return $this->hasOne(BookInfo::class, 'post_id', 'ID');
    }

    /**
     * Get the ISBN attribute for this book
     */
    public function getIsbnAttribute()
    {
        return $this->bookInfo?->isbn;
    }
}


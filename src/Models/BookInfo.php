<?php
namespace BookManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;


class BookInfo extends Model {
    protected $table = 'books_info';
    public $timestamps = false;
    protected $fillable = ['post_id', 'isbn'];

    public function getIsbnByPostId($postId){
        return $this->where('post_id', $postId)->first()->isbn ?? null;
    }

    public function saveIsbn($post_id, $isbn)
    {
        return $this->updateOrCreate([
            'post_id' => $post_id,
        ], [
            'isbn' => $isbn
        ]);
    }

    public function getAll()
    {
        return $this->all();
    }
}
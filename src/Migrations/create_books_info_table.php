<?php
namespace BookManager\Migrations;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class create_books_info_table extends Migration {

    public $tableName = 'books_info';
    public function up() {
        DB::schema()->create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->string('post_id');
            $table->string('isbn');
        });
    }
    public function down() {
        Schema::dropIfExists($this->tableName);
    }
}
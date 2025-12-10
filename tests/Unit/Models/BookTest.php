<?php
/**
 * Unit tests for Book Model
 * 
 * This file contains tests for the Book model class
 */

namespace BookManager\Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use BookManager\Models\Book;

/**
 * Test class for Book Model
 */
class BookTest extends TestCase
{
    /**
     * Test 1: Check that Book model can be instantiated
     */
    public function test_book_model_can_be_instantiated()
    {
        $book = new Book();
        
        $this->assertInstanceOf(Book::class, $book);
    }

    /**
     * Test 2: Check that table name is correctly set to 'posts'
     */
    public function test_book_has_correct_table_name()
    {
        $book = new Book();
        
        $this->assertEquals('posts', $book->getTable());
    }

    /**
     * Test 3: Check that primary key is correctly set to 'ID'
     */
    public function test_book_has_correct_primary_key()
    {
        $book = new Book();
        
        $this->assertEquals('ID', $book->getKeyName());
    }

    /**
     * Test 4: Check that timestamps are disabled
     */
    public function test_book_timestamps_are_disabled()
    {
        $book = new Book();
        
        $this->assertFalse($book->timestamps);
    }

    /**
     * Test 5: Check that bookInfo relationship method exists
     */
    public function test_book_has_book_info_relationship()
    {
        $book = new Book();
        
        // Check that the relationship method exists
        $this->assertTrue(method_exists($book, 'bookInfo'));
    }

    /**
     * Test 6: Check that scopeBooks method exists
     */
    public function test_book_has_scope_books_method()
    {
        $book = new Book();
        
        // Check that the scope method exists
        $this->assertTrue(method_exists($book, 'scopeBooks'));
    }

    /**
     * Test 7: Check that getIsbnAttribute method exists
     */
    public function test_book_has_get_isbn_attribute_method()
    {
        $book = new Book();
        
        // Check that the accessor method exists
        $this->assertTrue(method_exists($book, 'getIsbnAttribute'));
    }
}


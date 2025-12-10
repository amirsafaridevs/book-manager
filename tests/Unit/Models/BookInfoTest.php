<?php
/**
 * Unit tests for BookInfo class
 * 
 * This file contains tests that check the BookInfo class
 * Any function that starts with test_ is considered a test
 */

namespace BookManager\Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use BookManager\Models\BookInfo;

/**
 * Test class for BookInfo
 * 
 * This class extends TestCase which is the base class of PHPUnit
 * All test methods must be public and start with test
 */
class BookInfoTest extends TestCase
{
    /**
     * Test 1: Check that table name is correctly set
     * 
     * This test verifies that the books_info table is correctly configured
     */
    public function test_book_info_has_correct_table_name()
    {
        // Create an instance of BookInfo class
        $bookInfo = new BookInfo();
        
        // Check that the table name is books_info
        // assertEquals means "should be equal to"
        $this->assertEquals('books_info', $bookInfo->getTable());
    }

    /**
     * Test 2: Check that fillable fields are correct
     * 
     * fillable means fields that we can directly assign values to
     * This test verifies that only post_id and isbn are fillable
     */
    public function test_book_info_has_correct_fillable_fields()
    {
        // Create an instance of BookInfo class
        $bookInfo = new BookInfo();
        
        // Expected fields
        $expected = ['post_id', 'isbn'];
        
        // Check that fillable fields are correct
        $this->assertEquals($expected, $bookInfo->getFillable());
    }

    /**
     * Test 3: Check that timestamps are disabled
     * 
     * timestamps means created_at and updated_at
     * In this model, we don't want these fields
     */
    public function test_book_info_timestamps_are_disabled()
    {
        // Create an instance of BookInfo class
        $bookInfo = new BookInfo();
        
        // Check that timestamps are disabled
        // assertFalse means "should be false"
        $this->assertFalse($bookInfo->timestamps);
    }

    /**
     * Test 4: Check that BookInfo class can be instantiated
     * 
     * This is a very simple test that just checks the class works correctly
     */
    public function test_book_info_can_be_instantiated()
    {
        // Create an instance of BookInfo class
        $bookInfo = new BookInfo();
        
        // Check that the created instance is of type BookInfo
        // assertInstanceOf means "should be an instance of this class"
        $this->assertInstanceOf(BookInfo::class, $bookInfo);
    }
}

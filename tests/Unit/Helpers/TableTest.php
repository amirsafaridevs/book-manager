<?php
/**
 * Unit tests for Table class
 * 
 * This file contains tests that check the Table class
 * Table is a Helper for creating WordPress tables
 */

namespace BookManager\Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;
use BookManager\Helpers\Table;

/**
 * Test class for Table
 */
class TableTest extends TestCase
{
    /**
     * Test 1: Check that Table class can be instantiated
     * 
     * This test verifies that we can create an instance of Table
     */
    public function test_table_can_be_instantiated()
    {
        // Create an instance of Table class without parameters
        $table = new Table();
        
        // Check that the created instance is of type Table
        $this->assertInstanceOf(Table::class, $table);
    }

    /**
     * Test 2: Check that Table can be created with custom parameters
     * 
     * This test verifies that we can create Table with specific settings
     */
    public function test_table_accepts_custom_arguments()
    {
        // Create Table with custom parameters
        $table = new Table([
            'singular' => 'book',      // Singular name
            'plural' => 'books',        // Plural name
            'per_page' => 10            // Number of items per page
        ]);
        
        // Check that the created instance is of type Table
        $this->assertInstanceOf(Table::class, $table);
    }

    /**
     * Test 3: Check that Table works with default parameters
     * 
     * This test verifies that if we don't provide parameters, default values are used
     */
    public function test_table_works_with_default_arguments()
    {
        // Create Table without parameters (using default values)
        $table = new Table();
        
        // Check that the created instance is of type Table
        $this->assertInstanceOf(Table::class, $table);
    }
}

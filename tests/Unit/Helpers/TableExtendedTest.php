<?php
/**
 * Extended Unit tests for Table Helper
 * 
 * This file contains additional tests for Table class methods
 * that are not covered in TableTest.php
 */

namespace BookManager\Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;
use BookManager\Helpers\Table;

/**
 * Extended Test class for Table Helper
 */
class TableExtendedTest extends TestCase
{
    /**
     * Test 1: Check that setData method works with array
     */
    public function test_set_data_works_with_array()
    {
        $table = new Table();
        $data = [
            ['id' => 1, 'name' => 'Book 1'],
            ['id' => 2, 'name' => 'Book 2'],
        ];
        
        $result = $table->setData($data);
        
        $this->assertInstanceOf(Table::class, $result);
    }

    /**
     * Test 2: Check that setData method works with empty array
     */
    public function test_set_data_works_with_empty_array()
    {
        $table = new Table();
        $data = [];
        
        $result = $table->setData($data);
        
        $this->assertInstanceOf(Table::class, $result);
    }

    /**
     * Test 3: Check that setColumns method works with simple format
     */
    public function test_set_columns_works_with_simple_format()
    {
        $table = new Table();
        $columns = [
            'id' => 'ID',
            'name' => 'Name',
            'isbn' => 'ISBN',
        ];
        
        $result = $table->setColumns($columns);
        
        $this->assertInstanceOf(Table::class, $result);
    }

    /**
     * Test 4: Check that setColumns method works with advanced format
     */
    public function test_set_columns_works_with_advanced_format()
    {
        $table = new Table();
        $columns = [
            'id' => ['label' => 'ID', 'sortable' => true],
            'name' => ['label' => 'Name', 'sortable' => false, 'callback' => function($item) {
                return strtoupper($item['name']);
            }],
        ];
        
        $result = $table->setColumns($columns);
        
        $this->assertInstanceOf(Table::class, $result);
    }

    /**
     * Test 5: Check that get_columns returns correct columns
     */
    public function test_get_columns_returns_correct_columns()
    {
        $table = new Table();
        $columns = [
            'id' => 'ID',
            'name' => 'Name',
        ];
        $table->setColumns($columns);
        
        $result = $table->get_columns();
        
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertEquals('ID', $result['id']);
        $this->assertEquals('Name', $result['name']);
    }

    /**
     * Test 6: Check that get_columns includes checkbox when enabled
     */
    public function test_get_columns_includes_checkbox_when_enabled()
    {
        $table = new Table(['show_checkbox' => true]);
        $columns = [
            'id' => 'ID',
            'name' => 'Name',
        ];
        $table->setColumns($columns);
        
        $result = $table->get_columns();
        
        $this->assertArrayHasKey('cb', $result);
    }

    /**
     * Test 7: Check that get_sortable_columns returns sortable columns
     */
    public function test_get_sortable_columns_returns_sortable_columns()
    {
        $table = new Table();
        $columns = [
            'id' => 'ID',
            'name' => 'Name',
        ];
        $table->setColumns($columns);
        
        $result = $table->get_sortable_columns();
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
    }

    /**
     * Test 8: Check that column_default returns value for existing column
     */
    public function test_column_default_returns_value_for_existing_column()
    {
        $table = new Table();
        $columns = [
            'name' => 'Name',
        ];
        $table->setColumns($columns);
        
        $item = ['name' => 'Test Book'];
        $result = $table->column_default($item, 'name');
        
        $this->assertStringContainsString('Test Book', $result);
    }

    /**
     * Test 9: Check that column_default returns dash for non-existing column
     */
    public function test_column_default_returns_dash_for_non_existing_column()
    {
        $table = new Table();
        $columns = [
            'name' => 'Name',
        ];
        $table->setColumns($columns);
        
        $item = ['name' => 'Test Book'];
        $result = $table->column_default($item, 'nonexistent');
        
        $this->assertEquals('—', $result);
    }

    /**
     * Test 10: Check that create static method works
     */
    public function test_create_static_method_works()
    {
        $data = [
            ['id' => 1, 'name' => 'Book 1'],
        ];
        $columns = [
            'id' => 'ID',
            'name' => 'Name',
        ];
        
        $table = Table::create($data, $columns);
        
        $this->assertInstanceOf(Table::class, $table);
    }

    /**
     * Test 11: Check that prepare_items works with data
     */
    public function test_prepare_items_works_with_data()
    {
        $table = new Table();
        $data = [
            ['id' => 1, 'name' => 'Book 1'],
            ['id' => 2, 'name' => 'Book 2'],
            ['id' => 3, 'name' => 'Book 3'],
        ];
        $columns = [
            'id' => 'ID',
            'name' => 'Name',
        ];
        
        $table->setData($data);
        $table->setColumns($columns);
        
        // Set paged parameter for get_pagenum
        $_GET['paged'] = 1;
        
        $table->prepare_items();
        
        // Check that items are prepared
        $this->assertNotEmpty($table->items);
        
        // Clean up
        unset($_GET['paged']);
    }

    /**
     * Test 12: Check that filter_data works with search term
     */
    public function test_filter_data_filters_by_search_term()
    {
        $table = new Table();
        $data = [
            ['id' => 1, 'name' => 'Book One'],
            ['id' => 2, 'name' => 'Book Two'],
            ['id' => 3, 'name' => 'Novel Three'],
        ];
        $columns = [
            'id' => 'ID',
            'name' => 'Name',
        ];
        
        $table->setData($data);
        $table->setColumns($columns);
        
        // Set search term
        $_GET['s'] = 'One';
        
        $reflection = new \ReflectionClass($table);
        $method = $reflection->getMethod('filter_data');
        $method->setAccessible(true);
        $result = $method->invoke($table);
        
        // Should filter to only items containing 'One'
        $this->assertCount(1, $result);
        
        // Clean up
        unset($_GET['s']);
    }
}


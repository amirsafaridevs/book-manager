<?php
/**
 * Unit tests for BookPostTypeService
 * 
 * This file contains tests for the BookPostTypeService class
 * which registers the Book custom post type
 */

namespace BookManager\Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use BookManager\Services\BookPostTypeService;

/**
 * Test class for BookPostTypeService
 */
class BookPostTypeServiceTest extends TestCase
{
    /**
     * Test 1: Check that BookPostTypeService can be instantiated
     */
    public function test_book_post_type_service_can_be_instantiated()
    {
        $service = new BookPostTypeService();
        
        $this->assertInstanceOf(BookPostTypeService::class, $service);
    }

    /**
     * Test 2: Check that boot method exists
     */
    public function test_book_post_type_service_has_boot_method()
    {
        $service = new BookPostTypeService();
        
        $this->assertTrue(method_exists($service, 'boot'));
    }

    /**
     * Test 3: Check that boot method calls register_post_type
     * 
     * Note: This test verifies that boot() can be called without errors
     * In a real WordPress environment, it would register the post type
     */
    public function test_boot_method_can_be_called()
    {
        $service = new BookPostTypeService();
        
        // In test environment, register_post_type is mocked in Bootstrap.php
        // So we can call boot() without errors
        $service->boot();
        
        $this->assertTrue(true); // If we get here, no exception was thrown
    }
}


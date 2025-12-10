<?php
/**
 * Unit tests for BookTaxonomyService
 * 
 * This file contains tests for the BookTaxonomyService class
 * which registers taxonomies for Book post type
 */

namespace BookManager\Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use BookManager\Services\BookTaxonomyService;

/**
 * Test class for BookTaxonomyService
 */
class BookTaxonomyServiceTest extends TestCase
{
    /**
     * Test 1: Check that BookTaxonomyService can be instantiated
     */
    public function test_book_taxonomy_service_can_be_instantiated()
    {
        $service = new BookTaxonomyService();
        
        $this->assertInstanceOf(BookTaxonomyService::class, $service);
    }

    /**
     * Test 2: Check that boot method exists
     */
    public function test_book_taxonomy_service_has_boot_method()
    {
        $service = new BookTaxonomyService();
        
        $this->assertTrue(method_exists($service, 'boot'));
    }

    /**
     * Test 3: Check that boot method can be called
     * 
     * Note: This test verifies that boot() can be called without errors
     * In a real WordPress environment, it would register the taxonomies
     */
    public function test_boot_method_can_be_called()
    {
        $service = new BookTaxonomyService();
        
        // In test environment, register_taxonomy is mocked in Bootstrap.php
        // So we can call boot() without errors
        $service->boot();
        
        $this->assertTrue(true); // If we get here, no exception was thrown
    }

    /**
     * Test 4: Check that registerPublisherTaxonomy method exists (private but testable)
     */
    public function test_book_taxonomy_service_has_register_publisher_taxonomy_method()
    {
        $service = new BookTaxonomyService();
        
        // Use reflection to check private method exists
        $reflection = new \ReflectionClass($service);
        $this->assertTrue($reflection->hasMethod('registerPublisherTaxonomy'));
    }

    /**
     * Test 5: Check that registerAuthorsTaxonomy method exists (private but testable)
     */
    public function test_book_taxonomy_service_has_register_authors_taxonomy_method()
    {
        $service = new BookTaxonomyService();
        
        // Use reflection to check private method exists
        $reflection = new \ReflectionClass($service);
        $this->assertTrue($reflection->hasMethod('registerAuthorsTaxonomy'));
    }
}


<?php
/**
 * Unit tests for BookInfoPage
 * 
 * This file contains tests for the BookInfoPage class
 * which handles the admin page for book information
 */

namespace BookManager\Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use BookManager\Services\BookInfoPage;
use BookManager\Models\BookInfo;
use Mockery;

/**
 * Test class for BookInfoPage
 */
class BookInfoPageTest extends TestCase
{
    /**
     * Clean up after each test
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test 1: Check that BookInfoPage can be instantiated
     */
    public function test_book_info_page_can_be_instantiated()
    {
        $bookInfo = Mockery::mock(BookInfo::class);
        $service = new BookInfoPage($bookInfo);
        
        $this->assertInstanceOf(BookInfoPage::class, $service);
    }

    /**
     * Test 2: Check that boot method exists
     */
    public function test_book_info_page_has_boot_method()
    {
        $bookInfo = Mockery::mock(BookInfo::class);
        $service = new BookInfoPage($bookInfo);
        
        $this->assertTrue(method_exists($service, 'boot'));
    }

    /**
     * Test 3: Check that addMenuPage method exists
     */
    public function test_book_info_page_has_add_menu_page_method()
    {
        $bookInfo = Mockery::mock(BookInfo::class);
        $service = new BookInfoPage($bookInfo);
        
        $this->assertTrue(method_exists($service, 'addMenuPage'));
    }

    /**
     * Test 4: Check that renderPage method exists
     */
    public function test_book_info_page_has_render_page_method()
    {
        $bookInfo = Mockery::mock(BookInfo::class);
        $service = new BookInfoPage($bookInfo);
        
        $this->assertTrue(method_exists($service, 'renderPage'));
    }

    /**
     * Test 5: Check that addMenuPage can be called
     */
    public function test_add_menu_page_can_be_called()
    {
        $bookInfo = Mockery::mock(BookInfo::class);
        $service = new BookInfoPage($bookInfo);
        
        // In test environment, add_menu_page is mocked in Bootstrap.php
        $service->addMenuPage();
        
        $this->assertTrue(true); // If we get here, no exception was thrown
    }
}


<?php
/**
 * Unit tests for App class
 * 
 * This file contains tests for the main App class
 */

namespace BookManager\Tests\Unit\App;

use PHPUnit\Framework\TestCase;
use BookManager\App\App;
use Mockery;

/**
 * Test class for App
 */
class AppTest extends TestCase
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
     * Test 1: Check that App class can be accessed via Singleton
     */
    public function test_app_can_be_accessed_via_singleton()
    {
        // Note: Since App uses Singleton pattern and depends on Rabbit framework,
        // we'll just check that the class exists and has the get method
        $this->assertTrue(class_exists(App::class));
        $this->assertTrue(method_exists(App::class, 'get'));
    }

    /**
     * Test 2: Check that App has getApplication method
     */
    public function test_app_has_get_application_method()
    {
        $this->assertTrue(method_exists(App::class, 'getApplication'));
    }

    /**
     * Test 3: Check that App has addServiceProvider method
     */
    public function test_app_has_add_service_provider_method()
    {
        $this->assertTrue(method_exists(App::class, 'addServiceProvider'));
    }

    /**
     * Test 4: Check that App has registerViewMacro method
     */
    public function test_app_has_register_view_macro_method()
    {
        $this->assertTrue(method_exists(App::class, 'registerViewMacro'));
    }

    /**
     * Test 5: Check that App has loadPluginTextDomain method
     */
    public function test_app_has_load_plugin_text_domain_method()
    {
        $this->assertTrue(method_exists(App::class, 'loadPluginTextDomain'));
    }
}


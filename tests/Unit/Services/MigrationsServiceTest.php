<?php
/**
 * Unit tests for MigrationsService
 * 
 * This file contains tests for the MigrationsService class
 * which handles database migrations
 */

namespace BookManager\Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use BookManager\Services\MigrationsService;
use Mockery;

/**
 * Test class for MigrationsService
 */
class MigrationsServiceTest extends TestCase
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
     * Test 1: Check that MigrationsService can be instantiated
     */
    public function test_migrations_service_can_be_instantiated()
    {
        $service = new MigrationsService();
        
        $this->assertInstanceOf(MigrationsService::class, $service);
    }

    /**
     * Test 2: Check that boot method exists
     */
    public function test_migrations_service_has_boot_method()
    {
        $service = new MigrationsService();
        
        $this->assertTrue(method_exists($service, 'boot'));
    }

    /**
     * Test 3: Check that runMigrations method exists
     */
    public function test_migrations_service_has_run_migrations_method()
    {
        $service = new MigrationsService();
        
        $this->assertTrue(method_exists($service, 'runMigrations'));
    }

    /**
     * Test 4: Check that getClassNameFromFile method exists (private but testable via reflection)
     */
    public function test_migrations_service_has_get_class_name_from_file_method()
    {
        $service = new MigrationsService();
        
        // Use reflection to check private method exists
        $reflection = new \ReflectionClass($service);
        $this->assertTrue($reflection->hasMethod('getClassNameFromFile'));
    }

    /**
     * Test 5: Check that validateMigration method exists
     */
    public function test_migrations_service_has_validate_migration_method()
    {
        $service = new MigrationsService();
        
        // Use reflection to check private method exists
        $reflection = new \ReflectionClass($service);
        $this->assertTrue($reflection->hasMethod('validateMigration'));
    }

    /**
     * Test 6: Check that checkMigrationTableExists method exists
     */
    public function test_migrations_service_has_check_migration_table_exists_method()
    {
        $service = new MigrationsService();
        
        // Use reflection to check private method exists
        $reflection = new \ReflectionClass($service);
        $this->assertTrue($reflection->hasMethod('checkMigrationTableExists'));
    }

    /**
     * Test 7: Test getClassNameFromFile extracts class name correctly
     */
    public function test_get_class_name_from_file_extracts_class_name()
    {
        $service = new MigrationsService();
        
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('getClassNameFromFile');
        $method->setAccessible(true);
        
        // Mock file path
        $filePath = '/path/to/create_books_info_table.php';
        $result = $method->invoke($service, $filePath);
        
        // Should return namespace + class name
        $this->assertStringContainsString('BookManager\\Migrations\\', $result);
        $this->assertStringContainsString('create_books_info_table', $result);
    }
}


<?php
namespace BookManager\Services;

use Illuminate\Database\Capsule\Manager as DB;
use ReflectionClass;
use BookManager\Services\AbstractService;

class MigrationsService extends AbstractService
{

    private $migrationsPath;
    private $migrationsNameSpace = 'BookManager\\Migrations\\';
    public function boot()
    {
        $this->migrationsPath = $this->getContainer()->basePath('src/Migrations');
        $this->runMigrations();
    }
    

    
    public function runMigrations()    
    {
        $migrationFiles = $this->getMigrations();
        
        foreach ($migrationFiles as $file) {
           $this->runMigration($file);
        }
    }
    
   
    private function getMigrations() : array
    {
        return glob($this->migrationsPath . DIRECTORY_SEPARATOR . '*.php');
    }
    

    private function runMigration($file)
    {
        try {
            $fullClassName = $this->getClassNameFromFile($file);         
                
            if (!class_exists($fullClassName, true)) {
                return false;
            }
            $migration = new $fullClassName();
            if (!$this->validateMigration($migration)) {
                return false;
            }
            $migration->up();
            return true;
        }catch (Exception $e) {
            boman_handle_try_catch_error($e);
            return false;
        }
    }
    /**
     * Extract class name from migration file
     */
    private function getClassNameFromFile($file)
    {
        return $this->migrationsNameSpace . basename($file, '.php');
    }
    
    private function  checkMigrationTableExists($tableName)
    {
        return DB::schema()->hasTable($tableName);
    }

    private function validateMigration($migration)
    {
        if (!$migration || !method_exists($migration, 'up')) {
            return false;
        }
        $tableName = $migration->tableName  ;
        if (!$tableName) {
            return false;
        }
        if ($this->checkMigrationTableExists($tableName)) {
            return false;
        }
        return true;
    }
   
}
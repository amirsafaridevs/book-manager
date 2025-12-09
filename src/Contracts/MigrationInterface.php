<?php
namespace BookManager\Contracts;

interface MigrationInterface
{
    
    public function up();
    public function down();
}
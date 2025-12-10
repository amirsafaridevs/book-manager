<?php
/**
 * Book Information Page Template
 * 
 * Available variables:
 * @var array|object $books - Book data from database
 * @var array $columns - Column definitions
 */

use BookManager\Helpers\Table;
?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo esc_html__('Book Information', 'book-manager'); ?></h1>
    <hr class="wp-header-end">
    
    <?php
    // Display table with automatic search and pagination
    Table::show($books, $columns, [
        'singular' => __('Book', 'book-manager'),
        'plural' => __('Books', 'book-manager'),
        'per_page' => 1,
        'search_label' => __('Search Books', 'book-manager'),
    ]);
    ?>
</div>
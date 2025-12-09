<?php

namespace BookManager\Services;
use BookManager\Services\AbstractService;

/**
 * Service for registering Book Taxonomies
 */
class BookTaxonomyService extends AbstractService
{
    /**
     * Register taxonomies for Book post type
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublisherTaxonomy();
        $this->registerAuthorsTaxonomy();
    }

    /**
     * Register Publisher taxonomy
     *
     * @return void
     */
    private function registerPublisherTaxonomy()
    {
        $labels = [
            'name'              => _x('Publishers', 'taxonomy general name', 'book-manager'),
            'singular_name'     => _x('Publisher', 'taxonomy singular name', 'book-manager'),
            'search_items'      => __('Search Publishers', 'book-manager'),
            'all_items'         => __('All Publishers', 'book-manager'),
            'parent_item'       => __('Parent Publisher', 'book-manager'),
            'parent_item_colon' => __('Parent Publisher:', 'book-manager'),
            'edit_item'         => __('Edit Publisher', 'book-manager'),
            'update_item'       => __('Update Publisher', 'book-manager'),
            'add_new_item'      => __('Add New Publisher', 'book-manager'),
            'new_item_name'     => __('New Publisher Name', 'book-manager'),
            'menu_name'         => __('Publishers', 'book-manager'),
        ];

        $args = [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => 'publisher'],
            'show_in_rest'      => true,
        ];

        register_taxonomy('publisher', ['book'], $args);
    }

    /**
     * Register Authors taxonomy
     *
     * @return void
     */
    private function registerAuthorsTaxonomy()
    {
        $labels = [
            'name'              => _x('Authors', 'taxonomy general name', 'book-manager'),
            'singular_name'     => _x('Author', 'taxonomy singular name', 'book-manager'),
            'search_items'      => __('Search Authors', 'book-manager'),
            'all_items'         => __('All Authors', 'book-manager'),
            'parent_item'       => __('Parent Author', 'book-manager'),
            'parent_item_colon' => __('Parent Author:', 'book-manager'),
            'edit_item'         => __('Edit Author', 'book-manager'),
            'update_item'       => __('Update Author', 'book-manager'),
            'add_new_item'      => __('Add New Author', 'book-manager'),
            'new_item_name'     => __('New Author Name', 'book-manager'),
            'menu_name'         => __('Authors', 'book-manager'),
        ];

        $args = [
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => 'author'],
            'show_in_rest'      => true,
        ];

        register_taxonomy('authors', ['book'], $args);
    }
}


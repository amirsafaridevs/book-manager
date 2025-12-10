<?php

namespace BookManager\Services;

use BookManager\Services\AbstractService;

/**
 * Service for registering Book Custom Post Type
 */
class BookPostTypeService  extends AbstractService
{
    /**
     * Register the Book custom post type
     *
     * @return void
     */
    public function boot()
    {
        $labels = [
            'name'                  => _x('Books', 'Post type general name', 'book-manager'),
            'singular_name'         => _x('Book', 'Post type singular name', 'book-manager'),
            'menu_name'             => _x('Books', 'Admin Menu text', 'book-manager'),
            'name_admin_bar'        => _x('Book', 'Add New on Toolbar', 'book-manager'),
            'add_new'               => __('Add New', 'book-manager'),
            'add_new_item'          => __('Add New Book', 'book-manager'),
            'new_item'              => __('New Book', 'book-manager'),
            'edit_item'             => __('Edit Book', 'book-manager'),
            'view_item'             => __('View Book', 'book-manager'),
            'all_items'             => __('All Books', 'book-manager'),
            'search_items'          => __('Search Books', 'book-manager'),
            'parent_item_colon'     => __('Parent Books:', 'book-manager'),
            'not_found'             => __('No books found.', 'book-manager'),
            'not_found_in_trash'    => __('No books found in Trash.', 'book-manager'),
            'featured_image'        => _x('Book Cover Image', 'Overrides the "Featured Image" phrase', 'book-manager'),
            'set_featured_image'    => _x('Set cover image', 'Overrides the "Set featured image" phrase', 'book-manager'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the "Remove featured image" phrase', 'book-manager'),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the "Use as featured image" phrase', 'book-manager'),
            'archives'              => _x('Book archives', 'The post type archive label used in nav menus', 'book-manager'),
            'insert_into_item'      => _x('Insert into book', 'Overrides the "Insert into post"/"Insert into page" phrase', 'book-manager'),
            'uploaded_to_this_item' => _x('Uploaded to this book', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase', 'book-manager'),
            'filter_items_list'     => _x('Filter books list', 'Screen reader text for the filter links heading', 'book-manager'),
            'items_list_navigation' => _x('Books list navigation', 'Screen reader text for the pagination heading', 'book-manager'),
            'items_list'            => _x('Books list', 'Screen reader text for the items list heading', 'book-manager'),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => 'book'],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-book',
            'supports'           => ['title', 'editor', 'thumbnail', 'excerpt'],
            'show_in_rest'       => true,
        ];

        register_post_type('book', $args);
    }
}


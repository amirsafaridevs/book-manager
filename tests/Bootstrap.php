<?php
/**
 * Bootstrap file for PHPUnit tests
 * 
 * This file runs once before each test execution.
 * Its responsibilities are:
 * 1. Load libraries (autoloader)
 * 2. Mock WordPress functions (since WordPress is not available in test environment)
 * 3. Define necessary constants
 */

// ============================================
// Section 1: Loading Autoloader
// ============================================
// This line loads the autoloader file which allows us to use project classes
require_once dirname(__DIR__) . '/vendor/autoload.php';

// ============================================
// Section 2: Defining WordPress Constants
// ============================================
// ABSPATH is the main WordPress path used in the code
if (!defined('ABSPATH')) {
    define('ABSPATH', '/tmp/wordpress/');
}

// ============================================
// Section 3: Mocking WordPress Functions
// ============================================
// Since WordPress is not available in test environment, we need to mock its functions
// Mock means a simple version of the function that just works but is not real

// Function add_action: for adding actions to WordPress
if (!function_exists('add_action')) {
    function add_action($hook, $callback, $priority = 10, $accepted_args = 1) {
        // In tests, we just return true
        // because we don't want to actually add the action
        return true;
    }
}

// Function add_meta_box: for adding meta box to post edit page
if (!function_exists('add_meta_box')) {
    function add_meta_box($id, $title, $callback, $screen = null, $context = 'advanced', $priority = 'default', $callback_args = null) {
        return true;
    }
}

// Function esc_html: for sanitizing HTML text
if (!function_exists('esc_html')) {
    function esc_html($text) {
        // This function converts dangerous HTML characters
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

// Function __: for translating texts
if (!function_exists('__')) {
    function __($text, $domain = 'default') {
        // In tests, we return the text unchanged
        return $text;
    }
}

// Function get_post_meta: for getting post meta data
if (!function_exists('get_post_meta')) {
    function get_post_meta($post_id, $key = '', $single = false) {
        // In tests, we return an empty value
        return '';
    }
}

// Function update_post_meta: for saving post meta data
if (!function_exists('update_post_meta')) {
    function update_post_meta($post_id, $meta_key, $meta_value, $prev_value = '') {
        return true;
    }
}

// Function add_filter: for adding filters to WordPress
if (!function_exists('add_filter')) {
    function add_filter($hook, $callback, $priority = 10, $accepted_args = 1) {
        return true;
    }
}

// Function register_post_type: for registering custom post types
if (!function_exists('register_post_type')) {
    function register_post_type($post_type, $args = []) {
        return true;
    }
}

// Function register_taxonomy: for registering taxonomies
if (!function_exists('register_taxonomy')) {
    function register_taxonomy($taxonomy, $object_type, $args = []) {
        return true;
    }
}

// Function add_menu_page: for adding admin menu pages
if (!function_exists('add_menu_page')) {
    function add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function = '', $icon_url = '', $position = null) {
        return true;
    }
}

// Function get_post: for getting post data
if (!function_exists('get_post')) {
    function get_post($post = null, $output = OBJECT, $filter = 'raw') {
        $post_obj = new \WP_Post();
        $post_obj->ID = 1;
        $post_obj->post_type = 'book';
        return $post_obj;
    }
}

// Function current_user_can: for checking user capabilities
if (!function_exists('current_user_can')) {
    function current_user_can($capability, ...$args) {
        return true; // In tests, assume user has all capabilities
    }
}

// Function load_plugin_textdomain: for loading translations
if (!function_exists('load_plugin_textdomain')) {
    function load_plugin_textdomain($domain, $deprecated = false, $plugin_rel_path = false) {
        return true;
    }
}

// Function plugin_basename: for getting plugin basename
if (!function_exists('plugin_basename')) {
    function plugin_basename($file) {
        return basename($file);
    }
}

// Function _x: for translating with context
if (!function_exists('_x')) {
    function _x($text, $context, $domain = 'default') {
        return $text;
    }
}

// Function boman_handle_try_catch_error: for handling exceptions
if (!function_exists('boman_handle_try_catch_error')) {
    function boman_handle_try_catch_error($exception) {
        // In tests, just log or do nothing
        return;
    }
}

// Function sanitize_text_field: WordPress sanitization function
if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field($str) {
        return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
    }
}

// Function stripslashes_deep: WordPress function for removing slashes
if (!function_exists('stripslashes_deep')) {
    function stripslashes_deep($value) {
        if (is_array($value)) {
            return array_map('stripslashes_deep', $value);
        }
        return stripslashes($value);
    }
}

// Function absint: WordPress function for absolute integer
if (!function_exists('absint')) {
    function absint($maybeint) {
        return abs(intval($maybeint));
    }
}

// ============================================
// Section 4: Mocking WordPress Classes
// ============================================

// Class WP_Post: represents a post in WordPress
if (!class_exists('WP_Post')) {
    class WP_Post {
        public $ID = 0;              // Post ID
        public $post_type = 'post';  // Post type
        public $post_title = '';     // Post title
        public $post_content = '';   // Post content
        public $post_status = 'publish'; // Post status
    }
}

// Class WP_List_Table: for creating list tables in WordPress admin
if (!class_exists('WP_List_Table')) {
    class WP_List_Table {
        public $items = [];
        public $_column_headers = [];
        
        public function __construct($args = []) {
            // Empty constructor - just to make the class exist
        }
        
        public function get_pagenum() {
            return isset($_GET['paged']) ? max(1, absint($_GET['paged'])) : 1;
        }
        
        public function set_pagination_args($args) {
            // Mock method
        }
        
        public function display() {
            // Mock method
        }
    }
}

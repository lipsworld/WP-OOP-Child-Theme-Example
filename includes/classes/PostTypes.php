<?php 

/**
 * Register custom post types 
 */
 
class PostTypes {

    protected static $types = [];
    protected static $initialized = false;

    public static function instance() {

        if (self::$initialized) {
            return self;
        }

        self::initialize();
        return self;
    }

    public static function initialize() {
        
        if (self::$initialized) {
            return;
        }
            

        self::$types = [
            'film' => (object) [
                'singular' => 'Film',
                'plural' => 'Films'
            ]
        ];

        self::register();
    }

    public static function register() {
        foreach (self::$types as $name => $labels) {
            $args = [
                'public' => true,
                'labels' => self::labels($labels),
            ];
            $type = register_post_type($name, $args);
        }
    }

    protected static function labels($labels) {
        
        $singular = $labels->singular;
        $plural = $labels->plural;

        return [
                'name' => __($plural, CM_TEXT_DOMAIN),
                'singular_name' => __($singular, CM_TEXT_DOMAIN),
                'add_new' => __('Add ' . $singular, CM_TEXT_DOMAIN),
                'add_new_item' => __('Add ' . $singular, CM_TEXT_DOMAIN),
                'edit_item' => __('Edit ' . $singular, CM_TEXT_DOMAIN),
                'new_item' => __('New ' . $singular, CM_TEXT_DOMAIN),
                'view_item' => __('View ' . $singular, CM_TEXT_DOMAIN),
                'search_items' => __('Search ' . $plural, CM_TEXT_DOMAIN),
                'not_found' => __("No $plural found", CM_TEXT_DOMAIN),
                'not_found_in_trash' => __("No $plural found in Trash", CM_TEXT_DOMAIN),
                'parent_item_colon' => __('Parent ' . $singular . ' :', CM_TEXT_DOMAIN),
                'menu_name' => __($singular, CM_TEXT_DOMAIN),
            ];
    }
}

add_action('init', ['PostTypes', 'initialize']);

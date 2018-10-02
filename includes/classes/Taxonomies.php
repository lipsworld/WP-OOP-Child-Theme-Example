<?php 

namespace Codeline;

/**
 * Register taxonomies
 * Genre, Country, Year and Actors
 */
 
class Taxonomies {

    protected static $taxonomies = [];
    protected static $initialized = false;

    public static function init() {
        
        if (self::$initialized) {
            return;
        } 

        self::$taxonomies = [
            'genre' => [
                'labels' => (object) [
                    'singular' => 'Genre',
                    'plural' => 'Genres'
                ],
                'hierarchical' => false,
                'object_type' => 'cl_film'
            ],
            'country' => [
                'labels' => (object) [
                    'singular' => 'Country',
                    'plural' => 'Countries'
                ],
                'hierarchical' => false,
                'object_type' => 'cl_film'
            ],
            'year' => [
                'labels' => (object) [
                    'singular' => 'Year',
                    'plural' => 'Years'
                ],
                'hierarchical' => false,
                'object_type' => 'cl_film'
            ],
            'actor' => [
                'labels' => (object) [
                    'singular' => 'Actor',
                    'plural' => 'Actors'
                ],
                'hierarchical' => false,
                'object_type' => 'cl_film'
            ],
        ];
        self::register();
    }

    public static function register() {
        foreach (self::$taxonomies as $name => $args) {

            // setup required variables

            // replace labels
            $args['labels'] = self::labels($args['labels']);
            $object_type = $args['object_type'];
            unset($args['object_type']);
            
            register_taxonomy($name, $object_type, $args);
        }
        
    }

    protected static function labels($labels) {
        
        $singular = $labels->singular;
        $plural = $labels->plural;

        return [
            'name' => __($singular, CM_TEXT_DOMAIN),
            'singular_name' => __($singular, CM_TEXT_DOMAIN),
            'search_items' => __('Search '. $plural, CM_TEXT_DOMAIN),
            'popular_items' => __('Popular '. $plural, CM_TEXT_DOMAIN),
            'all_items' => __('All '. $plural, CM_TEXT_DOMAIN),
            'parent_item' => __('Parent '. $singular, CM_TEXT_DOMAIN),
            'parent_item_colon' => __('Parent '. $singular, CM_TEXT_DOMAIN),
            'edit_item' => __('Edit '. $singular, CM_TEXT_DOMAIN),
            'update_item' => __('Update '. $singular, CM_TEXT_DOMAIN),
            'add_new_item' => __('Add New '. $singular, CM_TEXT_DOMAIN),
            'new_item_name' => __('New ' . $singular . ' Name', CM_TEXT_DOMAIN),
            'add_or_remove_items'=> __('Add or remove '. $plural, CM_TEXT_DOMAIN),
            'menu_name'=> __($singular, CM_TEXT_DOMAIN)
        ];
    }

}

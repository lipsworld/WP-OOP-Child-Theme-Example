<?php 

namespace Codeline;

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
    
        self::$initialized = true;

        self::$types = [
            'cl_film' => [
                'labels' => (object) [
                    'singular' => 'Film',
                    'plural' => 'Films'
                ],
                'public' => true,
                'rewrite' => ['slug' => 'film'],
                'supports' => ['editor', 'title', 'author', 'comments']
            ]
        ];

        self::register();

        if (is_admin()) {
            self::metaBox();
            self::saveMeta();
        }
    }

    public static function register() {
        foreach (self::$types as $name => $args) {
            $args['labels'] = self::labels($args['labels']);
            register_post_type($name, $args);
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

    protected static function metaBox() {
        foreach (self::$types as $name => $args) {
            if (method_exists('PostTypes', 'add_meta_boxes_' . $name)) {
                add_action('add_meta_boxes_' . $name, ['PostTypes', 'add_meta_boxes_' . $name]);
            }
        }
    }

    public static function add_meta_boxes_cl_film($post) {
        
        if (method_exists('PostTypes', $post->post_type.'_meta_box_html')) {
            add_meta_box(
                'film-options',
                __('Film Options', CM_TEXT_DOMAIN), 
                ['PostTypes', $post->post_type.'_meta_box_html'],
                $post->post_type,
                'normal',
                'default'
            );
        }
    }

    public static function cl_film_meta_box_html($post) {
        $ticket_price = get_post_meta($post->ID, '_ticket_price', true);
        $release_date = get_post_meta($post->ID, '_release_date', true);

        ?>
        <?php wp_nonce_field('film-meta', 'film_nonce'); ?>
        <p>
            <label for="ticket-price">Ticket Price <br>
                <input type="number" step=".01" name="ticket-price" id="ticket-price" class="widefat" value="<?php echo esc_attr($ticket_price); ?>">
            </label>
        </p>

        <p>
            <label for="ticket-price">Release Date <br>
                <input type="date" name="release-date" id="release-date" class="widefat" value="<?php echo esc_attr($release_date); ?>">
            </label>
        </p>
        
        <?php
    }

    public static function saveMeta() {
        add_action('save_post', ['PostTypes', 'saveMetaValues']);
    }

    public static function saveMetaValues($post_id) {
        
        // return on autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // Check if our nonce is set.
        if (!isset($_POST['film_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['film_nonce'];

        // Verify that the nonce is valid.
        if (!wp_verify_nonce($nonce, 'film-meta')) {
            return $post_id;
        }

        // Check the user's permissions.
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }
        }

        if (isset($_POST['ticket-price'])) {
            $ticket_price = sanitize_text_field($_POST['ticket-price']);
            update_post_meta($post_id, '_ticket_price', $ticket_price);
        }

        if (isset($_POST['release-date'])) {
            $release_date = sanitize_text_field($_POST['release-date']);
            update_post_meta($post_id, '_release_date', $release_date);
        }
    }
}

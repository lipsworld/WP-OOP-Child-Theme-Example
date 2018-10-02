<?php 

namespace Codeline;

/**
 * Register custom post types 
 */
 
class PostTypes {

    protected static $types = [], $className = 'Codeline\PostTypes';
    protected static $initialized = false;

    public static function instance() {

        if (self::$initialized) {
            return self;
        }

        self::initialize();
        return self;
    }

    public static function init() {
        
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
                'has_archive' => 'films',
                'rewrite' => ['slug' => 'film'],
                'supports' => ['editor', 'title', 'author', 'comments']
            ]
        ];

        self::register();

        if (is_admin()) {
            self::metaBox();
            self::saveMeta();
        }

        add_filter('the_content', [self::$className, 'excerptMeta']);
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
            if (method_exists(self::$className, 'add_meta_boxes_' . $name)) {
                add_action('add_meta_boxes_' . $name, [self::$className, 'add_meta_boxes_' . $name]);
            }
        }
    }

    public static function add_meta_boxes_cl_film($post) {
        
        if (method_exists(self::$className, $post->post_type.'_meta_box_html')) {
            add_meta_box(
                'film-options',
                __('Film Options', CM_TEXT_DOMAIN), 
                [self::$className, $post->post_type.'_meta_box_html'],
                $post->post_type,
                'normal',
                'default'
            );
        }
    }

    public static function cl_film_meta_box_html($post) {
        $ticket_price = get_post_meta($post->ID, '_ticket_price', true);
        $release_date = date('m/d/Y', (int) get_post_meta($post->ID, '_release_date', true));

        ?>
        <?php wp_nonce_field('film-meta', 'film_nonce'); ?>
        <p>
            <label for="ticket-price">Ticket Price <br>
                <input type="number" step=".01" name="ticket-price" id="ticket-price" class="widefat" value="<?php echo esc_attr($ticket_price); ?>">
            </label>
        </p>

        <p>
            <label for="ticket-price">Release Date <br>
                <input type="text" name="release-date" id="release-date" class="widefat" value="<?php echo esc_attr($release_date); ?>">
            </label>
        </p>
        
        <?php
    }

    public static function saveMeta() {
        add_action('save_post', [self::$className, 'saveMetaValues']);
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
            $release_date = strtotime(sanitize_text_field($_POST['release-date']));
            update_post_meta($post_id, '_release_date', $release_date);
        }
    }

    public static function excerptMeta($content) {

        global $post;
              
        if ($post->post_type !== 'cl_film') {
            return $content;
        }

        if (is_singular('cl_film')) {
            return $content;
        }

        $ticket_price = get_post_meta($post->ID, '_ticket_price', true);
        $release_date = date('m/d/Y', (int) get_post_meta($post->ID, '_release_date', true));
        $country = get_the_term_list( $post->ID, 'country', '<strong>Country:</strong> ', ', ' );
        $genre = get_the_term_list( $post->ID, 'genre', '<strong>Genre:</strong> ', ', ' );

        return <<<HTML
            $content
            <div class="cl-film-excerpt">
                <div class="cl-film-excerpt-meta entry-meta">
                        <span><strong>Released:</strong> $release_date</span>
                        <span><i class="fa fa-money" aria-hidden="true"></i> \$$ticket_price</span>
						<span>$country</span>
                        <span>$genre</span>
                </div>
            </div>

HTML;

    }

}

<?php

namespace Codeline;

class CodelineMovies{

    protected static $instance = null, $initialized = false, 
        $className = 'Codeline\CodelineMovies';

    public static function getInstance() {

        if (null == self::$instance) {
            self::$instance = new self;
        }
 
        return self::$instance;
    }

    public static function init() {

        if (self::$initialized) {
            return;
        }

        self::$initialized = true;
        
        add_action('after_switch_theme', [self::$className, 'versionCheck']);
        add_action('after_switch_theme', 'flush_rewrite_rules');

        add_action('wp_enqueue_scripts', [self::$className, 'enqueueStyles']);

        // admin enqueue
        add_action('admin_enqueue_scripts', [self::$className, 'adminEnqueueScripts']);

        // other init
        add_action('init', ['Codeline\PostTypes', 'init']);
        add_action('init', ['Codeline\Taxonomies', 'init'], 100);

        add_action('init', ['Codeline\Shortcodes', 'init']);
    }

    public static function adminEnqueueScripts($hook){
        global $post;

        if ($post->post_type === 'cl_film' && ($hook === 'post.php' || $hook === 'post-new.php')) {
            
            wp_register_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
            wp_enqueue_style( 'jquery-ui' );

            wp_enqueue_script(
                'cl-admin-movie-edit',
                get_stylesheet_directory_uri() . '/js/admin-movies-edit.js',
                array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'),
                '',
                true
            );
        }
    }

    public static function enqueueStyles() {

        // parent theme css enqueue
        wp_enqueue_style('unite-style', get_template_directory_uri() . '/style.css');

        // theme css
        wp_enqueue_style('codeline-movies-style', get_stylesheet_directory_uri() . '/style.css', ['unite-style']);    
    }

    public static function versionCheck(){

        global $wp_version;
     
        // Compare versions.
        if (version_compare(phpversion(), CM_PHP_VERSION, '>') && version_compare($wp_version, CM_WP_VERSION, '>')) {
            return;
        }

        // not compatible
        add_action( 'admin_notices', [self::$className, 'uncompatibleNotice'] );

        // Switch back to previous theme.
        switch_theme( $old_theme->stylesheet );
    }

    public static function uncompatibleNotice() {
        $message = 'You need to upgrade your php to ' . CM_PHP_VERSION . '+ & WordPress to ' . CM_WP_VERSION . '+';
        ?>
            <div class="update-nag">
                <?php _e($message, CM_TEXT_DOMAIN ); ?>
            </div>
        <?php
    }
}

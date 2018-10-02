<?php 

namespace Codeline;

class Shortcodes{

    protected static $className = 'Codeline\Shortcodes';

    public static function init(){
        self::addShortcodes();
    }

    protected static function addShortcodes(){
        add_shortcode('movies', [self::$className, 'addShortcodeMovies']);
    }

    public static function addShortcodeMovies(){

        $limit = 5;
        $movies = get_posts(
            [
                'post_type' => 'cl_film',
                'posts_per_page' => $limit,
                'orderby' => 'meta_value_num',
                'order' => 'DESC',
                'meta_query' => [
                    [
                        'key' => '_release_date'
                    ]
                ]
            ]
        );

        $html = '';
        if(!empty($movies)): 
            $html .= '<ul class="list-group movie-widget-list movie-widget-recent">';

            foreach($movies as $movie):
                $release_date = date('m/d/Y', get_post_meta($movie->ID, '_release_date', true));
                $genre = get_the_term_list( $post->ID, 'genre', '', ', ' );
               $html .= <<<HTML
                <li id="movie-widget-item-recent-{$movie->ID}" class="movie-widget-item list-group-item">
                    <h4>{$movie->post_title}</h4>
                    <small class="movie-widget-item-meta">
                        Released: $release_date &#x25cf; $genre
                    </small>
                </li>
HTML;
        
        endforeach; 
            $html .= '</ul>';
        endif;

        return $html;
    }
}

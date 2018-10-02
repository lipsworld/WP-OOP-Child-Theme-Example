<?php
/**
 * @package unite
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header page-header">

		<?php 
                    if ( of_get_option( 'single_post_image', 1 ) == 1 ) :
                        the_post_thumbnail( 'unite-featured', array( 'class' => 'thumbnail' )); 
                    endif;
                  ?>

		<h1 class="entry-title "><?php the_title(); ?></h1>

		<div class="entry-meta">
			<?php unite_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'unite' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php
			$ticket_price = get_post_meta($post->ID, '_ticket_price', true);
			$release_date = date('m/d/Y', (int) get_post_meta($post->ID, '_release_date', true));
			$country = get_the_term_list( $post->ID, 'country', '<strong>Country:</strong> ', ', ' );
			$genre = get_the_term_list( $post->ID, 'genre', '<strong>Genre:</strong> ', ', ' );
		?>
            <div class="cl-film-excerpt">
                <div class="cl-film-excerpt-meta entry-meta">
                        <span><strong>Released:</strong> <?php echo $release_date; ?></span>
                        <span><i class="fa fa-money" aria-hidden="true"></i> $<?php echo $ticket_price; ?></span>
						<span><?php echo $country; ?></span>
                        <span><?php echo $genre; ?></span>
                </div>
            </div>

		<?php edit_post_link( __( 'Edit', 'unite' ), '<i class="fa fa-pencil-square-o"></i><span class="edit-link">', '</span>' ); ?>
		<?php unite_setPostViews(get_the_ID()); ?>
		<hr class="section-divider">
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->

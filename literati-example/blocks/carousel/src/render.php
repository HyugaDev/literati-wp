<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

// Fetch all promotion posts
$promotions_query = new WP_Query( array(
	'post_type' => 'promotions',
	'posts_per_page' => -1, 
	'order' => 'DESC',
) );

// Check if posts exists
if ( $promotions_query->have_posts() ) :
	?>
	<div class="promotions" <?php echo get_block_wrapper_attributes(); ?>>
		<div class="promotions-slider">
			<?php
			// Loop through each post
			while ( $promotions_query->have_posts() ) : $promotions_query->the_post();
				?>
				<div class="promotion-item">
					<?php
					// Fetch fields data
					$header = get_post_meta( get_the_ID(), 'header', true );
					$text = get_post_meta( get_the_ID(), 'text', true );
					$button = get_post_meta( get_the_ID(), 'button', true );
					$image = get_post_meta( get_the_ID(), 'image', true );
					
					if ( $image ) {
						echo '<img src="' . esc_url( $image ) . '" alt="Promotion Image" class="promotion-img col-sm-4 col-12">';
					}
					?>
					<div class="promotion-info-wrapper <?php echo ($image)? 'col-8' : 'col-12'; ?>">
						<h3 class="promotion-heading"><?php echo esc_html( $header ) ?></h3>
						<p class="promotion-text"><?php echo esc_html( $text ) ?></p>
						<a href="" class="promotion-btn"><?php echo esc_html( $button )?></a>
					</div>
				</div>
				<?php
			endwhile;
			?>
		</div>
		<div class="arrows">
			<div class="prev"><img src="<?php echo plugin_dir_url( dirname(dirname(dirname(__FILE__))) ) . 'includes/imgs/Arrow-Left.png' ?>" alt="Previous"></div>
			<div class="next"><img src="<?php echo plugin_dir_url( dirname(dirname(dirname(__FILE__))) ) . 'includes/imgs/Arrow-Right.png' ?>" alt="Next"></div>
		</div>
	</div>
	<?php
	// Restore original post data
	wp_reset_postdata();
else :
	?>
	<p><?php echo  __( 'No promotions found.', 'literati-example' ) ?></p>
	<?php
endif;

?>
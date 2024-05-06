<?php

namespace Literati\Example;

/**
 * Blocks class.
 */
class Blocks {
  /**
   * Init
   */
  public static function init() {
    add_action( 'init', [__CLASS__, 'register_blocks'] );
    add_action( 'init', [__CLASS__, 'create_promotion_post_type'] );
    add_action( 'add_meta_boxes', [__CLASS__, 'add_promotion_custom_fields'] );
    add_action( 'save_post', [__CLASS__, 'save_promotion_custom_fields'] );
    add_action( 'admin_enqueue_scripts', [__CLASS__, 'enqueue_scripts'] );
    add_action( 'wp_enqueue_scripts', [__CLASS__, 'wp_enqueue_scripts'] );
    add_action( 'init', [__CLASS__, 'meta_fields_register_meta'] );

  }

  /**
   * Register the Blocks
   */
  public static function register_blocks() {
    // Register the Carousel
    register_block_type( LITERATI_EXAMPLE_ABSPATH. 'blocks/carousel/build' );
  }

  /**
   * Register Promotion CPT
   */
  public static function create_promotion_post_type() {

    $labels = array(
        'name'                  => _x( 'Promotions', 'Post Type General Name', 'literati-example' ),
        'singular_name'         => _x( 'Promotion', 'Post Type Singular Name', 'literati-example' ),
        'menu_name'             => __( 'Promotions', 'literati-example' ),
        'name_admin_bar'        => __( 'Promotion', 'literati-example' ),
        'archives'              => __( 'Promotion Archives', 'literati-example' ),
        'attributes'            => __( 'Promotion Attributes', 'literati-example' ),
        'parent_item_colon'     => __( 'Parent Promotion:', 'literati-example' ),
        'all_items'             => __( 'All Promotions', 'literati-example' ),
        'add_new_item'          => __( 'Add New Promotion', 'literati-example' ),
        'add_new'               => __( 'Add New', 'literati-example' ),
        'new_item'              => __( 'New Promotion', 'literati-example' ),
        'edit_item'             => __( 'Edit Promotion', 'literati-example' ),
        'update_item'           => __( 'Update Promotion', 'literati-example' ),
        'view_item'             => __( 'View Promotion', 'literati-example' ),
        'view_items'            => __( 'View Promotions', 'literati-example' ),
        'search_items'          => __( 'Search Promotion', 'literati-example' ),
        'not_found'             => __( 'No promotion found', 'literati-example' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'literati-example' ),
        'featured_image'        => __( 'Featured Image', 'literati-example' ),
        'set_featured_image'    => __( 'Set featured image', 'literati-example' ),
        'remove_featured_image' => __( 'Remove featured image', 'literati-example' ),
        'use_featured_image'    => __( 'Use as featured image', 'literati-example' ),
        'insert_into_item'      => __( 'Insert into promotion', 'literati-example' ),
        'uploaded_to_this_item' => __( 'Uploaded to this promotion', 'literati-example' ),
        'items_list'            => __( 'Promotions list', 'literati-example' ),
        'items_list_navigation' => __( 'Promotions list navigation', 'literati-example' ),
        'filter_items_list'     => __( 'Filter promotions list', 'literati-example' ),
    );
    $args = array(
        'label'                 => __( 'Promotion', 'literati-example' ),
        'description'           => __( 'Custom post type to display different promotions.', 'literati-example' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail' ),
        'taxonomies'            => array( 'category', 'post_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-megaphone',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );
    register_post_type( 'promotions', $args );

  }

  /**
   * Add post meta box
   */
  public static function add_promotion_custom_fields() {
    add_meta_box(
        'promotion_custom_fields',
        __( 'Promotion Custom Fields', 'literati-example' ),
        [__CLASS__, 'render_promotion_custom_fields'],
        'promotions',
        'normal',
        'default'
    );
  }

  /**
   * Render post meta fields.
   */
  public static function render_promotion_custom_fields( $post ) {
    // Add nonce for security
    wp_nonce_field( basename( __FILE__ ), 'promotion_nonce' );

    // Retrieve values
    $header = get_post_meta( $post->ID, 'header', true );
    $text = get_post_meta( $post->ID, 'text', true );
    $button = get_post_meta( $post->ID, 'button', true );
    $image = get_post_meta( $post->ID, 'image', true );

    // Display the fields
    ?>
    <p>
        <label for="header"><?php _e( 'Header', 'text_domain' ); ?></label>
        <input type="text" name="header" id="header" value="<?php echo esc_attr( $header ); ?>" class="regular-text">
    </p>
    <p>
        <label for="text"><?php _e( 'Text', 'text_domain' ); ?></label>
        <textarea name="text" id="text" class="large-text"><?php echo esc_textarea( $text ); ?></textarea>
    </p>
    <p>
        <label for="button"><?php _e( 'Button', 'text_domain' ); ?></label>
        <input type="text" name="button" id="button" value="<?php echo esc_attr( $button ); ?>" class="regular-text">
    </p>
    <p>
        <label for="image"><?php _e( 'Image', 'text_domain' ); ?></label>
        <input type="text" name="image" id="image" value="<?php echo esc_attr( $image ); ?>" class="regular-text">
        <input type="button" id="upload_image_button" class="button" value="Upload Image">
        <div id="image-container">
            <?php if ( $image ) : ?>
                <img src="<?php echo esc_url( $image ); ?>" alt="Uploaded Image" style="max-width: 100px;">
            <?php endif; ?>
        </div>
    </p>
    <?php
  }

  /**
   * Save post meta's.
   */
  public static function save_promotion_custom_fields( $post_id ) {
    // Check if our nonce is set
    if ( ! isset( $_POST['promotion_nonce'] ) ) {
        return $post_id;
    }

    $nonce = $_POST['promotion_nonce'];

    // Verify that the nonce is valid
    if ( ! wp_verify_nonce( $nonce, basename( __FILE__ ) ) ) {
        return $post_id;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }

    // Check the user's permissions
    if ( 'promotion' === $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return $post_id;
        }
    } else {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }
    }

    // Save the custom fields data
    $fields = array( 'header', 'text', 'button', 'image' );
    foreach ( $fields as $field ) {
        if ( isset( $_POST[$field] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
    }
  }
  public static function enqueue_scripts() {
    wp_enqueue_media();
    wp_enqueue_script( 'promotion-admin-script', plugin_dir_url( __FILE__ ) . 'js/admin-script.js', array( 'jquery' ), null, true );
  }

  public static function wp_enqueue_scripts() {

    // Enqueue public scripts and styles here
    wp_enqueue_style( 'promotion-slick-theme', plugin_dir_url( __FILE__ ) . 'css/slick-theme.css' );
    wp_enqueue_style( 'promotion-public-css', plugin_dir_url( __FILE__ ) . 'css/public-style.css' );
    wp_enqueue_style( 'promotion-slick-css', plugin_dir_url( __FILE__ ) . 'css/slick.css' );
    wp_enqueue_style( 'promotion-bootstrap-css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css' );
    wp_enqueue_script( 'promotion-public-script', plugin_dir_url( __FILE__ ) . 'js/public-script.js', array( 'jquery' ), null, true );
    $promotion_timer = get_post_meta( get_the_ID(), 'promotion_timer', true );


	if($promotion_timer != ''){

		wp_localize_script(
			'promotion-public-script',
			'public_obj',
			array(
				'timer' => esc_html($promotion_timer),
			)
		);
	}
    wp_enqueue_script( 'promotion-slick-js', plugin_dir_url( __FILE__ ) . 'js/slick.min.js', array( 'jquery' ), null, true );
    wp_enqueue_script( 'promotion-bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), null, true );
  }

  public static function meta_fields_register_meta() {
    $metafields = [ 'promotion_timer' ];

    foreach( $metafields as $metafield ){
        // Pass an empty string to register the meta key across all existing post types.
        register_post_meta( '', $metafield, array(
            'show_in_rest' => true,
            'type' => 'string',
            'single' => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback' => function() {
                return current_user_can( 'edit_posts' );
            }
        ));
    }
  }
}

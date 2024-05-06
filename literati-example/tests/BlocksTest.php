<?php

namespace Literati\Example\Tests;

use Literati\Example\Blocks;
use WP_Mock\Tools\TestCase;

final class BlocksTest extends TestCase {

    public function setUp(): void {
        WP_Mock::setUp();
    }

    public function tearDown(): void {
        WP_Mock::tearDown();
    }

    /**
     * Test registering the blocks
     */
    public function test_register_blocks() {
        // Mock register_block_type function
        WP_Mock::userFunction('register_block_type', [
            'times' => 1,
            'args' => [LITERATI_EXAMPLE_ABSPATH . 'blocks/carousel/build'],
        ]);

        // Execute
        Blocks::register_blocks();
    }

    /**
     * Test custom post type registration
     */
    public function test_create_promotion_post_type() {
        // Mock register_post_type function
        WP_Mock::userFunction('register_post_type', [
            'times' => 1,
            'args' => function($type, $args) {
                return $type === 'promotions';
            }
        ]);

        // Execute
        Blocks::create_promotion_post_type();
    }

    /**
     * Test adding custom fields
     */
    public function test_add_promotion_custom_fields() {
        // Mock add_meta_box function
        WP_Mock::userFunction('add_meta_box', [
            'times' => 1,
            'args' => function($id) {
                return $id === 'promotion_custom_fields';
            }
        ]);

        // Execute
        Blocks::add_promotion_custom_fields();
    }

    /**
     * Test saving custom fields
     */
    public function test_save_promotion_custom_fields() {
        $post_id = 123;

        // Mock functions used in save_promotion_custom_fields
        WP_Mock::userFunction('wp_verify_nonce', [
            'return' => true
        ]);
        WP_Mock::userFunction('defined', [
            'return' => false,
            'args' => ['DOING_AUTOSAVE']
        ]);
        WP_Mock::userFunction('current_user_can', [
            'return' => true
        ]);
        WP_Mock::userFunction('update_post_meta', [
            'times' => 4
        ]);

        // Set POST data
        $_POST['promotion_nonce'] = 'valid_nonce';
        $_POST['header'] = 'Test Header';
        $_POST['text'] = 'Test Text';
        $_POST['button'] = 'Test Button';
        $_POST['image'] = 'Test Image';

        // Execute
        Blocks::save_promotion_custom_fields($post_id);
    }

    /**
      * Test enqueue scripts
     */
    public function test_enqueue_scripts() {
      // Mock wp_enqueue_media and wp_enqueue_script functions
      WP_Mock::userFunction('wp_enqueue_media', ['times' => 1]);
      WP_Mock::userFunction('wp_enqueue_script', [
          'times' => 1,
          'args' => function ($handle) {
              return $handle === 'promotion-admin-script';
          }
      ]);

      // Execute
      Blocks::enqueue_scripts();
  }

  /**
     * Test wordpress enqueue scripts
  */
  public function test_wp_enqueue_scripts() {
    // Mock wp_enqueue_style and wp_enqueue_script functions
    WP_Mock::userFunction('wp_enqueue_style', [
        'times' => 4,
        'args' => function ($handle) {
            return in_array($handle, [
                'promotion-slick-theme', 'promotion-public-css', 'promotion-slick-css', 'promotion-bootstrap-css'
            ]);
        }
    ]);
    WP_Mock::userFunction('wp_enqueue_script', [
        'times' => 3,
        'args' => function ($handle) {
            return in_array($handle, [
                'promotion-public-script', 'promotion-slick-js', 'promotion-bootstrap-js'
            ]);
        }
    ]);

    // Mock get_post_meta
    WP_Mock::userFunction('get_post_meta', [
        'times' => 1,
        'return' => 'timer_value'
    ]);

    // Mock wp_localize_script
    WP_Mock::userFunction('wp_localize_script', [
        'times' => 1,
        'args' => function ($handle, $name, $data) {
            return $handle === 'promotion-public-script' && $name === 'public_obj' && $data['timer'] === 'timer_value';
        }
    ]);

    // Execute
    Blocks::wp_enqueue_scripts();
  }

  /**
     * Test meta fields register
  */
  public function test_meta_fields_register_meta() {
    // Mock register_post_meta function
    WP_Mock::userFunction('register_post_meta', [
        'times' => 1,
        'args' => function ($post_type, $meta_key, $args) {
            return $meta_key === 'promotion_timer' && $args['show_in_rest'] === true;
        }
    ]);

    // Execute
    Blocks::meta_fields_register_meta();
  }
}

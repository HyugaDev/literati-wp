<?php

namespace Literati\Example\Tests;

use Literati\Example\Plugin;
use WP_Mock\Tools\TestCase as TestCase;
use WP_Mock;

final class PluginTest extends TestCase {
  public function setUp(): void {
    WP_Mock::setUp();

    Plugin::instance();
    /** Setup mocks */
    WP_Mock::userFunction('get_option', [
      'return' => function ($key) {
        switch ($key) {
          case 'LITERATI_EXAMPLE_VERSION':
            return null;
        }
      },
    ]);

    WP_Mock::userFunction('remove_action', [
      'return' => true,
    ]);

    WP_Mock::userFunction('plugin_dir_path', [
      'return' => function ($dir) {
        return $dir . '/../';
      },
    ]);

    WP_Mock::userFunction('trailingslashit', [
      'return' => function ($path) {
        return rtrim($path, '/') . '/';
      },
    ]);

    WP_Mock::userFunction('get_post_meta', [
      'return' => function($post_id, $key, $single){
        if ($key === 'header') {
          return 'some value';
        }
        return 'another';
      }
    ]);

  }

  public function tearDown(): void {
    WP_Mock::tearDown();
  }

  public function test_happy() {
    $plugin = Plugin::instance();

    $this->assertSame($plugin->get_plugin_version(), '1.0.0');
    $this->assertSame($plugin->is_plugin_initialized(), true);
  }

  public function test_constants_defined() {
    $this->assertTrue(defined('LITERATI_EXAMPLE_ABSPATH'), 'LITERATI_EXAMPLE_ABSPATH is not defined');
    $this->assertEquals(trailingslashit(plugin_dir_path(__DIR__)), LITERATI_EXAMPLE_ABSPATH);
  }
}

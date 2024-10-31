<?php
/**
 * @package WPPW
 * @subpackage Sensors
 * Wordpress Content.
 */
class WPPW_Sensors_Content extends WPPW_AbstractSensor {
  /**
   * @var Pressword
   */
  public $app;
  // protected $app;

  public function __construct(Pressword $app) {
    $this->app = $app;
  }

  /**
   * Listening to events using WP hooks.
   */
  public function HookEvents() {
    $this->addHooks(
      array (
        // 'edit_category',
        // 'create_category',
        // 'create_post_tag',
        // 'wp_head',

        /* page actions */
        'publish_page', // when page is published

        /* post actions */
        'publish_post',

        // 'publish_future_post',
        'delete_post',
        'wp_trash_post',
        'untrash_post'
      )
    );
  }

}

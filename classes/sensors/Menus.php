<?php
/**
 * @package WPPW
 * @subpackage Sensors
 * Menus sensor.
 *
 * 2078 User created new menu
 * 2079 User added content to a menu
 * 2080 User removed content from a menu
 * 2081 User deleted menu
 * 2082 User changed menu setting
 * 2083 User modified content in a menu
 * 2084 User changed name of a menu
 * 2085 User changed order of the objects in a menu
 * 2089 User moved objects as a sub-item
 */
// Attachments
class WPPW_Sensors_Menus extends WPPW_AbstractSensor {
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
        // 'wp_create_nav_menu',
        // 'wp_delete_nav_menu',
        // 'wp_update_nav_menu',
        // 'wp_update_nav_menu_item',
        // 'admin_menu',
        // 'customize_register',
        // 'customize_save_after'
      )
    );
  }
}

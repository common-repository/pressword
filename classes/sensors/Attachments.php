<?php
/**
 * @package WPPW
 * @subpackage Sensors
 * Wordpress Attachments.
 *
 * 2010 User uploaded file from Uploads directory
 * 2011 User deleted file from Uploads directory
 * 2046 User changed a file using the theme editor
 * 2051 User changed a file using the plugin editor
 */
// Attachments
class WPPW_Sensors_Attachments extends WPPW_AbstractSensor {
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
        // 'add_attachment',
        // 'delete_attachment',
        // 'admin_init'
      )
    );
  }

}

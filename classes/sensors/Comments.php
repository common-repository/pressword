<?php
/**
 * @package WPPW
 * @subpackage Sensors
 * Wordpress Comments.
 *
 * 2090 User approved a comment
 * 2091 User unapproved a comment
 * 2092 User replied to a comment
 * 2093 User edited a comment
 * 2094 User marked a comment as Spam
 * 2095 User marked a comment as Not Spam
 * 2096 User moved a comment to trash
 * 2097 User restored a comment from the trash
 * 2098 User permanently deleted a comment
 * 2099 User posted a comment
 */
// Attachments
class WPPW_Sensors_Comments extends WPPW_AbstractSensor {
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
        // 'edit_comment',
        // 'transition_comment_status',
        // 'spammed_comment',
        // 'unspammed_comment',
        // 'trashed_comment',
        // 'untrashed_comment',
        // 'deleted_comment',
        // 'comment_post'
      )
    );
  }

}

<?php
/**
 * Compiler object manages tree retrieval, manipulation and publishing
 * @package Pressword
 */

/**
 * Class WPPW_Compiler
 */
class WPPW_Compiler {

  /**
   * Application container.
   *
   * @var Pressword
   */
  public $app;

  /**
   * Instantiates a new Compiler object
   *
   * @param Pressword $app Application container.
   */
  public function __construct(Pressword $app) {
    $this->app = $app;
  }

  public function displayNotification($api, $action) {
    // $this->kennyLoggins($api);

    ?>
      <p id='notifElement'>Subscriber <?php echo $api['name']?> has been notified of <?php $action ?></p>";
    <?php
  }

  // public function kennyLoggins($content) {
  //   $location = SITE_ROOT."/wp-content/plugins/pressword/hugo_log.txt";
  //   $this->logger = new Logger($location);
  //   $this->logger->setTimestamp("D M d 'y h.i A");
  //   $this->logger->putLog($content);
  // }

  // Check Hugo API status
  public function checkAPIStatus($url) {
    return wp_remote_get($url);
  }

  public function postAPI($payload, $url) {
    $response = wp_remote_post(
      $url,
      array('body' => array(
        'payload' => json_encode($payload)
      ))
    );

    if (is_wp_error($response)) {
      $frontRes = $response->get_error_message();
    } else {
      $frontRes = $response['body'];
    }

    // $this->kennyLoggins($api);
    return $frontRes;
  }

  /**
   * Called on multitude of hooks.
   *
   * @param int $post_id Post ID.
   */
  public function triggerAPIs($id, $action, $content) {
    $apis = get_option('pressword');
    foreach ($apis as $api) {
      $url = $api['uri'];
      if ($this->checkAPIStatus($url)) {
        if (in_array($action, $api['hooks']) && $api['active']) {
          $this->postAPI(
            $this->createPayload(
              $action,
              $id,
              $content,
              $api),
            $url
          );
          // $this->displayNotification($api, $action);
          // $this->slackTest();
        }
      }
    }
    // $this->kennyLoggins($api);
  }

  public function payloadProto($action, $id, $content) {
    return array(
      'wp_action' => $action,
      'wp_id' => $id,
      'wp_content' => $content,
      'wp_testing' => true,
    );
  }

  // Determine what kind of build command to pass API
  public function createPayload($action, $id, $content, $api) {
    $payload = $this->payloadProto(
      $action,
      $id,
      $content);

    foreach($api['properties'] as $prop) {
      $payload[$prop['name']] = $prop['value'];
    }
    return $payload;
  }

  // public function slackTest() {
  //   $url = 'https://hooks.slack.com/services/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';
  //
  //   $payload = array(
  //     'text' => 'testing testing slack test from wordpress',
  //     'channel' => '#yobo',
  //     'username' => 'Rhobot',
  //     'icon_emoji' => ':rhogiggles:',
  //   );
  //
  //   $response = wp_remote_post(
  //     $url,
  //     array('body' => array(
  //       'payload' => json_encode($payload)
  //     ))
  //   );
  // }
}

<?php
/**
 * Admin object manages settings and administration
 * @package Pressword
 */

/**
 * Class WPPW_Admin
 */
class WPPW_Admin {

  /**
   * Application container.
   *
   * @var Pressword
   */
  public $app;

  /**
   * Instantiates a new Admin object
   *
   * @param Pressword $app Application container.
   */
  public function __construct( Pressword $app ) {
    $this->app = $app;
    $this->setup_admin_actions();
    $this->consumer_actions();
  }

  // TODO: allow actions to be hooked
  public function setup_admin_actions(){
    add_action('admin_menu', array($this, 'create_pressword_options_page'));
    add_action('admin_init', array($this, 'create_pressword_options'));
  }

  public function consumer_actions(){
      // api test
      add_action('wp_ajax_nopriv_pressword_rest_bulk', array($this, 'pressword_rest_bulk'));
      add_action('wp_ajax_pressword_rest_bulk', array($this, 'pressword_rest_bulk'));

      // api addition
      add_action('wp_ajax_nopriv_pressword_rest_post', array($this, 'pressword_rest_post'));
      add_action('wp_ajax_pressword_rest_post', array($this, 'pressword_rest_post'));

      // api removal
      add_action('wp_ajax_nopriv_pressword_rest_delete', array($this, 'pressword_rest_delete'));
      add_action('wp_ajax_pressword_rest_delete', array($this, 'pressword_rest_delete'));

      // server apis
      add_action('wp_ajax_nopriv_pressword_rest_get', array($this, 'pressword_rest_get'));
      add_action('wp_ajax_pressword_rest_get', array($this, 'pressword_rest_get'));

  }

  public function set_defaults_option() {
    if(!is_array(get_option('pressword'))) {
      $apis = array(
        'hugo' => array(
          'name' => 'hugo',
          'uri' => 'http://listener:3000/hugopress/endpoints',
          'hooks' => array(
            'publish_post',
            'untrash_post'
          ),
          'properties' => array(
            'name' => 'test',
            'value' => 'foobar'
          ),
          'active' => true
        ),
        'example-api' => array(
          'name' => 'example-api',
          'uri' => 'http://example.com/add/your/endpoint/here',
          'hooks' => array(
            'publish_post',
            'untrash_post'
          ),
          'properties' => array(
            'name' => 'foobar',
            'value' => 'fooit'
          ),
          'active' => false
        )
      );

      update_option('pressword', $apis, true);
    }
  }

  public function pressword_rest_bulk(){
    $bulk = $_POST['bulk'];
    $cmd = $_POST['cmd'];

    foreach($bulk as $item) {
      switch($cmd) {
        case 'delete':
          $apis = $this->delete_api($item);
          break;
        case 'post':
          $apis = $this->post_api($item);
          break;
      }
    }

    $json = json_encode(
      array(
        'apis' => $apis
      )
    );

    echo $json;
    die();
  }

  public function pressword_rest_get(){
    $json = json_encode(
      array(
        'apis' => $this->get_apis()
      )
    );

    echo $json;
    die();
  }

  public function get_apis(){
    return get_option('pressword');
  }

  public function pressword_rest_post(){
    $name = $_POST['name'];
    $uri = $_POST['uri'];
    $hooks = $_POST['hooks'];
    $properties = $_POST['properties'];
    $active = $_POST['active'];

    if( $name == '' || $uri == '' ) {
      die(
        json_encode(
          array(
            'success' => false,
            'message' => 'Missing required information.',
            'info' => $_POST
          )
        )
      );
    }

    $json = json_encode(
      array(
        'apis' => $this->post_api(array(
          'name' => $name,
          'uri' => $uri,
          'hooks' => $hooks,
          'properties' => $properties,
          'active' => $active)
        )
      )
    );

    echo $json;
    die();
  }

  public function post_api($api){
    $apis = get_option('pressword');
    $apis[$api['name']] = $api;
    update_option('pressword', $apis, true);
    return get_option('pressword');
  }

  public function pressword_rest_delete(){
    $name = $_POST['name'];

    if( $name == '') {
      die(
        json_encode(
          array(
            'success' => false,
            'message' => 'Missing required information.'
          )
        )
      );
    }

    $updated_apis = $this->delete_api($name);
    $json = json_encode(
      array(
        'apis' => $updated_apis
      )
    );

    echo $json;
    die();
  }

  public function delete_api($name){
    $apis = get_option('pressword');
    unset($apis[$name]);
    update_option('pressword', $apis, true);
    return get_option('pressword');
  }

  public function create_pressword_options_page(){
      // Add the menu item and page
      $page_title = 'PressWord Settings Page';
      $menu_title = 'PressWord';
      $capability = 'manage_options';
      $slug = 'pressword';
      $callback = array( $this, 'pressword_settings_page_content' );
      $icon = 'dashicons-admin-plugins';
      $position = 100;

      add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
      // add_submenu_page('options-general.php', $page_title, $menu_title, $capability, $slug, $callback);
      // add_options_page($page_title, $menu_title, $capability, $slug, $callback);
  }

  public function pressword_settings_page_content(){

    settings_fields( 'pressword' );
    do_settings_sections( 'pressword' );
    ?>
     <div class="wrap">
        <form method="post" action="options.php">
          <div id="pressword-root"></div>
        </form>
        </div>
    <?php
  }

  // this should trigger on add
  public function create_pressword_options(){
      register_setting(
          'pressword',
          'pressword'
      );
      $this->set_defaults_option();
  }

  public function checkAPI($url) {
      return wp_remote_get( $url );
  }
}

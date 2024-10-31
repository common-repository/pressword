<?php
/**
Plugin Name: PressWord
Plugin URI: http://wordpress.org/plugins/pressword/
Description: Utilize WordPress as an event API, pushing out data on selected events to specified endpoints.
Authors: Blake Watkins, Steven Waller, Unclesisu
Version: 0.1.1
Author URI: https://sisumedia.com/
License: MIT
*/

/**
 * @package PressWord
 * @version 0.1.1
 */
/*

/**
 * @package WPPW
 *
 * Main application class for the plugin. Responsible for bootstrapping
 * any hooks and instantiating all service classes.
 */
class Pressword {

  /**
   * Object instance.
   *
   * @var self
   */
  public static $instance;

  /**
   * Language text domain.
   *
   * @var string
   */
  public static $text_domain = 'pressword';

  /**
   * Current version.
   *
   * @var string
   */
  public $version = '0.1.1';
    const PLG_CLS_PRFX = 'WPPW_';

  /**
   * Compiler object.
   * @var WPPW_Compiler

   * Admin object.
   * @var WPPW_Admin
   *
   * Logger object.
   * @var Logger
   *
   * Sensor object.
   * @var Sensors
   *
   * current wp action.
   * @var action
   */
  public $compiler;
  public $admin;
  // public $logger;
  public $sensors;
  public $action = 'nope';

  /**
   * Standard singleton pattern.
   * WARNING! To ensure the system always works as expected, AVOID using this method.
   * Instead, make use of the plugin instance provided by 'wsal_init' action.
   * @return Pressword Returns the current plugin instance.
   */
  public static function GetInstance()
  {
    static $instance = null;
    if (!$instance) {
      $instance = new self();
    }
    return $instance;
  }

  /**
   * Called at load time, hooks into WP core
   */
  public function __construct() {
    // Define important plugin constants.
    $this->define_constants();

    require_once(__DIR__ . '/setup-hooks.php');
    require_once( 'classes/Autoloader.php' );
    // require_once( 'logger.php' );
    $this->autoloader = new WPPW_Autoloader( $this );
    $this->autoloader->Register( self::PLG_CLS_PRFX, $this->GetBaseDir() . 'classes' . DIRECTORY_SEPARATOR );

    $this->compiler = new WPPW_Compiler( $this );
    $this->sensors = new WPPW_SensorManager( $this );
    $this->admin = new WPPW_Admin( $this );

    add_action( 'init', array( $this, 'Init' ) );
  }

  /**
   * Boot/Loader method
   */
  public function boot() {
      // Load up stuff here if needed
  }

  /**
   * @internal Start to trigger the events after installation.
   */
  public function Init() {
    // Start listening to events
    Pressword::GetInstance()->sensors->HookEvents();
  }

  /**
   * Returns the class name of a particular file that contains the class.
   * @param string $file File name.
   * @return string Class name.
   */
  public function GetClassFileClassName($file)
  {
    return $this->autoloader->GetClassFileClassName($file);
  }

  public function GetBaseUrl()
  {
    return plugins_url('', __FILE__);
  }

  /**
   * @return string Full path to plugin directory WITH final slash.
   */
  public function GetBaseDir()
  {
    return plugin_dir_path(__FILE__);
  }

  /**
   * @return string Plugin directory name.
   */
  public function GetBaseName()
  {
    return plugin_basename(__FILE__);
  }

  public function define_constants() {

    // Plugin version.
    if ( ! defined( 'WPPW_VERSION' ) ) {
      define( 'WPPW_VERSION', $this->version );
    }
    // Plugin Name.
    if ( ! defined( 'WPPW_BASE_NAME' ) ) {
      define( 'WPPW_BASE_NAME', plugin_basename( __FILE__ ) );
    }
    // Plugin Directory URL.
    if ( ! defined( 'WPPW_BASE_URL' ) ) {
      define( 'WPPW_BASE_URL', plugin_dir_url( __FILE__ ) );
    }
    // Plugin Directory Path.
    if ( ! defined( 'WPPW_BASE_DIR' ) ) {
      define( 'WPPW_BASE_DIR', plugin_dir_path( __FILE__ ) );
    }
    // TODO: this needs to be abstracted, path to specific
    // Plugin Logging Path.
    // if ( ! defined( 'WPPW_LOGGER' ) ) {
      // define( 'WPPW_BASE_DIR', "/wp-content/plugins/pressword/pressword_log.txt" );
    // }
  }

}

add_action('plugins_loaded', array(Pressword::GetInstance(), 'boot'));

return Pressword::GetInstance();

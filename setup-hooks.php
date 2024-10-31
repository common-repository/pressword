<?php

function pressword_scripts_basic()
{
    // Register the script like this for a plugin:
    // wp_register_script('pressword', plugins_url('/js/pressword.js', __FILE__ ));

    // React scripts
    wp_register_script( 'pressword', plugins_url( 'build/admin.js', __FILE__ ), array(), time(), true );
    wp_register_style( 'pressword-css', plugins_url( 'build/bundle.css', __FILE__ ), array(), time() );

    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script('pressword');
    wp_enqueue_style('pressword-css');

    // wp_enqueue_script('jquery');
    // wp_enqueue_script('pressword');
    wp_localize_script('pressword', 'pressword_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));

}

add_action('admin_enqueue_scripts', 'pressword_scripts_basic');
add_action('wp_enqueue_scripts', 'pressword_scripts_basic');

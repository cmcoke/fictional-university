<?php

/**
 * This PHP code defines a custom WordPress plugin called "Are You Paying Attention Quiz." 
 * The plugin registers a custom Gutenberg block that allows users to input a sky color and a grass color.
 * These color values are saved as block attributes, and the plugin provides a dynamic rendering of these values on the front-end.
 * The block's JavaScript functionality (registered via the adminAssets method) works together with the JavaScript code 
 * from the previous example, which defines the block's behavior in the editor.
 */



/*
  Plugin Name: Are You Paying Attention Quiz
  Description: This plugin gives readers a multiple choice question.
  Version: 1.0
  Author: Charles Coke
  Author URI: https://www.charlescoke.com/
*/

// Prevent direct access to the plugin file.
if (!defined('ABSPATH')) exit;

// Define the AreYouPayingAttention class that registers the block and handles its rendering.
class AreYouPayingAttention
{

  // Constructor function that initializes the plugin and hooks into the 'init' action.
  function __construct()
  {
    add_action('init', array($this, 'adminAssets')); // Hook to load assets and register the block.
  }

  // Method to load block assets and register the block with a render callback.
  function adminAssets()
  {

    //
    register_block_type(__DIR__, array(
      'render_callback' => array($this, 'theHTML') // Use the render callback to dynamically render content on the front end.
    ));
  }

  // Method to render the block's content on the front-end based on the block attributes.
  function theHTML($attributes)
  {

    if (!is_admin()) {
      wp_enqueue_script('attentionFrontend', plugin_dir_url(__FILE__) . 'build/frontend.js', array('wp-element', 'wp-blocks'), '1.0', true);
    }

    ob_start(); ?>
    <div class="paying-attention-update-me">
      <pre style="display: none;"><?php echo wp_json_encode($attributes) ?></pre>
    </div>
<?php return ob_get_clean();
  }
}

// Create an instance of the AreYouPayingAttention class to initialize the plugin.
$areYouPayingAttention = new AreYouPayingAttention();

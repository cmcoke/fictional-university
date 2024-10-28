<?php

/**
 * This plugin registers a custom Gutenberg block called "Featured Professor." It dynamically displays professor details
 * and related posts using WordPress REST API endpoints and block attributes. It includes features for localization,
 * enqueues scripts and styles, and registers a custom REST API route for generating professor content.
 */



/*
  Plugin Name: Featured Professor Block Type
  Version: 1.0
  Author: Your Name Here
  Author URI: https://www.udemy.com/user/bradschiff/
  Text Domain: featured-professor
  Domain Path: /languages
 */

if (! defined('ABSPATH')) exit; // Prevents direct access to the plugin PHP file

// Includes PHP files for generating professor HTML and related posts
require_once plugin_dir_path(__FILE__) . 'inc/generateProfessorHTML.php';
require_once plugin_dir_path(__FILE__) . 'inc/relatedPostsHTML.php';

// Defines the FeaturedProfessor class, containing all plugin functionality.
class FeaturedProfessor
{
  // Constructor method to initialize hooks for plugin setup.
  function __construct()
  {
    add_action('init', [$this, 'onInit']); // Hook for registering block and metadata on WordPress initialization.
    add_action('rest_api_init', [$this, 'profHTML']); // Hook for registering custom REST API route.
    add_filter('the_content', [$this, 'addRelatedPosts']); // Hook to append related posts to professor content.
  }

  // Appends related posts list to the professor's single post content.
  function addRelatedPosts($content)
  {
    // Ensures that the content is for a singular 'professor' post in the main loop.
    if (is_singular('professor') && in_the_loop() && is_main_query()) {
      return $content . relatedPostsHTML(get_the_id()); // Appends related posts HTML to the professor's content.
    }
    return $content; // Returns content unchanged if conditions aren't met.
  }

  // Registers a custom REST API route to retrieve professor HTML content.
  function profHTML()
  {
    register_rest_route('featuredProfessor/v1', 'getHTML', array(
      'methods' => WP_REST_SERVER::READABLE, // Specifies the route as readable (GET).
      'callback' => [$this, 'getProfHTML'] // Calls the getProfHTML method to generate response data.
    ));
  }

  // Callback function for the REST API route to retrieve professor HTML based on professor ID.
  function getProfHTML($data)
  {
    return generateProfessorHTML($data['profId']); // Calls external function to generate HTML based on 'profId'.
  }

  // Initializes the plugin by registering block type, scripts, styles, metadata, and translations.
  function onInit()
  {
    // Loads plugin text domain for localization support.
    load_plugin_textdomain('featured-professor', false, dirname(plugin_basename(__FILE__)) . '/languages');

    // Registers custom metadata for professors, making it available in the REST API.
    register_meta('post', 'featuredprofessor', array(
      'show_in_rest' => true, // Enables metadata in the REST API.
      'type' => 'number', // Specifies metadata as a number.
      'single' => false // Allows multiple values for this metadata field.
    ));

    // Registers JavaScript and CSS files for the block editor.
    wp_register_script('featuredProfessorScript', plugin_dir_url(__FILE__) . 'build/index.js', array('wp-blocks', 'wp-i18n', 'wp-editor'));
    wp_register_style('featuredProfessorStyle', plugin_dir_url(__FILE__) . 'build/index.css');

    // Sets up translation files for the registered script.
    wp_set_script_translations('featuredProfessorScript', 'featured-professor', plugin_dir_path(__FILE__) . '/languages');

    // Registers the 'Featured Professor' block type with render callback for dynamic rendering.
    register_block_type('ourplugin/featured-professor', array(
      'render_callback' => [$this, 'renderCallback'], // Specifies the server-side rendering function.
      'editor_script' => 'featuredProfessorScript', // Enqueues block editor JavaScript file.
      'editor_style' => 'featuredProfessorStyle' // Enqueues block editor CSS file.
    ));
  }

  // Callback function for dynamically rendering the block content on the frontend.
  function renderCallback($attributes)
  {
    // Checks if 'profId' is provided in block attributes, enqueues styles, and generates HTML.
    if ($attributes['profId']) {
      wp_enqueue_style('featuredProfessorStyle'); // Enqueues block's frontend styles.
      return generateProfessorHTML($attributes['profId']); // Generates and returns professor HTML based on ID.
    } else {
      return NULL; // Returns null if 'profId' is missing.
    }
  }
}

// Instantiates the FeaturedProfessor class to enable the plugin's functionality.
$featuredProfessor = new FeaturedProfessor();

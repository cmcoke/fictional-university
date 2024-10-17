<?php

/*
  Plugin Name: Fictional Word Count
  Description: This plugin adds a Word Count and Read Time feature to WordPress posts. It allows users to display post statistics such as word count, character count, and estimated reading time. These settings can be customized through the WordPress admin dashboard. The plugin also supports internationalization (i18n) for translating text into different languages.
  Version: 1.0
  Author: Charles Coke
  Author URI: https://www.charlescoke.com/
  Text Domain: wcpdomain
  Domain Path: /languages
*/


class WordCountAndTimePlugin
{

  // Constructor function that sets up hooks for actions and filters
  function __construct()
  {
    // Add a new menu page in the admin dashboard for plugin settings
    add_action('admin_menu', array($this, 'adminPage'));

    // Initialize plugin settings on the admin_init hook
    add_action('admin_init', array($this, 'settings'));

    // Filter the content of single posts to include word count and read time statistics
    add_filter('the_content', array($this, 'ifWrap'));

    // Load plugin textdomain for internationalization (i18n) on the init hook
    add_action('init', array($this, 'languages'));
  }


  // Function to add a new options page for the plugin in the admin dashboard
  function adminPage()
  {
    add_options_page('Word Count Settings', __('Word Count', 'wcpdomain'), 'manage_options', 'word-count-settings-page', array($this, 'ourHTML'));
  }


  // Function to register settings for the plugin
  function settings()
  {

    // Add a new section to the settings page
    add_settings_section('wcp_first_section', null, null, 'word-count-settings-page');

    // Register and add the setting for display location (beginning or end of post)
    add_settings_field('wcp_location', 'Display Location', array($this, 'locationHTML'), 'word-count-settings-page', 'wcp_first_section');
    register_setting('wordcountplugin', 'wcp_location', array('sanitize_callback' =>  array($this, 'sanitizeLocation'), 'default' => '0'));

    // Register and add the setting for the headline text
    add_settings_field('wcp_headline', 'Headline Text', array($this, 'headlineHTML'), 'word-count-settings-page', 'wcp_first_section');
    register_setting('wordcountplugin', 'wcp_headline', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Statistics'));

    // Register and add the setting for word count display
    add_settings_field('wcp_wordcount', 'Word Count', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_wordcount'));
    register_setting('wordcountplugin', 'wcp_wordcount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

    // Register and add the setting for character count display
    add_settings_field('wcp_charactercount', 'Character Count', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_charactercount'));
    register_setting('wordcountplugin', 'wcp_charactercount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

    // Register and add the setting for read time display
    add_settings_field('wcp_readtime', 'Read Time', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_readtime'));
    register_setting('wordcountplugin', 'wcp_readtime', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
  }


  // Function to generate the HTML for the display location dropdown
  function locationHTML()
  { ?>
    <select name="wcp_location">
      <option value="0" <?php selected(get_option('wcp_location'), '0') ?>>Beginning of post</option>
      <option value="1" <?php selected(get_option('wcp_location'), '1') ?>>End of post</option>
    </select>
  <?php }


  // Function to sanitize the input for display location dropdown
  function sanitizeLocation($input)
  {
    // Ensure the input is either '0' (beginning) or '1' (end)
    if ($input != '0' && $input != '1') {
      add_settings_error('wcp_location', 'wcp_location_error', 'Display location must be either beginning or end.');
      return get_option('wcp_location');
    }
    return $input;
  }


  // Function to generate the HTML for the headline input field
  function headlineHTML()
  { ?>
    <input type="text" name="wcp_headline" value="<?php echo esc_attr(get_option('wcp_headline')) ?>">
  <?php }



  // Reusable function for generating checkbox input fields
  function checkboxHTML($args)
  { ?>
    <input type="checkbox" name="<?php echo $args['theName'] ?>" value="1"
      <?php checked(get_option($args['theName']), '1') ?>>
  <?php }


  // Conditional function to check if the statistics should be displayed
  function ifWrap($content)
  {
    // Check if it's the main query, a single post, and if at least one setting is enabled
    if (
      is_main_query() && is_single() &&
      (
        get_option('wcp_wordcount', '1') || // Check if word count display is enabled
        get_option('wcp_charactercount', '1') || // Check if character count display is enabled
        get_option('wcp_readtime', '1') // Check if read time display is enabled
      )
    ) {
      // If the conditions are met, call the function to generate the statistics HTML
      return $this->createHTML($content);
    }
    // Return the original content if conditions are not met
    return $content;
  }


  // Function to generate the HTML for post statistics
  function createHTML($content)
  {
    // Create the heading for the statistics block
    $html = '<h3>' . esc_html(get_option('wcp_headline', 'Post Statistics')) . '</h3><p>';

    // Calculate the word count if either word count or read time is enabled
    if (get_option('wcp_wordcount', '1') || get_option('wcp_readtime', '1')) {
      $wordCount = str_word_count(strip_tags($content)); // Strip HTML tags and count words
    }

    // Add the word count to the HTML if the setting is enabled
    if (get_option('wcp_wordcount', '1')) {
      $html .= esc_html__('This post has', 'wcpdomain') . ' ' . $wordCount . ' ' . esc_html__('words', 'wcpdomain') . '.<br>';
    }

    // Add the character count to the HTML if the setting is enabled
    if (get_option('wcp_charactercount', '1')) {
      $html .= 'This post has ' . strlen(strip_tags($content)) . ' characters.<br>';
    }

    // Calculate and add the estimated reading time to the HTML if the setting is enabled
    if (get_option('wcp_readtime', '1')) {
      $html .= 'This post will take about ' . round($wordCount / 225) . ' minute(s) to read.<br>';
    }

    // Close the statistics block
    $html .= '</p>';

    // Determine where to display the statistics: beginning or end of the post
    if (get_option('wcp_location', '0') == '0') {
      return $html . $content; // Display at the beginning
    }
    return $content . $html; // Display at the end
  }


  // Function to load the plugin's text domain for translation (i18n)
  function languages()
  {
    load_plugin_textdomain('wcpdomain', false, dirname(plugin_basename(__FILE__)) . '/languages');
  }


  // Function to generate the HTML for the plugin's settings page
  function ourHTML()
  { ?>

    <div class="wrap">
      <h1>Word Count Settings</h1>
      <form action="options.php" method="POST">
        <?php
        settings_fields('wordcountplugin'); // Output security fields for the settings page
        do_settings_sections('word-count-settings-page'); // Display the settings sections
        submit_button(); // Display the submit button
        ?>
      </form>
    </div>

<?php }
}


// Instantiate the plugin class
$wordCountAndTimePlugin = new WordCountAndTimePlugin();

<?php

/*
  Plugin Name: Fictional Word Filter
  Description: This plugin allows administrators to filter specific words from the site's content. It provides a settings page where the admin can enter a comma-separated list of words to filter. These words will be replaced with a specified string or removed from the site's content.
  Version: 1.0
  Author: Charles Coke
  Author URI: https://www.charlescoke.com/
*/

// Ensures the file is not accessed directly
if (!defined('ABSPATH')) exit;

class WordFilterPlugin
{
  function __construct()
  {
    // Hook to add the plugin's admin menu page
    add_action('admin_menu', array($this, 'newMenu'));

    // Hook to initialize settings and options for the plugin
    add_action('admin_init', array($this, 'settings'));

    // Adds a filter to modify the content if words to filter are set
    if (get_option('plugin_words_to_filter')) add_filter('the_content', array($this, 'filterLogic'));
  }

  function newMenu()
  {

    /* 
      uses the 'dashicons-smiley' icon for the plugin
    */
    // $mainPageHook = add_menu_page('Words To Filter', 'Word Filter', 'manage_options', 'ourwordfilter', array($this, 'wordFilterPage'), 'dashicons-smiley', 100);

    /*
      uses the colored version of the svg by using plugin_dir_url(__FILE__) . 'custom.svg'. custom.svg is the name of the svg file for this plugin and it is located in the same folder as this plugin
    */
    // $mainPageHook = add_menu_page('Words To Filter', 'Word Filter', 'manage_options', 'ourwordfilter', array($this, 'wordFilterPage'), plugin_dir_url(__FILE__) . 'custom.svg', 100);

    /*
      uses 'data:image/svg+xml;base64 ... ' for the icon. 'PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXd ..... ' is arrived from copying and pasting the custom.svg HTML syntax in the btoa(``) function in Chrome console bar
    */
    $mainPageHook = add_menu_page('Words To Filter', 'Word Filter', 'manage_options', 'ourwordfilter', array($this, 'wordFilterPage'), 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMCAyMEMxNS41MjI5IDIwIDIwIDE1LjUyMjkgMjAgMTBDMjAgNC40NzcxNCAxNS41MjI5IDAgMTAgMEM0LjQ3NzE0IDAgMCA0LjQ3NzE0IDAgMTBDMCAxNS41MjI5IDQuNDc3MTQgMjAgMTAgMjBaTTExLjk5IDcuNDQ2NjZMMTAuMDc4MSAxLjU2MjVMOC4xNjYyNiA3LjQ0NjY2SDEuOTc5MjhMNi45ODQ2NSAxMS4wODMzTDUuMDcyNzUgMTYuOTY3NEwxMC4wNzgxIDEzLjMzMDhMMTUuMDgzNSAxNi45Njc0TDEzLjE3MTYgMTEuMDgzM0wxOC4xNzcgNy40NDY2NkgxMS45OVoiIGZpbGw9IiNGRkRGOEQiLz4KPC9zdmc+Cg==', 100);

    // Adds a submenu under the main menu for managing words to filter
    add_submenu_page('ourwordfilter', 'Word To Filter', 'Words List', 'manage_options', 'ourwordfilter', array($this, 'wordFilterPage'));

    // Adds another submenu for plugin options
    add_submenu_page('ourwordfilter', 'Word Filter Options', 'Options', 'manage_options', 'word-filter-options', array($this, 'optionsSubPage'));

    // Hook to load assets when the main page is loaded
    add_action("load-{$mainPageHook}", array($this, 'mainPageAssets'));
  }

  // Renders the main page content for managing the words to filter
  function wordFilterPage()
  { ?>
    <div class="wrap">
      <h1>Word Filter</h1>
      <!-- Check if the form was submitted and handle it -->
      <?php if (isset($_POST['justsubmitted']) == "true") $this->handleForm(); ?>
      <form method="POST">
        <input type="hidden" name="justsubmitted" value="true">
        <?php wp_nonce_field('saveFilterWords', 'ourNonce'); ?>
        <label for="plugin_words_to_filter">
          <p>Enter a <strong>comma-separated</strong> list of words to filter from your site's content.</p>
        </label>
        <div class="word-filter__flex-container">
          <textarea name="plugin_words_to_filter" id="plugin_words_to_filter"
            placeholder="bad, mean, awful, horrible"><?php echo esc_textarea(get_option('plugin_words_to_filter')); ?></textarea>
        </div>
        <input type="submit" name="submit" id="submit" value="Save Changes" class="button button-primary">
      </form>
    </div>
    <?php }

  // Enqueues styles for the admin page
  function mainPageAssets()
  {
    // Enqueues the admin-specific stylesheet for the plugin
    wp_enqueue_style('filterAdminCss', plugin_dir_url(__FILE__) . 'styles.css');
  }

  // Handles the form submission to save the filtered words
  function handleForm()
  {
    // Verifies the nonce and checks user permissions before saving
    if (wp_verify_nonce($_POST['ourNonce'], 'saveFilterWords') && current_user_can('manage_options')) {

      // Updates the option with sanitized input from the form
      update_option('plugin_words_to_filter', sanitize_text_field($_POST['plugin_words_to_filter'])); ?>

      <div class="updated">
        <p>Your filtered words were saved.</p>
      </div>

    <?php } else { ?>

      <div class="error">
        <p>Sorry, you do not have permission to perform that action.</p>
      </div>

    <?php }
  }

  // Logic to filter content based on the saved list of words
  function filterLogic($content)
  {
    // Retrieves the list of words to filter from options
    $badWords = explode(',', get_option('plugin_words_to_filter'));

    // Trims any spaces from the words
    $badWordsTrimmed = array_map('trim', $badWords);

    // Replaces the filtered words with the replacement text or removes them
    return str_ireplace($badWordsTrimmed, esc_html(get_option('replacementText'), '****'), $content);
  }

  // Initializes the settings section and registers fields for the options page
  function settings()
  {
    // Adds a settings section for the replacement text
    add_settings_section('replacement-text-section', null, null, 'word-filter-options');

    // Registers the setting for the replacement text
    register_setting('replacementFields', 'replacementText');

    // Adds the replacement text field to the options page
    add_settings_field('replacement-text', 'Filtered Text', array($this, 'replacementFieldHTML'), 'word-filter-options', 'replacement-text-section');
  }

  // HTML for the replacement text field
  function replacementFieldHTML()
  { ?>
    <input type="text" name="replacementText" value="<?php echo esc_attr(get_option('replacementText', '***')); ?>">
    <p class="description">Leave blank to simply remove filtered words.</p>
  <?php }

  // Renders the options page where the replacement text can be set
  function optionsSubPage()
  { ?>
    <div class="wrap">
      <h1>Word Filter Option</h1>
      <!-- Displays the settings form -->
      <form action="options.php" method="POST">
        <?php
        settings_errors(); // Displays settings errors, if any
        settings_fields('replacementFields'); // Outputs the hidden fields for the settings
        do_settings_sections('word-filter-options'); // Outputs the settings sections
        submit_button(); // Outputs the submit button
        ?>
      </form>
    </div>
<?php }
}

// Instantiates the plugin class to initialize the plugin
$wordFilterPlugin = new WordFilterPlugin();

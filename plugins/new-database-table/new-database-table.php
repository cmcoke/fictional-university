<?php

/**
 * Pet Adoption Plugin: Creates a new database table for managing pet adoption entries.
 * Provides functionality for adding and deleting pet records, with the necessary table schema.
 * For more details on creating tables with plugins, refer to the WordPress guide:
 * https://developer.wordpress.org/plugins/creating-tables-with-plugins/
 */


/*
  Plugin Name: Pet Adoption (New DB Table)
  Version: 1.0
  Author: Charles-Michael
  Author URI: https://www.cmcoke.com
*/

if (! defined('ABSPATH')) exit; // Exit if accessed directly for security

require_once plugin_dir_path(__FILE__) . 'inc/generatePet.php'; // Import generatePet function

// Define the main plugin class
class PetAdoptionTablePlugin
{
  // Constructor method
  function __construct()
  {
    global $wpdb; // WordPress database global object
    $this->charset = $wpdb->get_charset_collate(); // Set charset for table
    $this->tablename = $wpdb->prefix . "pets"; // Define the table name with prefix

    // Register hooks
    add_action('activate_new-database-table/new-database-table.php', array($this, 'onActivate')); // Run on plugin activation
    // add_action('admin_head', array($this, 'populateFast')); // Optional, bulk populate for testing
    add_action('admin_post_createpet', array($this, 'createPet')); // Handle form submission for creating a pet (logged in users)
    add_action('admin_post_nopriv_createpet', array($this, 'createPet')); // Handle form submission for creating a pet (non-logged in users)
    add_action('admin_post_deletepet', array($this, 'deletePet')); // Handle form submission for deleting a pet (logged in users)
    add_action('admin_post_nopriv_deletepet', array($this, 'deletePet')); // Handle form submission for deleting a pet (non-logged in users)
    add_action('wp_enqueue_scripts', array($this, 'loadAssets')); // Load CSS for the plugin
    add_filter('template_include', array($this, 'loadTemplate'), 99); // Use custom template for pet-adoption page
  }

  // Delete pet record based on ID from form input
  function deletePet()
  {
    // Check if user has admin permissions
    if (current_user_can('administrator')) {
      $id = sanitize_text_field($_POST['idtodelete']); // Sanitize the ID to delete
      global $wpdb; // Access the database
      $wpdb->delete($this->tablename, array('id' => $id)); // Delete the pet record by ID
      wp_safe_redirect(site_url('/pet-adoption')); // Redirect to pet-adoption page
    } else {
      wp_safe_redirect(site_url()); // Redirect to home page if unauthorized
    }
    exit; // Exit to prevent further execution
  }

  // Create a new pet record in the database
  function createPet()
  {
    // Ensure the user has admin privileges
    if (current_user_can('administrator')) {
      $pet = generatePet(); // Generate a pet with default properties
      $pet['petname'] = sanitize_text_field($_POST['incomingpetname']); // Get pet name from form input
      global $wpdb; // Access the database
      $wpdb->insert($this->tablename, $pet); // Insert new pet record
      wp_safe_redirect(site_url('/pet-adoption')); // Redirect to pet-adoption page
    } else {
      wp_safe_redirect(site_url()); // Redirect to home page if unauthorized
    }
    exit; // Exit after redirection
  }

  // Create custom database table on plugin activation
  function onActivate()
  {
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); // Include upgrade.php for dbDelta function

    // Create the pets table with dbDelta
    dbDelta("CREATE TABLE $this->tablename (
      id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      birthyear smallint(5) NOT NULL DEFAULT 0,
      petweight smallint(5) NOT NULL DEFAULT 0,
      favfood varchar(60) NOT NULL DEFAULT '',
      favhobby varchar(60) NOT NULL DEFAULT '',
      favcolor varchar(60) NOT NULL DEFAULT '',
      petname varchar(60) NOT NULL DEFAULT '',
      species varchar(60) NOT NULL DEFAULT '',
      PRIMARY KEY  (id)
    ) $this->charset;");
  }

  // Insert a pet record (used for testing or demo purposes)
  function onAdminRefresh()
  {
    global $wpdb; // Access the database
    $wpdb->insert($this->tablename, generatePet()); // Insert a randomly generated pet
  }

  // Enqueue CSS for styling on the pet-adoption page
  function loadAssets()
  {
    // Check if we are on the pet-adoption page
    if (is_page('pet-adoption')) {
      wp_enqueue_style('petadoptioncss', plugin_dir_url(__FILE__) . 'pet-adoption.css'); // Enqueue the CSS file
    }
  }

  // Load custom template for the pet-adoption page
  function loadTemplate($template)
  {
    // Check if we are on the pet-adoption page
    if (is_page('pet-adoption')) {
      return plugin_dir_path(__FILE__) . 'inc/template-pets.php'; // Use custom template for pet-adoption page
    }
    return $template; // Return default template if not on pet-adoption page
  }

  // Quickly populate the table with multiple pet records for testing
  function populateFast()
  {
    $query = "INSERT INTO $this->tablename (`species`, `birthyear`, `petweight`, `favfood`, `favhobby`, `favcolor`, `petname`) VALUES "; // Define insert query
    $numberofpets = 100000; // Number of pets to insert

    // Loop to add each pet's values to query
    for ($i = 0; $i < $numberofpets; $i++) {
      $pet = generatePet(); // Generate a random pet
      $query .= "('{$pet['species']}', {$pet['birthyear']}, {$pet['petweight']}, '{$pet['favfood']}', '{$pet['favhobby']}', '{$pet['favcolor']}', '{$pet['petname']}')"; // Add pet values

      // Append comma between entries except for the last one
      if ($i != $numberofpets - 1) {
        $query .= ", "; // Add comma separator
      }
    }

    /*
    Never use query directly like this without using $wpdb->prepare in the
    real world. I'm only using it this way here because the values I'm 
    inserting are coming fromy my innocent pet generator function so I
    know they are not malicious, and I simply want this example script
    to execute as quickly as possible and not use too much memory.
    */
    global $wpdb; // Access the database
    $wpdb->query($query); // Execute the bulk insert query
  }
}

$petAdoptionTablePlugin = new PetAdoptionTablePlugin(); // Instantiate the plugin class
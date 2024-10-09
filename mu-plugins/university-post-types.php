<?php

/**
 * Reference links:
 * 1. Information about - register_post_type() - https://developer.wordpress.org/reference/functions/register_post_type/
 * 2. Information about the various 'labels' key-value pairs - https://developer.wordpress.org/reference/functions/get_post_type_labels/
 * 3. Information about the various WordPress Dashicons that can be used for the 'menu_icon' key in line 16 - https://developer.wordpress.org/resource/dashicons/#controls-volumeon
 */

function university_post_types()
{
  // Register a custom post type named 'event'
  register_post_type('event', array(
    'capability_type' => 'event', // Custom capability for managing 'event' posts
    'map_meta_cap' => true, // Map meta capabilities (such as edit_post, delete_post) to custom capabilities
    'public' => true, // Make the custom post type publicly accessible
    'supports' => array('title', 'editor', 'excerpt'), // Enable support for the title, editor, and excerpt fields
    'rewrite' => array('slug' => 'events'), // Set a custom URL slug for the post type archive and single pages
    'has_archive' => true, // Enable an archive page for the custom post type
    'show_in_rest' => true, // Enable the custom post type to be accessible via the WordPress REST API
    'menu_icon' => 'dashicons-calendar', // Set the icon for the post type in the WordPress admin menu
    'labels' => array(
      'name' => 'Events', // Display name for the post type in plural form
      'add_new_item' => 'Add New Event', // Label for the "Add New" page title
      'edit_item' => 'Edit Event', // Label for the "Edit" page title
      'all_items' => 'All Events', // Label for the menu item in the admin dashboard
      'singular_name' => 'Event' // Singular label for the post type
    ),
  ));


  // Register a custom post type named 'program'
  register_post_type('program', array(
    'public' => true, // Make the custom post type publicly accessible
    'supports' => array('title'), // Enable support for the title
    'rewrite' => array('slug' => 'programs'), // Set a custom URL slug for the post type archive and single pages
    'has_archive' => true, // Enable an archive page for the custom post type
    'show_in_rest' => true, // Enable the custom post type to be accessible via the WordPress REST API
    'menu_icon' => 'dashicons-awards', // Set the icon for the post type in the WordPress admin menu
    'labels' => array(
      'name' => 'Programs', // Display name for the post type in plural form
      'add_new_item' => 'Add New Program', // Label for the "Add New" page title
      'edit_item' => 'Edit Program', // Label for the "Edit" page title
      'all_items' => 'All Programs', // Label for the menu item in the admin dashboard
      'singular_name' => 'Program' // Singular label for the post type
    ),
  ));


  // Register a custom post type named 'professor'
  register_post_type('professor', array(
    'public' => true, // Make the custom post type publicly accessible
    'supports' => array('title', 'editor', 'thumbnail'), // Enable support for the title, editor, and post thumbnail (featured image)
    'show_in_rest' => true, // Enable the custom post type to be accessible via the WordPress REST API
    'menu_icon' => 'dashicons-welcome-learn-more', // Set the icon for the post type in the WordPress admin menu
    'labels' => array(
      'name' => 'Professors', // Display name for the post type in plural form
      'add_new_item' => 'Add New Professor', // Label for the "Add New" page title
      'edit_item' => 'Edit Professor', // Label for the "Edit" page title
      'all_items' => 'All Professors', // Label for the menu item in the admin dashboard
      'singular_name' => 'Professor' // Singular label for the post type
    ),
  ));


  // Register a custom post type named 'campus'
  register_post_type('campus', array(
    'capability_type' => 'campus', // Custom capability for managing 'campus' posts
    'map_meta_cap' => true, // Map meta capabilities to custom capabilities
    'public' => true, // Make the custom post type publicly accessible
    'supports' => array('title', 'editor', 'excerpt'), // Enable support for the title, editor, and excerpt fields
    'rewrite' => array('slug' => 'campuses'), // Set a custom URL slug for the post type archive and single pages
    'has_archive' => true, // Enable an archive page for the custom post type
    'show_in_rest' => true, // Enable the custom post type to be accessible via the WordPress REST API
    'menu_icon' => 'dashicons-location-alt', // Set the icon for the post type in the WordPress admin menu
    'labels' => array(
      'name' => 'Campuses', // Display name for the post type in plural form
      'add_new_item' => 'Add New Campus', // Label for the "Add New" page title
      'edit_item' => 'Edit Campus', // Label for the "Edit" page title
      'all_items' => 'All Campuses', // Label for the menu item in the admin dashboard
      'singular_name' => 'Campus' // Singular label for the post type
    ),
  ));


  // Register a custom post type named 'note'
  register_post_type('note', array(
    'public' => false, // Set 'public' to false to keep notes from appearing on the front end of the site or in search results
    'show_ui' => true, // Show the custom post type in the WordPress admin dashboard
    'capability_type' => 'note', // Use a custom capability type for 'note' to handle permissions for creating, editing, and deleting notes
    'map_meta_cap' => true, // Enable WordPress’s built-in meta capabilities to manage permissions more easily
    'supports' => array('title', 'editor'), // Limit the supported fields to 'title' and 'editor' (i.e., no featured images or other meta fields)
    'show_in_rest' => true, // Expose this custom post type to the REST API for integration with front-end requests
    'menu_icon' => 'dashicons-welcome-write-blog', // Set a custom icon for the post type in the admin menu

    // Customize the labels displayed in the WordPress admin dashboard
    'labels' => array(
      'name' => 'Notes', // The plural name for the post type
      'add_new_item' => 'Add New Note', // The label for adding a new note
      'edit_item' => 'Edit Note', // The label for editing a note
      'all_items' => 'All Notes', // The label for viewing all notes in the admin dashboard
      'singular_name' => 'Note' // The singular form of the post type name
    ),
  ));
}

// Hook the custom post type registration into the 'init' action
add_action('init', 'university_post_types');
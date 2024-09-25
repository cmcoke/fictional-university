<?php

// Function to enqueue theme scripts and styles
function university_files()
{
  // Enqueue the main JavaScript file, which depends on jQuery, with version 1.0 and loaded in the footer
  wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);

  // Enqueue Google Fonts with specific font weights and styles for Roboto and Roboto Condensed
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');

  // Enqueue Font Awesome for adding icons throughout the theme
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

  // Enqueue the main theme styles
  wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));

  // Enqueue additional theme styles
  wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
}

// Hook the 'university_files' function to the 'wp_enqueue_scripts' action to load scripts and styles
add_action('wp_enqueue_scripts', 'university_files');



/**************************************************************************************************************/



// Function to add theme features
function university_features()
{
  // Enable support for dynamic title tags in the theme
  add_theme_support('title-tag');

  // Enable support for featured images (post thumbnails) in the theme
  add_theme_support('post-thumbnails');

  // Define a custom image size named 'professorLandscape' with dimensions 400x260 pixels, cropped to fit
  add_image_size('professorLandscape', 400, 260, true);

  // Define a custom image size named 'professorPortrait' with dimensions 480x650 pixels, cropped to fit
  add_image_size('professorPortrait', 480, 650, true);

  // Define a custom image size named 'pageBanner' with dimensions 1500x350 pixels, cropped to fit
  add_image_size('pageBanner', 1500, 350, true);
}

// Hook the 'university_features' function to the 'after_setup_theme' action, which runs after the theme is initialized
add_action('after_setup_theme', 'university_features');




/**************************************************************************************************************/

/**
 * 
 * This code modifies the main query for the 'event' custom post type archive page to display events in ascending order by date. 
 * It ensures that only future or current events are shown, based on the event_date custom field.
 * 
 */

// Function to modify the main WordPress query for the 'event' custom post type archive page
function university_adjust_queries($query)
{

  // Check if not in the admin dashboard, if viewing the 'program' custom post type archive, and if it's the main query
  if (!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
    $query->set('orderby', 'title');  // Set the query to order posts by their title
    $query->set('order', 'ASC'); // Set the order of the posts to ascending (A-Z)
    $query->set('posts_per_page', -1); // Display all posts without pagination (-1 means no limit)
  }


  // Check if not in the admin dashboard, if viewing the 'event' post type archive, and if it's the main query
  if (!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
    $today = date('Ymd'); // Get today's date in 'YYYYMMDD' format

    // Modify the query parameters to order the events by their 'event_date' custom field
    $query->set('meta_key', 'event_date'); // Specify the custom field to use for ordering
    $query->set('orderby', 'meta_value_num'); // Order by the numeric value of the custom field
    $query->set('order', 'ASC'); // Set the order to ascending, so the earliest events appear first

    // Set up a meta query to only include events with a date greater than or equal to today
    $query->set('meta_query', array(
      array(
        'key' => 'event_date', // The custom field that holds the event date
        'compare' => '>=', // Only include events with dates greater than or equal to today
        'value' => $today, // Today's date
        'type' => 'numeric' // Specify that the field is numeric for comparison
      )
    ));
  }
}

// Attach the 'university_adjust_queries' function to the 'pre_get_posts' action
add_action('pre_get_posts', 'university_adjust_queries');

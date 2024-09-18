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



// Function to add theme features
function university_features()
{
  // Enable support for dynamic title tags in the theme
  add_theme_support('title-tag');
}

// Hook the 'university_features' function to the 'after_setup_theme' action, which runs after the theme is initialized
add_action('after_setup_theme', 'university_features');

<?php

/**
 * This code registers a custom REST API route for search functionality in a WordPress theme. 
 * It defines a search route that allows for searching across various post types such as posts, 
 * pages, professors, programs, events, and campuses. The search term is sanitized, and results 
 * are categorized and returned based on post types.
 */

// Hook the function 'universityRegisterSearch' to 'rest_api_init' to register a custom REST API route
add_action('rest_api_init', 'universityRegisterSearch');

// Define the function that registers the custom search route
function universityRegisterSearch()
{
  // Register a new REST API route under 'university/v1/search' that accepts GET requests
  register_rest_route('university/v1', 'search', array(
    'methods' => WP_REST_SERVER::READABLE, // Set the route to respond to GET requests (READABLE)
    'callback' => 'universitySearchResults' // Specify the callback function to handle the search results
  ));
}

// Callback function that handles the search query and returns the results
function universitySearchResults($data)
{
  // Create a new WP_Query to search across multiple post types with the sanitized search term
  // $data contains the parameters passed to the REST API request. It includes values such as the search term.
  // $data['term'] refers to the 'term' parameter, which is the search query sent by the front-end user through the REST API.
  $mainQuery = new WP_Query(array(
    'post_type' => array('post', 'page', 'professor', 'program', 'event', 'campus'), // Post types to search
    's' => sanitize_text_field($data['term']) // 's' is the search parameter for WP_Query, which performs a keyword search using the sanitized term from $data['term'].
  ));

  // Initialize an empty array to store the search results categorized by post type
  $results = array(
    'generalInfo' => array(),
    'professors' => array(),
    'programs' => array(),
    'events' => array(),
    'campuses' => array(),
  );

  // Loop through each post returned by the search query
  while ($mainQuery->have_posts()) {

    $mainQuery->the_post(); // Set up post data for the current post

    // If the post type is 'post' or 'page', add the post to the 'generalInfo' array
    if (get_post_type() == 'post' || get_post_type() == 'page') {

      // Push the post's title and permalink into the 'generalInfo' results array
      array_push($results['generalInfo'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
      ));
    }

    // If the post type is 'professor', add the post to the 'professors' array
    if (get_post_type() == 'professor') {

      // Push the professor's title and permalink into the 'professors' results array
      array_push($results['professors'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
      ));
    }

    // If the post type is 'program', add the post to the 'programs' array
    if (get_post_type() == 'program') {

      // Push the program's title and permalink into the 'programs' results array
      array_push($results['programs'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
      ));
    }

    // If the post type is 'event', add the post to the 'events' array
    if (get_post_type() == 'event') {

      // Push the event's title and permalink into the 'events' results array
      array_push($results['events'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
      ));
    }

    // If the post type is 'campus', add the post to the 'campuses' array
    if (get_post_type() == 'campus') {

      // Push the campus's title and permalink into the 'campuses' results array
      array_push($results['campuses'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
      ));
    }
  }

  // Return the categorized search results to the REST API response
  return $results;
}

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
        'title' => get_the_title(), // Get the post's title
        'permalink' => get_the_permalink(), // Get the post's permalink
        'postType' => get_post_type(), // Get the post type ('post' or 'page')
        'authorName' => get_the_author() // Get the author's name
      ));
    }

    // If the post type is 'professor', add the post to the 'professors' array
    if (get_post_type() == 'professor') {
      // Push the professor's title, permalink, and image into the 'professors' results array
      array_push($results['professors'], array(
        'title' => get_the_title(), // Get the professor's title
        'permalink' => get_the_permalink(), // Get the professor's permalink
        'image' => get_the_post_thumbnail_url(0, 'professorLandscape') // Get the professor's featured image in 'professorLandscape' size
      ));
    }

    // If the post type is 'program', add the post to the 'programs' array
    if (get_post_type() == 'program') {
      // Get related campuses using a custom field (ACF)
      $relatedCampuses = get_field('related_campus');

      if ($relatedCampuses) {
        // Loop through each related campus and add to the 'campuses' array
        foreach ($relatedCampuses as $campus) {
          array_push($results['campuses'], array(
            'title' => get_the_title($campus), // Get the campus's title
            'permalink' => get_the_permalink($campus) // Get the campus's permalink
          ));
        }
      }

      // Push the program's title, permalink, and ID into the 'programs' results array
      array_push($results['programs'], array(
        'title' => get_the_title(), // Get the program's title
        'permalink' => get_the_permalink(), // Get the program's permalink
        'id' => get_the_ID(), // Get the program's ID
      ));
    }

    // If the post type is 'event', add the post to the 'events' array
    if (get_post_type() == 'event') {
      $eventDate = new DateTime(get_field('event_date')); // Get the event date using a custom field (ACF)

      // Check if the event has an excerpt, else trim the content
      if (has_excerpt()) {
        $description = get_the_excerpt(); // Get the excerpt if available
      } else {
        $description = wp_trim_words(get_the_content(), 18); // Trim the content to 18 words
      }

      // Push the event's title, permalink, month, day, and description into the 'events' results array
      array_push($results['events'], array(
        'title' => get_the_title(), // Get the event's title
        'permalink' => get_the_permalink(), // Get the event's permalink
        'month' => $eventDate->format('M'), // Format the event date to show the month
        'day' => $eventDate->format('d'), // Format the event date to show the day
        'description' => $description // Get the event description
      ));
    }

    // If the post type is 'campus', add the post to the 'campuses' array
    if (get_post_type() == 'campus') {
      // Push the campus's title and permalink into the 'campuses' results array
      array_push($results['campuses'], array(
        'title' => get_the_title(), // Get the campus's title
        'permalink' => get_the_permalink(), // Get the campus's permalink
      ));
    }
  }



  /**
   * If the search results contain programs, perform a secondary query to find related professors and events. 
   * This involves checking relationships between the programs and other post types (professors and events) 
   * and merging the new results into the existing search results array.
   */

  if ($results['programs']) {
    $programsMetaQuery = array('relation' => 'OR'); // Create an OR meta query to find related programs

    // Loop through each program and add to the meta query for related posts (professors or events)
    foreach ($results['programs'] as $item) {
      array_push($programsMetaQuery, array(
        'key' => 'related_programs', // Search the custom field 'related_programs'
        'compare' => 'LIKE', // Find any posts with the program's ID
        'value' => '"' . $item['id'] . '"' // Use the program's ID to match related posts
      ));
    }

    // Create a new WP_Query to find related professors or events based on the program
    $programRelationshipQuery = new WP_Query(array(
      'post_type' =>  array('professor', 'event'), // Search for professors and events
      'meta_query' => $programsMetaQuery // Use the meta query to find related posts
    ));

    // Loop through the related posts
    while ($programRelationshipQuery->have_posts()) {
      $programRelationshipQuery->the_post(); // Set up post data for the current post

      // If the related post is an event, add it to the 'events' array
      if (get_post_type() == 'event') {
        $eventDate = new DateTime(get_field('event_date')); // Get the event date

        // Check if the event has an excerpt, else trim the content
        if (has_excerpt()) {
          $description = get_the_excerpt(); // Get the excerpt if available
        } else {
          $description = wp_trim_words(get_the_content(), 18); // Trim the content to 18 words
        }

        // Push the related event's title, permalink, month, day, and description into the 'events' array
        array_push($results['events'], array(
          'title' => get_the_title(), // Get the event's title
          'permalink' => get_the_permalink(), // Get the event's permalink
          'month' => $eventDate->format('M'), // Format the event date to show the month
          'day' => $eventDate->format('d'), // Format the event date to show the day
          'description' => $description // Get the event description
        ));
      }

      // If the related post is a professor, add it to the 'professors' array
      if (get_post_type() == 'professor') {
        // Push the related professor's title, permalink, and image into the 'professors' array
        array_push($results['professors'], array(
          'title' => get_the_title(), // Get the professor's title
          'permalink' => get_the_permalink(), // Get the professor's permalink
          'image' => get_the_post_thumbnail_url(0, 'professorLandscape') // Get the professor's featured image in 'professorLandscape' size
        ));
      }
    }

    // Remove any duplicate professors from the 'professors' array.
    // The array_unique() function removes duplicate values based on the entire array content (SORT_REGULAR).
    // array_values() is used to re-index the array after removing duplicates.
    $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));

    // Remove any duplicate events from the 'events' array.
    // array_unique() removes duplicate values from the 'events' array using SORT_REGULAR to compare the entire content of each array element.
    // array_values() ensures the array is re-indexed correctly after duplicates are removed.
    $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
  }

  // Return the final array of results, categorized by post type
  return $results;
}

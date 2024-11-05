<?php

/**
 * 
 * This code registers two custom REST API routes in a WordPress site to handle the creation and deletion 
 * of "likes" for professor posts. The code ensures that only logged-in users can create or delete likes 
 * and validates the professor ID when creating a like. The REST API routes allow for the creation of a 
 * "like" (via a POST request) or the deletion of a "like" (via a DELETE request) based on user interaction.
 */

// Hook into 'rest_api_init' to register custom REST API routes
add_action('rest_api_init', 'universityLikeRoutes');

// Function to register custom REST API routes for managing likes
function universityLikeRoutes()
{
  // Register a POST route for creating a like
  register_rest_route('university/v1', 'manageLike', array(
    'methods' => 'POST', // Specify that this route accepts POST requests
    'callback' => 'createLike' // Function to handle the creation of a like
  ));

  // Register a DELETE route for deleting a like
  register_rest_route('university/v1', 'manageLike', array(
    'methods' => 'DELETE', // Specify that this route accepts DELETE requests
    'callback' => 'deleteLike' // Function to handle the deletion of a like
  ));
}

// Function to handle the creation of a like
function createLike($data)
{
  // Check if the user is logged in before allowing them to create a like
  if (is_user_logged_in()) {
    // Sanitize and retrieve the professor ID from the request data
    $professor = sanitize_text_field($data['professorId']);

    // Query the database to check if the user has already liked this professor
    $existQuery = new WP_Query(array(
      'author' => get_current_user_id(), // Query posts by the current logged-in user
      'post_type' => 'like', // Query only posts of type 'like'
      'meta_query' => array(
        array(
          'key' => 'liked_professor_id', // Meta key that stores the liked professor ID
          'compare' => '=', // Check if the value matches
          'value' => $professor // The professor ID to check against
        )
      )
    ));

    // If the user hasn't already liked the professor and the professor ID is valid
    if ($existQuery->found_posts == 0 and get_post_type($professor) == 'professor') {
      // Insert a new post of type 'like' to register the like
      return wp_insert_post(array(
        'post_type' => 'like', // Set the post type as 'like'
        'post_status' => 'publish', // Publish the like post
        'post_title' => '2nd PHP Test', // Placeholder title (could be dynamic)
        'meta_input' => array(
          'liked_professor_id' => $professor // Store the liked professor's ID in post metadata
        )
      ));
    } else {
      // If the professor ID is invalid or the user has already liked this professor, stop the process
      die("Invalid professor id");
    }
  } else {
    // If the user is not logged in, deny the like creation
    die("Only logged in users can create a like.");
  }
}

// Function to handle the deletion of a like
function deleteLike($data)
{
  // Sanitize and retrieve the like ID from the request data
  $likeId = sanitize_text_field($data['like']);

  // Check if the current user is the author of the like and if the post type is 'like'
  if (get_current_user_id() == get_post_field('post_author', $likeId) and get_post_type($likeId) == 'like') {
    // Delete the like post permanently (force deletion)
    wp_delete_post($likeId, true);
    return 'Congrats, like deleted.';
  } else {
    // If the user doesn't have permission to delete the like, stop the process
    die("You do not have permission to delete that.");
  }
}
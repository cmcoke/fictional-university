<?php

/**
 * This PHP function generates HTML for displaying a list of related posts that mention
 * a specified professor, identified by post ID, in a WordPress theme. The function uses
 * WP_Query to retrieve posts that reference the professor through a custom field, and
 * then outputs a list of links to each related post.
 */

function relatedPostsHTML($id)
{
  // Sets up a custom WP_Query to retrieve posts related to the specified professor.
  $postsAboutThisProf = new WP_Query(array(
    'posts_per_page' => -1, // Retrieves all posts without a limit.
    'post_type' => 'post', // Specifies the post type as 'post'.
    'meta_query' => array( // Filters posts by a custom field value.
      array(
        'key' => 'featuredprofessor', // Custom field key to check.
        'compare' => '=', // Ensures the value matches exactly.
        'value' => $id // Checks if the custom field value matches the professor's ID.
      )
    )
  ));

  // Begin output buffering to capture HTML content.
  ob_start();

  // Checks if any related posts were found for the specified professor.
  if ($postsAboutThisProf->found_posts) { ?>
    <!-- Outputs the professor's name and a heading for the related posts list -->
    <p><?php the_title(); ?> is mentioned in the following posts:</p>
    <ul>
      <?php
      // Loop through each related post to display links.
      while ($postsAboutThisProf->have_posts()) {
        $postsAboutThisProf->the_post(); // Sets up post data for each post in the loop. 
      ?>

        <!-- List item containing a link to the related post -->
        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
      <?php } ?>
    </ul>
<?php }

  // Reset the global post data to prevent conflicts.
  wp_reset_postdata();

  return ob_get_clean(); // Returns the buffered HTML as a string.
}

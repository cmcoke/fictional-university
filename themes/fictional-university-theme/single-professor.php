<?php

get_header();

// Start the loop to display the current post content
while (have_posts()) {
  // Prepare the post data
  the_post();

  // Display a custom page banner (custom function defined elsewhere)
  pageBanner();
?>

<div class="container container--narrow page-section">
  <div class="generic-content">
    <div class="row group">
      <div class="one-third">
        <?php
          // Display the featured image of the current post, using the 'professorPortrait' image size
          the_post_thumbnail('professorPortrait');
          ?>
      </div>
      <div class="two-thirds">
        <?php
          // Create a new WP_Query to count how many 'like' posts are associated with this professor
          $likeCount = new WP_Query(array(
            'post_type' => 'like', // Custom post type 'like'
            'meta_query' => array(
              array(
                'key' => 'liked_professor_id', // Custom field storing the liked professor's ID
                'compare' => '=',
                'value' => get_the_ID() // Get the current professor's post ID
              )
            )
          ));

          // Set the default like status to 'no'
          $existStatus = 'no';

          // Check if the user is logged in
          if (is_user_logged_in()) {
            // Query to check if the current user has already liked this professor
            $existQuery = new WP_Query(array(
              'author' => get_current_user_id(), // Limit to the current logged-in user
              'post_type' => 'like', // Custom post type 'like'
              'meta_query' => array(
                array(
                  'key' => 'liked_professor_id', // Custom field storing the liked professor's ID
                  'compare' => '=',
                  'value' => get_the_ID() // Get the current professor's post ID
                )
              )
            ));

            // If a like post exists for this user and professor, set the status to 'yes'
            if ($existQuery->found_posts) {
              $existStatus = 'yes';
            }
          }
          ?>

        <!-- HTML structure for the like button -->
        <span class="like-box"
          data-like="<?php if (isset($existQuery->posts[0]->ID)) echo $existQuery->posts[0]->ID; ?>"
          data-professor="<?php the_ID(); ?>" data-exists="<?php echo $existStatus; ?>">
          <!-- Outline heart icon (empty) -->
          <i class="fa fa-heart-o" aria-hidden="true"></i>
          <!-- Solid heart icon (liked) -->
          <i class="fa fa-heart" aria-hidden="true"></i>
          <!-- Display the total number of likes for this professor -->
          <span class="like-count"><?php echo $likeCount->found_posts; ?></span>
        </span>

        <!-- Display the main content of the post -->
        <?php the_content(); ?>
      </div>
    </div>
  </div>

  <?php
    // Get the related programs (custom field) associated with the current professor
    $relatedPrograms = get_field('related_programs');

    // If there are related programs, display them
    if ($relatedPrograms) {
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">Subject(s) Taught</h2>';
      echo '<ul class="link-list min-list">';

      // Loop through the related programs and display each one
      foreach ($relatedPrograms as $program) { ?>
  <li><a href="<?php echo get_the_permalink($program); ?>"><?php echo get_the_title($program); ?></a></li>
  <?php }
      // Close the unordered list
      echo '</ul>';
    }
    ?>
</div>

<?php }

get_footer();
?>
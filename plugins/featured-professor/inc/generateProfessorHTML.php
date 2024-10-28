<?php

/**
 * This PHP function generates HTML for displaying a "professor callout" section
 * in a WordPress theme. The function accepts a professor's post ID as a parameter,
 * retrieves the relevant post data using a custom WP_Query, and formats it with 
 * HTML to display the professor's image, name, a truncated description, 
 * related programs, and a link to the full professor profile.
 */

function generateProfessorHTML($id)
{
  // Set up a new WP_Query to retrieve the professor post with the given ID.
  $profPost = new WP_Query(array(
    'post_type' => 'professor', // Specifies the custom post type 'professor'.
    'p' => $id // Fetches the post with the specified ID.
  ));

  // Loop through the post(s) retrieved by WP_Query (though only one post is expected).
  while ($profPost->have_posts()) {
    $profPost->the_post(); // Sets up post data for use in template tags.

    // Begin output buffering to capture HTML content.
    ob_start();
?>

    <!-- HTML structure for the professor callout -->
    <div class="professor-callout">
      <!-- Professor photo section with inline background image using featured image URL -->
      <div class="professor-callout__photo"
        style="background-image: url(<?php the_post_thumbnail_url('professorPortrait'); ?>);"></div>

      <!-- Professor text section including name, excerpt, and related programs -->
      <div class="professor-callout__text">
        <h5><?php the_title(); ?></h5> <!-- Displays the professor's name -->
        <p><?php echo wp_trim_words(get_the_content(), 30); ?></p> <!-- Truncated content to 30 words -->

        <?php
        // Retrieves any related programs assigned to the professor via Advanced Custom Fields (ACF).
        $relatedPrograms = get_field('related_programs');

        // Checks if there are related programs and outputs them if available.
        if ($relatedPrograms) { ?>
          <p> <?php echo wp_strip_all_tags(get_the_title()); ?> teaches:
            <?php
            // Loops through each related program and displays its title.
            foreach ($relatedPrograms as $key => $program) {
              echo get_the_title($program); // Outputs program title.

              // Adds a comma between program titles if multiple programs are present.
              if ($key != array_key_last($relatedPrograms) && count($relatedPrograms) > 1) {
                echo ', ';
              }
            } ?>.
          </p>
        <?php } ?>

        <!-- Link to the professor's full profile page -->
        <p><strong><a href="<?php the_permalink(); ?>">Learn more about <?php the_title(); ?> &raquo;</a></strong></p>
      </div>
    </div>

<?php
    wp_reset_postdata(); // Resets the post data to avoid conflicts.

    return ob_get_clean(); // Returns the buffered HTML as a string.
  }
}

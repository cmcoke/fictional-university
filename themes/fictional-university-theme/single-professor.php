<?php

get_header();

// Start the loop to display the current post
while (have_posts()) {

  the_post();
  pageBanner(); // Display a custom page banner with the post title and any associated subtitle/background image
?>

<div class="container container--narrow page-section">

  <!-- Display the featured image of the professor and the post's text content -->
  <div class="generic-content">
    <div class="row group">
      <div class="one-third">
        <?php the_post_thumbnail('professorPortrait'); // Display the professor's portrait as the featured image ?>
      </div>
      <div class="two-thirds">
        <?php the_content(); // Display the main content of the post ?>
      </div>
    </div>
  </div>

  <!-- Display related program(s) for the professor -->
  <?php

    // Retrieve the 'related_programs' advanced custom field values for the current professor
    $relatedPrograms = get_field('related_programs');

    // Check if there are any related programs
    if ($relatedPrograms) {
      echo "<hr class='section-break'>"; // Display a horizontal line as a section break
      echo "<h2 class='headline headline--medium'>Subject(s) Taught</h2>"; // Display a heading for the programs the professor teaches
      echo "<ul class='link-list min-list'>"; // Start an unordered list

      // Loop through each related program
      foreach ($relatedPrograms as $program) { ?>
  <li>
    <!-- Display a link to the related program's page with its title -->
    <a href="<?php echo get_the_permalink($program); ?>"><?php echo get_the_title($program); ?></a>
  </li>
  <?php }

      echo "</ul>"; // Close the unordered list
    }

    ?>

</div>

<?php }

get_footer();

?>
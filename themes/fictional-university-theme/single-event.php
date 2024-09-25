<?php

get_header();

while (have_posts()) {

  the_post(); ?>

  <!-- Page banner section with background image and title -->
  <div class="page-banner">
    <div class="page-banner__bg-image"
      style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg') ?>)">
    </div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php the_title(); ?></h1>
      <div class="page-banner__intro">
        <p>DON'T FORGET TO REPLACE ME LATER.</p>
      </div>
    </div>
  </div>

  <div class="container container--narrow page-section">

    <!-- Meta box showing a link to the event archive page and the name of the event -->
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p>

        <!-- Link back to the event archive page -->
        <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event'); ?>">
          <i class="fa fa-home" aria-hidden="true"></i> Event Home
        </a>

        <!-- show the name of the event  -->
        <span class="metabox__main">
          <?php the_title(); ?>
        </span>

      </p>
    </div>

    <!-- The post's text content -->
    <div class="generic-content">
      <?php the_content(); ?>
    </div>

    <!-- Program(s) related to the event -->
    <?php

    // Retrieve the 'related_programs' advanced custom field values for the current event
    $relatedPrograms = get_field('related_programs');

    // Check if there are any related programs
    if ($relatedPrograms) {
      echo "<hr class='section-break'>"; // Display a horizontal line as a section break
      echo "<h2 class='headline headline--medium'>Related Program(s)</h2>"; // Display a heading for the related programs section
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
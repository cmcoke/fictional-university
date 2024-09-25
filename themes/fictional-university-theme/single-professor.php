<?php

get_header();

while (have_posts()) {

  the_post(); ?>

  <!-- Page banner section with background image and title -->
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(
      <?php
      $pageBannerImage = get_field('page_banner_background_image');
      echo $pageBannerImage['sizes']['pageBanner'];
      ?>)">
    </div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php the_title(); ?></h1>
      <div class="page-banner__intro">
        <p><?php the_field('page_banner_subtitle'); ?></p>
      </div>
    </div>
  </div>

  <div class="container container--narrow page-section">

    <!-- Display the featured image of the professor and the post's text content -->
    <div class="generic-content">
      <div class="row group">
        <div class="one-third">
          <?php the_post_thumbnail('professorPortrait'); ?>
        </div>
        <div class="two-thirds">
          <?php the_content(); ?>
        </div>
      </div>
    </div>

    <!-- Program(s) related to the professor-->
    <?php

    // Retrieve the 'related_programs' advanced custom field values for the current professor
    $relatedPrograms = get_field('related_programs');

    // Check if there are any related programs
    if ($relatedPrograms) {
      echo "<hr class='section-break'>"; // Display a horizontal line as a section break
      echo "<h2 class='headline headline--medium'>Subject(s) Taught</h2>"; // Display the programs taught by professor
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
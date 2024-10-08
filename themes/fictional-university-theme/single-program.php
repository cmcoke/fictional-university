<?php

get_header();

// Start the WordPress loop to iterate through posts
while (have_posts()) {

  the_post();
  pageBanner(); // Display a custom page banner with the post title and any associated subtitle/background image
?>

<div class="container container--narrow page-section">

  <!-- Meta box showing a link to the program archive page and displaying the program name -->
  <div class="metabox metabox--position-up metabox--with-home-link">
    <p>
      <!-- Link back to the program archive page -->
      <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>">
        <i class="fa fa-home" aria-hidden="true"></i> All Programs
      </a>

      <!-- Display the current program's name -->
      <span class="metabox__main">
        <?php the_title(); ?>
      </span>
    </p>
  </div>

  <!-- Display the main content of the post/page -->
  <div class="generic-content">
    <!-- Display the post/page content -->
    <?php the_field('main_body_content'); ?>
  </div>

  <?php
    /**
     * The code below creates a custom WordPress query to fetch "professor" posts related to the current program.
     * The posts are ordered alphabetically by title.
     */

    // Create a custom WP_Query to fetch related professors
    $relatedProfessors = new WP_Query(array(
      'posts_per_page' => -1, // Retrieve all matching posts
      'post_type' => 'professor', // Specify the custom post type as 'professor'
      'orderby' => 'title', // Order professors alphabetically by title
      'order' => 'ASC', // Arrange posts in ascending order
      // Define a meta query to find professors related to the current program
      'meta_query' => array(
        array(
          'key' => 'related_programs', // Use the 'related_programs' advanced custom field
          'compare' => 'LIKE', // Search for the current program's ID within the 'related_programs' field
          'value' => '"' . get_the_ID() . '"' // The current program's ID in double quotes for accurate LIKE comparison
        )
      )
    ));

    // Check if there are any professors that match the query
    if ($relatedProfessors->have_posts()) {

      echo "<hr class='section-break'>"; // Display a horizontal line as a section break
      echo '<h2 class="headline headline--medium">' . get_the_title() . ' Professors</h2>'; // Display a heading for related professors
      echo "<ul class='professor-cards'>"; // Start an unordered list with a custom class

      // Loop through each 'professor' post retrieved by WP_Query
      while ($relatedProfessors->have_posts()) {
        $relatedProfessors->the_post(); ?>

  <li class="professor-card__list-item">
    <!-- Display professor details with a link to their page -->
    <a class="professor-card" href="<?php the_permalink(); ?>">
      <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>">
      <!-- Display professor's image -->
      <span class="professor-card__name"><?php the_title(); ?></span> <!-- Display professor's name -->
    </a>
  </li>

  <?php }
      echo "</ul>"; // End the unordered list
    }

    // Reset the global post data to avoid conflicts with other queries
    wp_reset_postdata();

    /**
     * The code below creates a custom WordPress query to fetch upcoming "event" posts associated with the current program.
     * Events are filtered to show only those happening from today's date onwards, sorted in ascending order by 'event_date'.
     */

    // Get the current date in 'Ymd' format (e.g., 20240918 for September 18, 2024)
    $today = date('Ymd');

    // Create a new custom WordPress query to fetch upcoming events related to the current program
    $homepageEvents = new WP_Query(array(
      'posts_per_page' => 2,  // Limit the query to 2 posts
      'post_type' => 'event', // Specify the custom post type as 'event'
      'meta_key' => 'event_date', // Use the 'event_date' advanced custom field for sorting
      'orderby' => 'meta_value_num', // Order by the numeric value of 'event_date'
      'order' => 'ASC', // Arrange posts in ascending order (earliest to latest)

      // Define a meta query to filter events based on the date and related program
      'meta_query' => array(
        array(
          'key' => 'event_date', // Check against the 'event_date' custom field
          'compare' => '>=', // Include events that are on or after today's date
          'value' => $today, // Compare against today's date
          'type' => 'numeric' // Treat the 'event_date' field as a numeric value for comparison
        ),
        array(
          'key' => 'related_programs', // Check against the 'related_programs' advanced custom field
          'compare' => 'LIKE', // Find events where the related program includes the current program ID
          'value' => '"' . get_the_ID() . '"' // Use the current program's ID in the comparison
        )
      )
    ));

    // Check if there are any events that match the query
    if ($homepageEvents->have_posts()) {

      echo "<hr class='section-break'>"; // Display a horizontal line as a section break
      echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>'; // Display a heading for upcoming events

      // Loop through each 'event' post retrieved by WP_Query
      while ($homepageEvents->have_posts()) {
        $homepageEvents->the_post();
        get_template_part('/template-parts/content-event'); // Include the template part for displaying event content
      }
    }

    // Reset the global post data to avoid conflicts with other queries
    wp_reset_postdata();


    // Campuses that teach current program
    $relatedCampuses = get_field('related_campus');

    if ($relatedCampuses) {
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">' . get_the_title() .  ' is Available At Theses Campuses:</h2>';
      echo '<ul class="min-list link-list">';
      foreach ($relatedCampuses as $campus) { ?>
  <li>
    <a href="<?php echo get_the_permalink($campus); ?>"><?php echo get_the_title($campus); ?></a>
  </li>
  <?php }
      echo '</ul>';
    }

    ?>

</div>

<?php }

get_footer();

?>
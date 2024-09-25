<?php

get_header();

// Start the WordPress loop to iterate through posts
while (have_posts()) {

  the_post(); ?>

  <!-- Page banner section with background image and title -->
  <div class="page-banner">
    <div class="page-banner__bg-image"
      style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg') ?>)">
    </div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php the_title(); ?></h1> <!-- Display the current post/page title -->
      <div class="page-banner__intro">
        <p>DON'T FORGET TO REPLACE ME LATER.</p> <!-- Placeholder text for banner subtitle -->
      </div>
    </div>
  </div>

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
      <?php the_content(); ?>
    </div>

    <!-- Section to display upcoming events related to the program -->
    <?php

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
        $homepageEvents->the_post(); ?>

        <div class="event-summary">
          <a class="event-summary__date t-center" href="#">
            <span class="event-summary__month">
              <?php
              // Retrieve the 'event_date' custom field and create a new DateTime object from it
              $eventDate = new DateTime(get_field('event_date'));

              // Format the date to display the month abbreviation (e.g., Jan, Feb, etc.)
              echo $eventDate->format('M');
              ?>
            </span>
            <span class="event-summary__day">
              <?php
              // Format the date to display the day (e.g., 01, 02, etc.)
              echo $eventDate->format('d');
              ?>
            </span>

          </a>
          <div class="event-summary__content">
            <h5 class="event-summary__title headline headline--tiny">
              <!-- Display the event title with a link to the single post page -->
              <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
            </h5>
            <p>
              <?php
              // Check if the post has an excerpt
              if (has_excerpt()) {
                echo get_the_excerpt(); // Display the post's excerpt if available
              } else {
                echo wp_trim_words(get_the_content(), 18); // Display a trimmed version of the content if no excerpt is set, limited to 18 words
              }
              ?>
              <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a> <!-- Link to the full event details -->
            </p>
          </div>
        </div>

    <?php }
    }

    // Reset the global post data to avoid conflicts with other queries
    wp_reset_postdata();
    ?>

  </div>

<?php }

get_footer();

?>
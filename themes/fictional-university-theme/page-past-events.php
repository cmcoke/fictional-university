<?php get_header(); ?>

<!-- Page banner section with a background image and title for the "Past Events" page -->
<div class="page-banner">
  <div class="page-banner__bg-image"
    style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg') ?>)">
  </div>
  <div class="page-banner__content container container--narrow">
    <h1 class="page-banner__title">Past Events</h1>
    <div class="page-banner__intro">
      <p>A recap of our past events</p>
    </div>
  </div>
</div>

<div class="container container--narrow page-section">

  <?php

  /**
   * 
   * The query fetches events where the custom field event_date is earlier than today, orders them in 
   * ascending order by date, and supports pagination to display the events across multiple pages.
   * 
   */

  // Get today's date in 'Ymd' format to use in the query comparison
  $today = date('Ymd');

  // Create a new custom WP_Query to fetch past events
  $pastEvents = new WP_Query(array(
    'post_type' => 'event', // Specify the custom post type 'event'
    'paged' => get_query_var('paged', 1), // Set up pagination
    'meta_key' => 'event_date', // Custom field 'event_date' used for ordering
    'orderby' => 'meta_value_num', // Order by the numeric value of 'event_date'
    'order' => 'ASC', // Display events in ascending order (oldest to newest)
    'meta_query' => array(
      array(
        'key' => 'event_date', // Check against the 'event_date' custom field
        'compare' => '<', // Only retrieve events with dates earlier than today
        'value' => $today, // Use today's date for comparison
        'type' => 'numeric' // Specify that the 'event_date' is a numeric value
      )
    )
  ));

  // Loop through the retrieved past events
  while ($pastEvents->have_posts()) {
    $pastEvents->the_post();
  ?>

    <!-- Display each past event summary -->
    <div class="event-summary">
      <a class="event-summary__date t-center" href="#">
        <span class="event-summary__month">
          <?php
          // Retrieve the event date and display the month in 'M' format
          $eventDate = new DateTime(get_field('event_date'));
          echo $eventDate->format('M');
          ?>
        </span>
        <span class="event-summary__day">
          <?php
          // Display the day of the event date in 'd' format
          echo $eventDate->format('d');
          ?>
        </span>
      </a>
      <div class="event-summary__content">
        <!-- Display the event title with a link to the full event post -->
        <h5 class="event-summary__title headline headline--tiny">
          <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
        </h5>
        <p>
          <?php echo wp_trim_words(get_the_content(), 18) // Display an excerpt of the event content limited to 18 words 
          ?>
          <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a>
        </p>
      </div>
    </div>

  <?php }

  // Display pagination links for navigating through the pages of past events
  echo paginate_links(array(
    'total' => $pastEvents->max_num_pages // Use the total number of pages from the custom query
  ));
  ?>

</div>

<?php get_footer(); ?>
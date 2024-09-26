<?php

get_header();
pageBanner(array(
  'title' => 'Past Events',
  'subtitle' => 'A recap of our past events'
));

?>


<div class="container container--narrow page-section">

  <?php

  /**
   * Create a custom query to fetch past events
   * The query fetches events where the custom field 'event_date' is earlier than today, orders them in 
   * ascending order by date, and supports pagination to display the events across multiple pages.
   */

  // Get today's date in 'Ymd' format to use in the query comparison
  $today = date('Ymd');

  // Create a new custom WP_Query to fetch past events
  $pastEvents = new WP_Query(array(
    'post_type' => 'event', // Specify the custom post type 'event'
    'paged' => get_query_var('paged', 1), // Set up pagination using the 'paged' query variable
    'meta_key' => 'event_date', // Custom field 'event_date' used for ordering the events
    'orderby' => 'meta_value_num', // Order by the numeric value of 'event_date'
    'order' => 'ASC', // Display events in ascending order (oldest to newest)
    'meta_query' => array( // Filter events based on the custom field 'event_date'
      array(
        'key' => 'event_date', // Specify the custom field to compare
        'compare' => '<', // Only retrieve events with dates earlier than today
        'value' => $today, // Use today's date as the comparison value
        'type' => 'numeric' // Specify that the 'event_date' is a numeric value
      )
    )
  ));

  // Loop through the retrieved past events
  while ($pastEvents->have_posts()) {
    $pastEvents->the_post(); // Set up post data for each event

    // Display each event using the 'content-event' template part
    get_template_part('/template-parts/content-event');
  }

  // Display pagination links for navigating through the pages of past events
  echo paginate_links(array(
    'total' => $pastEvents->max_num_pages // Use the total number of pages from the custom query
  ));
  ?>

</div>

<?php get_footer(); ?>
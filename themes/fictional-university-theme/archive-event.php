<?php

get_header();

// Display a custom page banner with title and subtitle for the Events archive page
pageBanner(array(
  'title' => 'All Events', // Set the banner title
  'subtitle' => 'See what is going on in our world' // Set the banner subtitle
));

?>

<div class="container container--narrow page-section">

  <?php
  // Start the WordPress loop to iterate through posts in the archive page
  while (have_posts()) {
    the_post(); // Set up post data for each post

    // Include the template part 'content-event' to display each event's content
    get_template_part('/template-parts/content-event');
  }

  // Output pagination links for navigating between multiple pages of posts
  echo paginate_links();
  ?>

  <hr class="section-break"> <!-- Display a horizontal line as a section break -->

  <p>Looking for a recap of past events?
    <!-- Link to the past events archive page -->
    <a href="<?php echo site_url('/past-events'); ?>">Check out our past events archive</a>.
  </p>
</div>

<?php get_footer();  ?>
<div class="full-width-split group">
  <!-- Container for Upcoming Events Section -->
  <div class="full-width-split__one">
    <div class="full-width-split__inner">
      <h2 class="headline headline--small-plus t-center">Upcoming Events</h2>

      <?php
      // Sets today's date in 'Ymd' format for event filtering
      $today = date('Ymd');

      // Creates a custom WP_Query to fetch upcoming events
      $homepageEvents = new WP_Query(array(
        'posts_per_page' => 2, // Limits the number of events to display
        'post_type' => 'event', // Fetches posts from the 'event' custom post type
        'meta_key' => 'event_date', // Defines the meta key for sorting
        'orderby' => 'meta_value_num', // Sorts by the event date in ascending order
        'order' => 'ASC',
        'meta_query' => array(
          array(
            'key' => 'event_date', // Filters events that occur on or after today
            'compare' => '>=',
            'value' => $today,
            'type' => 'numeric' // Specifies numeric comparison for the date
          )
        )
      ));

      // Loops through each event and loads a template part for displaying each one
      while ($homepageEvents->have_posts()) {
        $homepageEvents->the_post();
        get_template_part('template-parts/content', 'event'); // Displays event content from a separate template file
      }
      ?>

      <!-- Link to view all events -->
      <p class="t-center no-margin"><a href="<?php echo get_post_type_archive_link('event'); ?>"
          class="btn btn--blue">View All Events</a></p>
    </div>
  </div>

  <!-- Container for Blog Posts Section -->
  <div class="full-width-split__two">
    <div class="full-width-split__inner">
      <h2 class="headline headline--small-plus t-center">From Our Blogs</h2>

      <?php
      // Creates a custom WP_Query to fetch the latest blog posts
      $homepagePosts = new WP_Query(array(
        'posts_per_page' => 2 // Limits the number of blog posts to display
      ));

      // Loops through each blog post and outputs a summary for each
      while ($homepagePosts->have_posts()) {
        $homepagePosts->the_post(); ?>
        <div class="event-summary">
          <!-- Displays the date of each blog post with a link to the post -->
          <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink(); ?>">
            <span class="event-summary__month"><?php the_time('M'); ?></span> <!-- Shows the month -->
            <span class="event-summary__day"><?php the_time('d'); ?></span> <!-- Shows the day -->
          </a>
          <div class="event-summary__content">
            <!-- Displays the title of the blog post with a link -->
            <h5 class="event-summary__title headline headline--tiny"><a
                href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
            <!-- Displays the excerpt or trimmed content with a "Read more" link -->
            <p><?php if (has_excerpt()) {
                  echo get_the_excerpt();
                } else {
                  echo wp_trim_words(get_the_content(), 18); // Limits content to 18 words if no excerpt is available
                } ?> <a href="<?php the_permalink(); ?>" class="nu gray">Read more</a></p>
          </div>
        </div>
      <?php }
      wp_reset_postdata(); // Resets the query after the custom loop
      ?>

      <!-- Link to view all blog posts -->
      <p class="t-center no-margin"><a href="<?php echo site_url('/blog'); ?>" class="btn btn--yellow">View All Blog
          Posts</a></p>
    </div>
  </div>
</div>
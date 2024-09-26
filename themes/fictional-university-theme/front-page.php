<?php get_header(); ?>

<div class="page-banner">
  <div class="page-banner__bg-image"
    style="background-image: url(<?php echo get_theme_file_uri('images/library-hero.jpg') ?>)"></div>
  <div class="page-banner__content container t-center c-white">
    <h1 class="headline headline--large">Welcome!</h1>
    <h2 class="headline headline--medium">We think you&rsquo;ll like it here.</h2>
    <h3 class="headline headline--small">Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re
      interested in?</h3>
    <!-- link to the program page -->
    <a href="<?php echo get_post_type_archive_link('program'); ?>" class="btn btn--large btn--blue">Find Your Major</a>
  </div>
</div>

<div class="full-width-split group">
  <div class="full-width-split__one">
    <div class="full-width-split__inner">
      <h2 class="headline headline--small-plus t-center">Upcoming Events</h2>

      <?php

      /**
       * The code below creates a custom WordPress query to fetch upcoming "event" posts from the current date onwards, 
       * sorted in ascending order by the "event_date" custom field.
       */

      // Get the current date in 'Ymd' format (e.g., 20240918 for September 18, 2024)
      $today = date('Ymd');

      // Create a new custom WordPress query to fetch upcoming events
      $homepageEvents = new WP_Query(array(
        'posts_per_page' => 2,  // Limit the query to 2 posts
        'post_type' => 'event', // Specify the custom post type as 'event'
        'meta_key' => 'event_date', // Use the 'event_date' advanced custom field to sort posts
        'orderby' => 'meta_value_num', // Order by the numeric value of the advanced custom field
        'order' => 'ASC', // Arrange posts in ascending order (earliest to latest)
        // Define a meta query to filter posts based on the event date
        'meta_query' => array(
          array(
            'key' => 'event_date', // Use the 'event_date' advanced custom field
            'compare' => '>=', // Include events that are on or after today's date
            'value' => $today, // Compare against today's date
            'type' => 'numeric' // Treat the 'event_date' field as a numeric value for comparison
          )
        )
      ));

      // Loop through each 'event' post retrieved by WP_Query
      while ($homepageEvents->have_posts()) {
        $homepageEvents->the_post();
        get_template_part('/template-parts/content-event');
      }
      wp_reset_postdata(); // Reset the global post data to avoid conflicts with other queries
      ?>

      <p class="t-center no-margin">
        <!-- Link to the archive page showing all 'event' posts -->
        <a href="<?php echo get_post_type_archive_link('event'); ?>" class="btn btn--blue">View All Events</a>
      </p>


    </div>
  </div>

  <div class="full-width-split__two">
    <div class="full-width-split__inner">
      <h2 class="headline headline--small-plus t-center">From Our Blogs</h2>

      <?php
      // Create a new custom WordPress query to fetch the latest 2 blog posts
      $homepagePosts = new WP_Query(array(
        'posts_per_page' => 2  // Limit the query to 2 posts
      ));

      // Loop through the fetched posts
      while ($homepagePosts->have_posts()) {
        $homepagePosts->the_post(); // Set up the post data for each post 
      ?>

        <!-- Display each post's date and content summary -->
        <div class="event-summary">
          <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink(); ?>">
            <!-- Display the month of the post -->
            <span class="event-summary__month"><?php the_time('M'); ?></span>
            <!-- Display the day of the post -->
            <span class="event-summary__day"><?php the_time('d'); ?></span>
          </a>
          <div class="event-summary__content">
            <!-- Display the post title with a link to the full post -->
            <h5 class="event-summary__title headline headline--tiny"><a
                href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>

            <p>
              <?php
              // Display the excerpt if available; otherwise, display the first 18 words of the post content
              if (has_excerpt()) {
                echo get_the_excerpt(); // Display the post's excerpt if available
              } else {
                echo wp_trim_words(get_the_content(), 18); // Display a trimmed version of the content if no excerpt is set, limited to 18 words
              }
              ?>
              <a href="<?php the_permalink(); ?>" class="nu gray">Read more</a>
            </p>
          </div>
        </div>

      <?php }
      // Reset the post data to the original query after using a custom query
      wp_reset_postdata();
      ?>

      <!-- Display a button linking to the full blog archive -->
      <p class="t-center no-margin">
        <a href="<?php echo site_url('/blog'); ?>" class="btn btn--yellow">View All BlogPosts</a>
      </p>

    </div>

  </div>

</div>

<div class="hero-slider">
  <div data-glide-el="track" class="glide__track">
    <div class="glide__slides">
      <div class="hero-slider__slide" style="background-image: url(<?php echo get_theme_file_uri('images/bus.jpg') ?>)">
        <div class="hero-slider__interior container">
          <div class="hero-slider__overlay">
            <h2 class="headline headline--medium t-center">Free Transportation</h2>
            <p class="t-center">All students have free unlimited bus fare.</p>
            <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
          </div>
        </div>
      </div>
      <div class="hero-slider__slide"
        style="background-image: url(<?php echo get_theme_file_uri('images/apples.jpg') ?>)">
        <div class="hero-slider__interior container">
          <div class="hero-slider__overlay">
            <h2 class="headline headline--medium t-center">An Apple a Day</h2>
            <p class="t-center">Our dentistry program recommends eating apples.</p>
            <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
          </div>
        </div>
      </div>
      <div class="hero-slider__slide"
        style="background-image: url(<?php echo get_theme_file_uri('images/bread.jpg') ?>)">
        <div class="hero-slider__interior container">
          <div class="hero-slider__overlay">
            <h2 class="headline headline--medium t-center">Free Food</h2>
            <p class="t-center">Fictional University offers lunch plans for those in need.</p>
            <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
          </div>
        </div>
      </div>
    </div>
    <div class="slider__bullets glide__bullets" data-glide-el="controls[nav]"></div>
  </div>
</div>

<?php get_footer(); ?>
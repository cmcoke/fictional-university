<?php get_header(); ?>

<!-- Page banner section with background image and title -->
<div class="page-banner">
  <!-- Display a background image from the theme's 'images' folder -->
  <div class="page-banner__bg-image"
    style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg') ?>)">
  </div>
  <div class="page-banner__content container container--narrow">
    <h1 class="page-banner__title">All Events</h1>
    <div class="page-banner__intro">
      <p>See what is going on in our world</p>
    </div>
  </div>
</div>

<div class="container container--narrow page-section">

  <?php
  // Loop through the posts in the archive page
  while (have_posts()) {
    the_post(); // Set up post data for each post 
  ?>

  <div class="event-summary">
    <a class="event-summary__date t-center" href="#">
      <span class="event-summary__month">
        <?php
          // Retrieve the advanced custom field value 'event_date' and create a new DateTime object from it
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
        <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
      </h5>
      <p>
        <?php echo wp_trim_words(get_the_content(), 18) ?>
        <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a>
      </p>
    </div>
  </div>

  <?php }
  // Output pagination links for navigating between multiple pages of posts
  echo paginate_links();
  ?>

  <hr class="section-break">

  <p>Looking for a recap of past events?
    <a href="<?php echo site_url('/past-events'); ?>">Check out our past events archive</a>.
  </p>
</div>


<?php get_footer(); ?>
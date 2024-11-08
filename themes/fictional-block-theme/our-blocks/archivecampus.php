<?php

// Display a custom page banner with the specified title and subtitle
pageBanner(array(
  'title' => 'Our Campuses', // Set the title of the page banner
  'subtitle' => 'We have several conveniently located campuses.' // Set the subtitle of the page banner
));


?>

<div class="container container--narrow page-section">

  <ul class="link-list min-list">

    <?php

    // Start the WordPress loop to iterate through posts
    while (have_posts()) {

      // Set up post data for each post 
      the_post(); ?>

      <!-- displays the campus location on google maps -->
      <li>
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        <?php echo get_field('map_location'); ?>
      </li>



    <?php } ?>

  </ul>
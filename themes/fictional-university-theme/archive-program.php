<?php get_header(); ?>

<!-- Page banner section with background image and title -->
<div class="page-banner">
  <!-- Display a background image from the theme's 'images' folder -->
  <div class="page-banner__bg-image"
    style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg') ?>)">
  </div>
  <div class="page-banner__content container container--narrow">
    <h1 class="page-banner__title">All Programs</h1>
    <div class="page-banner__intro">
      <p>There is something for everyone. Have a look around.</p>
    </div>
  </div>
</div>

<div class="container container--narrow page-section">

  <ul class="link-list min-list">
    <?php
    // Loop through the posts in the archive page
    while (have_posts()) {
      the_post(); // Set up post data for each post 
    ?>

      <li>
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      </li>

    <?php }
    // Output pagination links for navigating between multiple pages of posts
    echo paginate_links();
    ?>
  </ul>

</div>


<?php get_footer(); ?>
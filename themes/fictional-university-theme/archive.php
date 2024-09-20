<!-- Include the WordPress header template -->
<?php get_header(); ?>

<!-- Page banner section with background image and title -->
<div class="page-banner">
  <!-- Display a background image from the theme's 'images' folder -->
  <div class="page-banner__bg-image"
    style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg') ?>)">
  </div>
  <div class="page-banner__content container container--narrow">
    <!-- Display the archive title (e.g., category or tag name) -->
    <h1 class="page-banner__title"><?php the_archive_title(); ?></h1>
    <!-- Display the archive description (e.g., category or tag description) -->
    <div class="page-banner__intro">
      <p><?php the_archive_description(); ?></p>
    </div>
  </div>
</div>

<div class="container container--narrow page-section">

  <?php
  // Loop through the posts in the archive page
  while (have_posts()) {
    the_post(); // Set up post data for each post 
  ?>

    <div class="post-item">
      <!-- Display the post title as a link to the full post -->
      <h2 class="headline headline--medium headline--post-title">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      </h2>

      <!-- Display post metadata: author, date, and categories -->
      <div class="metabox">
        <p>
          Posted by <?php the_author_posts_link(); ?> on <?php the_time('n.j.y'); ?>
          in <?php echo get_the_category_list(', '); ?>
        </p>
      </div>

      <!-- Display the post excerpt -->
      <div class="generic-content">
        <?php the_excerpt(); ?>
        <!-- Link to the full post -->
        <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue reading &raquo;</a></p>
      </div>
    </div>

  <?php }
  // Output pagination links for navigating between multiple pages of posts
  echo paginate_links();
  ?>

</div>

<!-- Include the WordPress footer template -->
<?php get_footer(); ?>
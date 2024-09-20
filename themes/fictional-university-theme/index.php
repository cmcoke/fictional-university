<?php get_header(); ?>

<!-- Page banner section with background image and title -->
<div class="page-banner">
  <div class="page-banner__bg-image"
    style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg') ?>)">
  </div>
  <div class="page-banner__content container container--narrow">
    <h1 class="page-banner__title">Welcome to our blog!</h1>
    <div class="page-banner__intro">
      <p>Keep up with our latest news.</p>
    </div>
  </div>
</div>


<!-- Latest Blog Posts -->
<div class="container container--narrow page-section">

  <?php
  // Loop through posts while there are more available
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
        <p>Posted by <?php the_author_posts_link(); ?> on <?php the_time('n.j.y'); ?>
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


<?php get_footer(); ?>
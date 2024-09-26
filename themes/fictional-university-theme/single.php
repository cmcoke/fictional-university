<?php

get_header(); // Include the header template

// Start the WordPress loop to iterate through posts
while (have_posts()) {

  the_post(); // Set up the current post data
  pageBanner(); // Display a custom page banner with the post title and any associated subtitle/background image
?>

<div class="container container--narrow page-section">

  <!-- Meta box showing a link to the blog page and displaying the blog's author, time of creation, and post category -->
  <div class="metabox metabox--position-up metabox--with-home-link">
    <p>

      <!-- Link back to the blog page -->
      <a class="metabox__blog-home-link" href="<?php echo site_url('/blog'); ?>">
        <i class="fa fa-home" aria-hidden="true"></i> Blog Home
      </a>

      <!-- Show the blog's author, time of creation, and the blog post's categories -->
      <span class="metabox__main">
        Posted by <?php the_author_posts_link(); ?> on <?php the_time('n.j.y'); ?> in
        <?php echo get_the_category_list(', '); ?>
        <!-- Display the categories of the post -->
      </span>

    </p>
  </div>

  <!-- The blog post's text content -->
  <div class="generic-content">
    <?php the_content(); ?>
    <!-- Display the content of the post -->
  </div>
</div>

<?php }

get_footer();

?>
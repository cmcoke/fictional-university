<?php

get_header();

while (have_posts()) {

  the_post(); ?>

<!-- Page banner section with background image and title -->
<div class="page-banner">
  <div class="page-banner__bg-image"
    style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg') ?>)">
  </div>
  <div class="page-banner__content container container--narrow">
    <h1 class="page-banner__title"><?php the_title(); ?></h1>
    <div class="page-banner__intro">
      <p>DON'T FORGET TO REPLACE ME LATER.</p>
    </div>
  </div>
</div>

<div class="container container--narrow page-section">

  <!-- Meta box showing a link to the blog page and show the blog's author, time in which the blog post was created and the blog post' category -->
  <div class="metabox metabox--position-up metabox--with-home-link">
    <p>

      <!-- Link back to the blog page -->
      <a class="metabox__blog-home-link" href="<?php echo site_url('/blog'); ?>">
        <i class="fa fa-home" aria-hidden="true"></i>Blog Home
      </a>

      <!-- show the blog's author, time in which the blog post was created and the blog post' category  -->
      <span class="metabox__main">
        Posted by <?php the_author_posts_link(); ?> on <?php the_time('n.j.y'); ?> in
        <?php echo get_the_category_list(', '); ?>
      </span>

    </p>
  </div>

  <!-- The blog post's text content -->
  <div class="generic-content">
    <?php the_content(); ?>
  </div>
</div>

<?php }

get_footer();

?>
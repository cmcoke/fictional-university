<?php
get_header();

// Display a custom page banner with a title and subtitle
pageBanner(array(
  'title' => 'Welcome to our blog!', // Set the main title of the page
  'subtitle' => 'Keep up with our latest news.' // Set the subtitle for the banner
));
?>


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

<?php get_footer(); ?>
<?php
get_header();

// Display a custom page banner with the specified title and subtitle
pageBanner(array(
  'title' => 'All Programs', // Set the title of the page banner
  'subtitle' => 'There is something for everyone. Have a look around.' // Set the subtitle of the page banner
));
?>

<div class="container container--narrow page-section">

  <ul class="link-list min-list">
    <?php
    // Start the WordPress loop to iterate through posts
    while (have_posts()) {
      the_post(); // Set up post data for each post 
    ?>

    <li>
      <!-- Display the post title as a link to the single post page -->
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </li>

    <?php }
    // Output pagination links for navigating between multiple pages of posts
    echo paginate_links();
    ?>
  </ul>

</div>

<?php get_footer(); ?>
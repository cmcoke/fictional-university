<?php

/**
 * This template will only show if JavaScript is disabled on the user's browser.
 * It handles displaying search results when a search is performed.
 */

get_header(); // Include the header.php template

// Display a custom page banner with a title and subtitle
pageBanner(array(
  'title' => 'Search Results', // Set the main title of the page
  'subtitle' => 'You searched for &ldquo;' . esc_html(get_search_query(false)) . '&rdquo;' // Display the user's search query in the subtitle
));
?>

<!-- Search Results Section -->
<div class="container container--narrow page-section">

  <?php

  // Check if there are any posts that match the search query
  if (have_posts()) {
    // Loop through all matching posts
    while (have_posts()) {
      the_post(); // Set up post data for each post in the loop

      // Load a template part to display the content based on the post type
      get_template_part('template-parts/content', get_post_type());
    }

    // Display pagination links to navigate through multiple pages of search results
    echo paginate_links();
  } else {
    // Display a message if no posts match the search query
    echo '<h2 class="headline headline--medium">No results match that search.</h2>';
  }

  // Display the search form to allow the user to search again
  get_search_form();

  ?>

</div>

<?php get_footer(); // Include the footer.php template 
?>
<?php

/**
 * The purpose of this template is to show a search form incase JavaScript is disabled on the user' browser.
 */

get_header();

// Start the loop to display content of the current page
while (have_posts()) {
  the_post();
  pageBanner(); // Display the page banner with the current page's title and subtitle
?>


<!-- Main content container -->
<div class="container container--narrow page-section">

  <?php

    // Get the parent page ID if this page has a parent
    $theParent = wp_get_post_parent_id(get_the_ID());

    // If the page has a parent, display a back link to the parent page 
    if ($theParent) { ?>

  <div class="metabox metabox--position-up metabox--with-home-link">
    <p>
      <!-- Link back to the parent page -->
      <a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>">

        <i class="fa fa-home" aria-hidden="true"></i>

        <!-- Display parent page title  -->
        Back to <?php echo get_the_title($theParent); ?></a>

      <span class="metabox__main">
        <!-- Display the current page title  -->
        <?php the_title(); ?>
      </span>

    </p>
  </div>

  <?php }

    // Check if the current page has any child pages
    $testArray = get_pages(array(
      'child_of' => get_the_ID() // Retrieve pages that are children of the current page
    ));

    // If the page has a parent or child pages 
    if ($theParent or $testArray) { ?>

  <div class="page-links">

    <!-- Display title and link to the parent page -->
    <h2 class="page-links__title">
      <a href="<?php echo get_permalink($theParent) ?>"> <?php echo get_the_title($theParent); ?></a>
    </h2>

    <!-- List child pages (if any) -->
    <ul class="min-list">

      <?php

          // Determine whether to find children of the parent or the current page
          if ($theParent) {
            $findChildrenOf = $theParent;
          } else {
            $findChildrenOf = get_the_ID();
          }

          // List the child pages, ordered by menu_order
          wp_list_pages(array(
            'title_li' => NULL, // Removes the default <li> wrapper around the page title (NULL disables the title list)
            'child_of' => $findChildrenOf, // Specifies the ID of the parent page whose child pages will be listed
            'sort_column' => 'menu_order' // Sorts the child pages based on their menu order as defined in the WordPress admin
          ));

          ?>

    </ul>

  </div>

  <?php } ?>

  <!-- Display the search form in the searchform.php file -->
  <?php get_search_form(); ?>

</div>

<?php } // End of the loop


get_footer();

?>
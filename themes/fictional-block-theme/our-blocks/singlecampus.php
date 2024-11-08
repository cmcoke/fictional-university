<?php
  
  pageBanner(); // Display a custom page banner with the post title and any associated subtitle/background image
  ?>

<div class="container container--narrow page-section">

  <!-- Meta box showing a link to the campus archive page and displaying the campus name -->
  <div class="metabox metabox--position-up metabox--with-home-link">
    <p>
      <!-- Link back to the campus archive page -->
      <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>">
        <i class="fa fa-home" aria-hidden="true"></i> All Campuses
      </a>

      <!-- Display the current campus name -->
      <span class="metabox__main">
        <?php the_title(); ?>
      </span>
    </p>
  </div>

  <!-- Display the main content of the post/page -->
  <div class="generic-content">
    <!-- Display the post/page content -->
    <?php the_content(); ?>
  </div>

  <!-- Campus Map -->
  <?php echo get_field('map_location'); ?>


  <?php
      /**
       * The code below creates a custom WordPress query to fetch "professor" posts related to the current program.
       * The posts are ordered alphabetically by title.
       */
  
      // Create a custom WP_Query to fetch related professors
      $relatedPrograms = new WP_Query(array(
        'posts_per_page' => -1, // Retrieve all matching posts
        'post_type' => 'program', // Specify the custom post type as 'professor'
        'orderby' => 'title', // Order professors alphabetically by title
        'order' => 'ASC', // Arrange posts in ascending order
        // Define a meta query to find professors related to the current program
        'meta_query' => array(
          array(
            'key' => 'related_campus', // Use the 'related_programs' advanced custom field
            'compare' => 'LIKE', // Search for the current program's ID within the 'related_programs' field
            'value' => '"' . get_the_ID() . '"' // The current program's ID in double quotes for accurate LIKE comparison
          )
        )
      ));
  
      // Check if there are any professors that match the query
      if ($relatedPrograms->have_posts()) {
  
        echo "<hr class='section-break'>"; // Display a horizontal line as a section break
        echo '<h2 class="headline headline--medium">Programs available at this campus</h2>'; // Display a heading for related professors
        echo "<ul class='min-list link-list'>"; // Start an unordered list with a custom class
  
        // Loop through each 'professor' post retrieved by WP_Query
        while ($relatedPrograms->have_posts()) {
          $relatedPrograms->the_post(); ?>

  <li>
    <!-- Display professor details with a link to their page -->
    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
  </li>

  <?php }
        echo "</ul>"; // End the unordered list
      }
  
      // Reset the global post data to avoid conflicts with other queries
      wp_reset_postdata();
  
      ?>

</div>
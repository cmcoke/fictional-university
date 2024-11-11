<div class="post-item">
  <!-- Display the post title as a link to the full post -->
  <h2 class="headline headline--medium headline--post-title">
    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
  </h2>


  <!-- Display the post excerpt -->
  <div class="generic-content">
    <?php the_excerpt(); ?>
    <!-- Link to the full post -->
    <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">View program &raquo;</a></p>
  </div>
</div>
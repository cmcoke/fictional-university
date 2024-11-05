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
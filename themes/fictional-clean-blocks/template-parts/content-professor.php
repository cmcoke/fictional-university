<div class="post-item">
  <li class="professor-card__list-item">
    <!-- Display professor details with a link to their page -->
    <a class="professor-card" href="<?php the_permalink(); ?>">
      <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>">
      <!-- Display professor's image -->
      <span class="professor-card__name"><?php the_title(); ?></span> <!-- Display professor's name -->
    </a>
  </li>
</div>
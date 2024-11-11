<?php

// Check if 'imgURL' attribute is not set
if (!isset($attributes['imgURL'])) {
  // If 'imgURL' is not set, assign a default image path
  $attributes['imgURL'] = get_theme_file_uri('/images/library-hero.jpg'); // Default background image
}

?>

<!-- Main page banner container -->
<div class="page-banner">

  <!-- Background image for the banner, using 'imgURL' attribute -->
  <div class="page-banner__bg-image" style="background-image: url('<?php echo $attributes['imgURL'] ?>')"></div>

  <!-- Banner content container with center-aligned text and white color styling -->
  <div class="page-banner__content container t-center c-white">

    <!-- Output dynamic content passed to the banner (e.g., title, subtitle, or other elements) -->
    <?php echo $content; ?>
  </div>
</div>
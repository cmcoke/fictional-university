<?php

// Check if 'themeimage' attribute is not empty
if (!empty($attributes['themeimage'])) {
  // If 'themeimage' is set, construct the image URL using the theme directory URI and set it to 'imgURL'
  $attributes['imgURL'] = get_theme_file_uri('/images/' . $attributes['themeimage']);
}

// Check if 'imgURL' attribute is not set
if (!isset($attributes['imgURL'])) {
  // If 'imgURL' is not set, assign a default image URL ('library-hero.jpg') as the fallback
  $attributes['imgURL'] = get_theme_file_uri('/images/library-hero.jpg');
}

?>

<!-- HTML structure for the slide, with background image dynamically set from 'imgURL' -->
<div class="hero-slider__slide" style="background-image: url('<?php echo $attributes['imgURL'] ?>')">
  <!-- Slide interior container with some custom styling -->
  <div class="hero-slider__interior container">
    <!-- Overlay content section, centered text for the slide -->
    <div class="hero-slider__overlay t-center">
      <!-- Output the content of the block (e.g., nested blocks like heading, buttons, etc.) -->
      <?php echo $content; ?>
    </div>
  </div>
</div>
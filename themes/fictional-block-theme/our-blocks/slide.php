<?php

// Check if 'imgURL' attribute is set; if not, set a default image URL
if (!isset($attributes['imgURL'])) {
  // Sets default image to 'library-hero.jpg' from the theme's images directory
  $attributes['imgURL'] = get_theme_file_uri('/images/library-hero.jpg');
}

?>

<!-- Slide container with dynamic background image -->
<div class="hero-slider__slide" style="background-image: url('<?php echo $attributes['imgURL'] ?>')">
  <div class="hero-slider__interior container">
    <div class="hero-slider__overlay t-center">
      <?php echo $content; // Output nested content (like headings/buttons) added via InnerBlocks 
      ?>
    </div>
  </div>
</div>
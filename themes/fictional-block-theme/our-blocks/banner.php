<?php

// Check if 'imgURL' attribute exists. If not, assign a default image URL from the theme directory
if (!$attributes['imgURL']) {
  $attributes['imgURL'] = get_theme_file_uri('/images/library-hero.jpg'); // Default background image
}

?>

<div class="page-banner">
  <!-- Background image div, using the imgURL attribute for the inline background image style -->
  <div class="page-banner__bg-image" style="background-image: url('<?php echo $attributes['imgURL']; ?>')"></div>

  <!-- Banner content container with a center-aligned white text style -->
  <div class="page-banner__content container t-center c-white">
    <!-- Outputs the inner content of the block (e.g., headings, buttons) -->
    <?php echo $content; ?>
  </div>
</div>
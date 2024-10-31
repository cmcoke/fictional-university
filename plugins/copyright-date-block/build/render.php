<?php

/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>

<?php

// Get the current year as a four-digit number (e.g., "2024")
$current_year = date("Y");

// Check if 'startingYear' and 'showStartingYear' attributes are both set and not empty
if (! empty($attributes['startingYear']) && ! empty($attributes['showStartingYear'])) {
  // Display as a range with the starting year and current year (e.g., "2020–2024")
  $display_date = $attributes['startingYear'] . '–' . $current_year;
} else {
  // Display only the current year if starting year or 'showStartingYear' is not set
  $display_date = $current_year;
}

?>

<!-- Render the copyright information inside a paragraph tag -->
<p <?php echo get_block_wrapper_attributes(); ?>>
  © <?php echo esc_html($display_date); ?>
</p>
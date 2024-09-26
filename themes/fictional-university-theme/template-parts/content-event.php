<div class="event-summary">
  <a class="event-summary__date t-center" href="#">
    <span class="event-summary__month">
      <?php
      // Retrieve the advanced custom field value 'event_date' and create a new DateTime object from it
      $eventDate = new DateTime(get_field('event_date'));

      // Format the date to display the month abbreviation (e.g., Jan, Feb, etc.)
      echo $eventDate->format('M');
      ?>
    </span>
    <span class="event-summary__day">
      <?php
      // Format the date to display the day (e.g., 01, 02, etc.)
      echo $eventDate->format('d');
      ?>
    </span>

  </a>
  <div class="event-summary__content">
    <h5 class="event-summary__title headline headline--tiny">
      <!-- Display the event title with a link to the single post page -->
      <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
    </h5>
    <p>
      <?php
      // Check if the post has an excerpt
      if (has_excerpt()) {
        echo get_the_excerpt(); // Display the post's excerpt if available
      } else {
        echo wp_trim_words(get_the_content(), 18); // Display a trimmed version of the content if no excerpt is set, limited to 18 words
      }
      ?>
      <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a>
    </p>
  </div>
</div>
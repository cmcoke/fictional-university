<?php

/**
 * This code is a WordPress template that allows logged-in users to create, edit, and delete their own "notes" using a custom post type.
 * - If the user is not logged in, they are redirected to the homepage.
 * - If logged in, the user can see their notes in a list, create new notes, and edit or delete existing ones.
 */

// Redirect users who are not logged in to the homepage
if (!is_user_logged_in()) {
  wp_redirect(esc_url(site_url('/')));
  exit; // Ensure that the script stops executing after the redirect
}

get_header(); // Load the header template

// Start the loop to display the content of the current page
while (have_posts()) {
  the_post();
  pageBanner(); // Display a banner with the page's title and subtitle
?>

<!-- Main content container for creating and managing notes -->
<div class="container container--narrow page-section">

  <!-- Section for creating a new note -->
  <div class="create-note">
    <h2 class="headline headline--medium">Create New Note</h2>

    <!-- Input for the title of the new note -->
    <input class="new-note-title" type="text" placeholder="Title">

    <!-- Text area for the body of the new note -->
    <textarea class="new-note-body" placeholder="Your note here..."></textarea>

    <!-- Button to submit and create the new note -->
    <span class="submit-note">Create Note</span>

    <!-- Message shown if the user has reached their note limit -->
    <span class="note-limit-message">Note limit reached: delete an existing note to make room for a new one.</span>
  </div>

  <!-- List of existing notes created by the logged-in user -->
  <ul class="min-list link-list" id="my-notes">

    <?php
      // Query to fetch all 'note' posts created by the current user
      $userNotes = new WP_Query(array(
        'post_type' => 'note',           // Only retrieve 'note' post types
        'posts_per_page' => -1,          // Retrieve all posts (no limit)
        'author' => get_current_user_id() // Only retrieve posts created by the current user
      ));

      // Loop through each note and display it
      while ($userNotes->have_posts()) {
        $userNotes->the_post(); ?>

    <!-- List item for each note, including its title and body -->
    <li data-id="<?php the_ID(); ?>">
      <!-- Read-only input for the note title, removing 'Private: ' from the title if present -->
      <input readonly class="note-title-field" type="text"
        value="<?php echo str_replace('Private: ', '', esc_attr(get_the_title())); ?>">

      <!-- Button to edit the note -->
      <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>

      <!-- Button to delete the note -->
      <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>

      <!-- Text area to display the note content, with HTML tags stripped -->
      <textarea readonly
        class="note-body-field"><?php echo esc_textarea(wp_strip_all_tags(get_the_content())); ?></textarea>

      <!-- Button to save any updates made to the note -->
      <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i>
        Save</span>
    </li>

    <?php } // End of notes loop 
      ?>
  </ul>

</div>

<?php } // End of the main page content loop

get_footer(); // Load the footer template

?>
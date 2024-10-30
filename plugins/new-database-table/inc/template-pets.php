<?php

/**
 * Page template for displaying pet adoption information with options for administrators to manage entries.
 */

require_once plugin_dir_path(__FILE__) . 'GetPets.php'; // Include the GetPets class for retrieving pet data.
$getPets = new GetPets(); // Instantiate the GetPets class to retrieve pets based on user filters.

get_header(); ?>

<!-- Banner Section for Pet Adoption Page -->
<div class="page-banner">
  <div class="page-banner__bg-image"
    style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>);"></div>
  <div class="page-banner__content container container--narrow">
    <h1 class="page-banner__title">Pet Adoption</h1>
    <div class="page-banner__intro">
      <p>Providing forever homes one search at a time.</p>
    </div>
  </div>
</div>

<div class="container container--narrow page-section">

  <p>
    <!-- Display page generation time. -->
    This page took <strong><?php echo timer_stop(); ?></strong> seconds to prepare.
    <!-- Display total pet count. -->
    Found <strong><?php echo number_format($getPets->count); ?></strong> results
    <!-- Display number of pets currently shown. -->
    (showing the first <?php echo count($getPets->pets) ?>).
  </p>

  <!-- Table to Display Pet Details -->
  <table class="pet-adoption-table">
    <tr>
      <th>Name</th>
      <th>Species</th>
      <th>Weight</th>
      <th>Birth Year</th>
      <th>Hobby</th>
      <th>Favorite Color</th>
      <th>Favorite Food</th>
      <!-- Conditionally display Delete column for administrators only -->
      <?php if (current_user_can('administrator')) { ?>
      <th>Delete</th>
      <?php } ?>
    </tr>
    <?php
    // Loop through each pet and display its details in a table row.
    foreach ($getPets->pets as $pet) { ?>
    <tr>
      <td><?php echo $pet->petname; ?></td>
      <td><?php echo $pet->species; ?></td>
      <td><?php echo $pet->petweight; ?></td>
      <td><?php echo $pet->birthyear; ?></td>
      <td><?php echo $pet->favhobby; ?></td>
      <td><?php echo $pet->favcolor; ?></td>
      <td><?php echo $pet->favfood; ?></td>
      <!-- Display Delete button if the current user is an administrator -->
      <?php if (current_user_can('administrator')) { ?>
      <td style="text-align: center;">
        <form action="<?php echo esc_url(admin_url('admin-post.php')) ?>" method="POST">
          <input type="hidden" name="action" value="deletepet"> <!-- Action to trigger deletion. -->
          <input type="hidden" name="idtodelete" value="<?php echo $pet->id; ?>"> <!-- ID of the pet to delete. -->
          <button class="delete-pet-button">X</button> <!-- Delete button styled as 'X'. -->
        </form>
      </td>
      <?php } ?>
    </tr>
    <?php }
    ?>
  </table>

  <!-- Form to Add New Pet, visible only to administrators -->
  <?php
  if (current_user_can('administrator')) { ?>
  <form action="<?php echo esc_url(admin_url('admin-post.php')) ?>" class="create-pet-form" method="POST">
    <p>Enter just the name for a new pet. Its species, weight, and other details will be randomly generated.</p>
    <input type="hidden" name="action" value="createpet"> <!-- Action to trigger pet creation. -->
    <input type="text" name="incomingpetname" placeholder="name...">
    <button>Add Pet</button>
  </form>
  <?php }
  ?>

</div>

<?php get_footer(); ?>
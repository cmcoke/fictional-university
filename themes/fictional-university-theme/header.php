<!DOCTYPE html>

<!-- Outputs the language attributes for the HTML element (e.g., lang="en-US") based on the site's language settings -->
<html <?php language_attributes(); ?>>

<head>
  <!-- Sets the character encoding for the document, using the charset defined in WordPress settings -->
  <meta charset="<?php bloginfo('charset'); ?>">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Hook for WordPress to insert additional elements (like styles and scripts) into the <head> section -->
  <?php wp_head(); ?>
</head>

<!-- body_class(); - Adds the appropriate CSS classes to the <body> tag, allowing for page-specific styling based on WordPress settings -->

<body <?php body_class(); ?>>

  <header class="site-header">
    <div class="container">
      <h1 class="school-logo-text float-left">
        <!-- link to the home page -->
        <a href="<?php echo esc_url(site_url()); ?>"><strong>Fictional</strong> University</a>
      </h1>
      <a href="<?php echo esc_url(site_url('/search')); ?>" class="js-search-trigger site-header__search-trigger"><i
          class="fa fa-search" aria-hidden="true"></i></a>
      <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>
      <div class="site-header__menu group">
        <nav class="main-navigation">
          <ul>

            <li <?php
                // Check if the current page has a slug of 'about-us' or if the parent page ID is 12 (the number 12 refers to the parent page ID 'about us page', which can be found in the WordPress dashboard by viewing the parent page's post ID 'about us page')
                // If either condition is true, add the "current-menu-item" class to the <li> element 
                if (is_page('about-us') or wp_get_post_parent_id(0) == 12) echo 'class="current-menu-item"'
                ?>>
              <!-- link to the about us page -->
              <a href="<?php echo esc_url(site_url('/about-us')); ?>">About Us</a>
            </li>

            <li <?php
                // Check if the current post type is 'program'
                // If true, add the 'current-menu-item' class to highlight this menu item
                if (get_post_type() == 'program') echo 'class="current-menu-item"';
                ?>>

              <a href="<?php echo  get_post_type_archive_link('program'); ?>">Programs</a>
            </li>

            <li <?php
                // Check if the current post type is 'event' or if the current page is 'past-events'
                // If true, add the 'current-menu-item' class to highlight this menu item
                if (get_post_type() == 'event' or is_page('past-events')) echo 'class="current-menu-item"';
                ?>>
              <!-- Link to the archive page of the 'event' custom post type -->
              <a href="<?php echo get_post_type_archive_link('event'); ?>">Events</a>
            </li>

            <li <?php
                // Check if the current post type is 'campus'
                // If true, add the 'current-menu-item' class to highlight this menu item
                if (get_post_type() == 'campus') echo 'class="current-menu-item"';
                ?>>
              <a href="<?php echo get_post_type_archive_link('campus'); ?>">Campuses</a>
            </li>

            <li <?php
                // Check if the current post type is 'post' (i.e., it's a blog post)
                // If true, add the "current-menu-item" class to the <li> element to highlight the menu item
                if (get_post_type() == 'post') echo 'class="current-menu-item"' ?>>
              <!-- Create a link to the 'Blog' page -->
              <a href="<?php echo esc_url(site_url('/blog')); ?>">Blog</a>
            </li>

          </ul>
        </nav>
        <div class="site-header__util">
          <?php

          // Check if the user is logged in
          if (is_user_logged_in()) { ?>

          <!-- Display the "My Notes" button -->
          <a href="<?php echo esc_url(site_url('/my-notes')); ?>"
            class="btn btn--small btn--orange float-left push-right">My Notes</a>

          <!-- Display a "Log Out" button with the current user's avatar if the user is logged in -->
          <a href="<?php echo wp_logout_url(); ?>" class="btn btn--small btn--dark-orange float-left btn--with-photo">

            <!-- Display the avatar of the current user (with a size of 60px) -->
            <span class="site-header__avatar"><?php echo get_avatar(get_current_user_id(), 60); ?></span>

            <!-- Display the "Log Out" button -->
            <span class="btn__text">Log Out</span>

          </a>

          <?php } else { ?>

          <!-- If the user is not logged in, display a "Login" button that links to the login page -->
          <a href="<?php echo wp_login_url(); ?>" class="btn btn--small btn--orange float-left push-right">Login</a>

          <!-- Display a "Sign Up" button that links to the registration page -->
          <a href="<?php echo wp_registration_url(); ?>" class="btn btn--small btn--dark-orange float-left">Sign Up</a>

          <?php } // End of the if-else statement 
          ?>



          <a href="<?php echo esc_url(site_url('/search')); ?>" class="search-trigger js-search-trigger"><i
              class="fa fa-search" aria-hidden="true"></i></a>
        </div>
      </div>
    </div>
  </header>
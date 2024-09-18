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
        <a href="<?php echo site_url() ?>"><strong>Fictional</strong> University</a>
      </h1>
      <span class="js-search-trigger site-header__search-trigger"><i class="fa fa-search" aria-hidden="true"></i></span>
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
              <a href="<?php echo site_url('/about-us') ?>">About Us</a>
            </li>
            <li><a href="#">Programs</a></li>
            <li><a href="#">Events</a></li>
            <li><a href="#">Campuses</a></li>
            <li><a href="#">Blog</a></li>
          </ul>
        </nav>
        <div class="site-header__util">
          <a href="#" class="btn btn--small btn--orange float-left push-right">Login</a>
          <a href="#" class="btn btn--small btn--dark-orange float-left">Sign Up</a>
          <span class="search-trigger js-search-trigger"><i class="fa fa-search" aria-hidden="true"></i></span>
        </div>
      </div>
    </div>
  </header>
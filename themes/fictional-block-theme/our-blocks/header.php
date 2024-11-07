<header class="site-header">
  <div class="container">
    <!-- Logo with link to the homepage -->
    <h1 class="school-logo-text float-left"><a href="<?php echo site_url() ?>"><strong>Fictional</strong> University</a>
    </h1>

    <!-- Search icon with link to the search page -->
    <a href="<?php echo esc_url(site_url('/search')); ?>" class="js-search-trigger site-header__search-trigger">
      <i class="fa fa-search" aria-hidden="true"></i>
    </a>

    <!-- Mobile menu icon for toggling navigation -->
    <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>

    <div class="site-header__menu group">
      <!-- Main navigation menu -->
      <nav class="main-navigation">
        <ul>
          <!-- "About Us" link with active class if on the About Us page or its child pages -->
          <li <?php if (is_page('about-us') || wp_get_post_parent_id(0) == 16) echo 'class="current-menu-item"' ?>>
            <a href="<?php echo site_url('/about-us') ?>">About Us</a>
          </li>

          <!-- "Programs" link with active class if viewing a Program post type archive -->
          <li <?php if (get_post_type() == 'program') echo 'class="current-menu-item"' ?>>
            <a href="<?php echo get_post_type_archive_link('program') ?>">Programs</a>
          </li>

          <!-- "Events" link with active class if on Event post type archive or Past Events page -->
          <li <?php if (get_post_type() == 'event' || is_page('past-events')) echo 'class="current-menu-item"' ?>>
            <a href="<?php echo get_post_type_archive_link('event'); ?>">Events</a>
          </li>

          <!-- "Campuses" link with active class if viewing a Campus post type archive -->
          <li <?php if (get_post_type() == 'campus') echo 'class="current-menu-item"' ?>>
            <a href="<?php echo get_post_type_archive_link('campus'); ?>">Campuses</a>
          </li>

          <!-- "Blog" link with active class if viewing Blog posts -->
          <li <?php if (get_post_type() == 'post') echo 'class="current-menu-item"' ?>>
            <a href="<?php echo site_url('/blog'); ?>">Blog</a>
          </li>
        </ul>
      </nav>

      <div class="site-header__util">
        <!-- User account section, displaying different options for logged-in and logged-out users -->
        <?php if (is_user_logged_in()) { ?>
        <!-- "My Notes" button for logged-in users -->
        <a href="<?php echo esc_url(site_url('/my-notes')); ?>"
          class="btn btn--small btn--orange float-left push-right">My Notes</a>

        <!-- Logout button with user's avatar for logged-in users -->
        <a href="<?php echo wp_logout_url(); ?>" class="btn btn--small btn--dark-orange float-left btn--with-photo">
          <span class="site-header__avatar"><?php echo get_avatar(get_current_user_id(), 60); ?></span>
          <span class="btn__text">Log Out</span>
        </a>
        <?php } else { ?>
        <!-- Login and Sign Up buttons for guests -->
        <a href="<?php echo wp_login_url(); ?>" class="btn btn--small btn--orange float-left push-right">Login</a>
        <a href="<?php echo wp_registration_url(); ?>" class="btn btn--small btn--dark-orange float-left">Sign Up</a>
        <?php } ?>

        <!-- Additional search icon for header utility area -->
        <a href="<?php echo esc_url(site_url('/search')); ?>" class="search-trigger js-search-trigger">
          <i class="fa fa-search" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </div>
</header>
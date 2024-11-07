<?php

// Require the 'like-route.php' file from the 'inc' directory within the theme's file path
require get_theme_file_path('/inc/like-route.php');


// Require the 'search-route.php' file from the 'inc' directory within the theme's file path
require get_theme_file_path('/inc/search-route.php');


/**************************************************************************************************************/

/**
 * This function customizes the WordPress REST API by adding two custom fields to the REST API response.
 * 1. It adds an 'authorName' field to the 'post' post type, which retrieves the author's name.
 * 2. It adds a 'userNoteCount' field to the 'note' post type, which retrieves the number of 'note' posts 
 *    created by the current logged-in user.
 */

// Function to add custom fields to the REST API response
function university_custom_rest()
{
  // Register a custom field 'authorName' for the 'post' post type in the REST API
  register_rest_field('post', 'authorName', array(
    'get_callback' => function () {
      // Retrieves and returns the author's name for each post in the API response
      return get_the_author();
    }
  ));

  // Register a custom field 'userNoteCount' for the 'note' post type in the REST API
  register_rest_field('note', 'userNoteCount', array(
    'get_callback' => function () {
      // Retrieves and returns the number of 'note' posts created by the currently logged-in user
      return count_user_posts(get_current_user_id(), 'note');
    }
  ));
}

// Hook the custom REST API modifications into the 'rest_api_init' action, which runs during REST API initialization
add_action('rest_api_init', 'university_custom_rest');






/**************************************************************************************************************/


// Custom function to display a page banner with customizable title, subtitle, and background image
function pageBanner($args = NULL)
{

  // If the 'title' argument is not provided, use the current post/page title
  if (!isset($args['title'])) {
    $args['title'] = get_the_title();
  }

  // If the 'subtitle' argument is not provided, use the value from the advanced custom field 'page_banner_subtitle'
  if (!isset($args['subtitle'])) {
    $args['subtitle'] = get_field('page_banner_subtitle');
  }

  // If the 'photo' argument is not provided, determine the appropriate background image
  if (!isset($args['photo'])) {

    // Use the advanced custom field 'page_banner_background_image' if it exists and the current page is not an archive or the home page
    if (get_field('page_banner_background_image') and !is_archive() and !is_home()) {
      $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
    } else {
      // Use a default image if no custom field value is available
      $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
    }
  }

?>

  <!-- Page banner section with background image and title -->
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>)"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle']; ?></p>
      </div>
    </div>
  </div>

<?php }




/**************************************************************************************************************/



// Function to enqueue theme scripts and styles
function university_files()
{
  // Enqueue the main JavaScript file, which depends on jQuery, with version 1.0 and loaded in the footer
  wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);

  // Enqueue Google Fonts with specific font weights and styles for Roboto and Roboto Condensed
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');

  // Enqueue Font Awesome for adding icons throughout the theme
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

  // Enqueue the main theme styles generated by the build process (typically the main CSS file)
  wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));

  // Enqueue additional theme styles that might be needed for extra customizations
  wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));


  /**
   * This code uses the wp_localize_script() function to make dynamic data available to a JavaScript file
   * in the WordPress theme. Specifically, it provides two pieces of data:
   * 1. The 'root_url' key, which contains the base URL of the WordPress site.
   * 2. The 'nonce' key, which generates a unique nonce for security purposes, allowing safe REST API requests.
   */

  // Pass dynamic data from PHP to a JavaScript file
  wp_localize_script('main-university-js', 'universityData', array(
    // 'root_url' contains the base URL of the WordPress site (e.g., http://fictional-university.local/)
    'root_url' => get_site_url(),

    // 'nonce' contains a security token generated by WordPress to verify REST API requests
    'nonce' => wp_create_nonce('wp_rest')
  ));
}

// Hook the 'university_files' function to the 'wp_enqueue_scripts' action to load scripts and styles on the front end
add_action('wp_enqueue_scripts', 'university_files');




/**************************************************************************************************************/



// Function to add theme features
function university_features()
{
  // Enable support for dynamic title tags in the theme
  add_theme_support('title-tag');

  // Enable support for featured images (post thumbnails) in the theme
  add_theme_support('post-thumbnails');

  // Define a custom image size named 'professorLandscape' with dimensions 400x260 pixels, cropped to fit
  add_image_size('professorLandscape', 400, 260, true);

  // Define a custom image size named 'professorPortrait' with dimensions 480x650 pixels, cropped to fit
  add_image_size('professorPortrait', 480, 650, true);

  // Define a custom image size named 'pageBanner' with dimensions 1500x350 pixels, cropped to fit
  add_image_size('pageBanner', 1500, 350, true);

  // Enables the use of custom editor styles in the block editor
  add_theme_support('editor-styles');

  // Adds specific stylesheets for the block editor, including Google Fonts and custom CSS files
  add_editor_style(array('https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i', 'build/style-index.css', 'build/index.css'));
}

// Hook the 'university_features' function to the 'after_setup_theme' action, which runs after the theme is initialized
add_action('after_setup_theme', 'university_features');




/**************************************************************************************************************/

/**
 * 
 * This code modifies the main query for the 'event' custom post type archive page to display events in ascending order by date. 
 * It ensures that only future or current events are shown, based on the event_date custom field.
 * 
 */

// Function to modify the main WordPress query for the 'event' custom post type archive page
function university_adjust_queries($query)
{

  // Check if not in the admin dashboard, if viewing the 'program' custom post type archive, and if it's the main query
  if (!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
    $query->set('orderby', 'title');  // Set the query to order posts by their title
    $query->set('order', 'ASC'); // Set the order of the posts to ascending (A-Z)
    $query->set('posts_per_page', -1); // Display all posts without pagination (-1 means no limit)
  }


  // Check if not in the admin dashboard, if viewing the 'event' post type archive, and if it's the main query
  if (!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
    $today = date('Ymd'); // Get today's date in 'YYYYMMDD' format

    // Modify the query parameters to order the events by their 'event_date' custom field
    $query->set('meta_key', 'event_date'); // Specify the custom field to use for ordering
    $query->set('orderby', 'meta_value_num'); // Order by the numeric value of the custom field
    $query->set('order', 'ASC'); // Set the order to ascending, so the earliest events appear first

    // Set up a meta query to only include events with a date greater than or equal to today
    $query->set('meta_query', array(
      array(
        'key' => 'event_date', // The custom field that holds the event date
        'compare' => '>=', // Only include events with dates greater than or equal to today
        'value' => $today, // Today's date
        'type' => 'numeric' // Specify that the field is numeric for comparison
      )
    ));
  }
}

// Attach the 'university_adjust_queries' function to the 'pre_get_posts' action
add_action('pre_get_posts', 'university_adjust_queries');






/**************************************************************************************************************/

/**
 * Redirect subscriber accounts from the WordPress admin area to the homepage.
 * 
 * This function checks if the currently logged-in user has only the 'subscriber' role. 
 * If true, the user is redirected to the site's homepage, preventing access to the WordPress admin area.
 */

add_action('admin_init', 'redirectSubsToFrontend');

function redirectSubsToFrontend()
{
  $ourCurrentUser = wp_get_current_user();

  // If the current user has only one role, and that role is 'subscriber', redirect them to the homepage.
  if (count($ourCurrentUser->roles) == 1 && $ourCurrentUser->roles[0] == 'subscriber') {
    wp_redirect(site_url('/'));
    exit; // Ensure the script stops after the redirect.
  }
}





/**************************************************************************************************************/

/**
 * Removes the admin bar for subscribers.
 * 
 * This function checks the role of the currently logged-in user. If the user only has the 'subscriber' role, 
 * the black admin bar is disabled (hidden) on the front end.
 */

add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar()
{
  $ourCurrentUser = wp_get_current_user();

  // If the current user has only one role, and that role is 'subscriber', disable the admin bar.
  if (count($ourCurrentUser->roles) == 1 && $ourCurrentUser->roles[0] == 'subscriber') {
    show_admin_bar(false);
  }
}





/**************************************************************************************************************/

/**
 * Customize the login screen logo URL.
 * 
 * This function changes the link URL of the logo on the WordPress login screen to the site's homepage URL.
 */

add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl()
{
  // Return the site's homepage URL to replace the default WordPress link on the login screen logo.
  return esc_url(site_url('/'));
}





/**************************************************************************************************************/

/**
 * Enqueue custom styles for the login screen.
 * 
 * This function adds Google Fonts, Font Awesome icons, and theme-specific styles to the WordPress login screen.
 */

add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS()
{
  // Enqueue Google Fonts for Roboto and Roboto Condensed with various font weights.
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');

  // Enqueue Font Awesome to provide access to icons on the login screen.
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

  // Enqueue the main theme styles generated by the build process (used on the login screen).
  wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));

  // Enqueue additional theme styles for extra customizations.
  wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
}





/**************************************************************************************************************/

/**
 * Customize the login screen title.
 * 
 * This function changes the title attribute of the WordPress login screen logo to the site's name.
 */

add_filter('login_headertitle', 'ourLoginTitle');

function ourLoginTitle()
{
  // Return the site's name to replace the default 'Powered by WordPress' title on the login screen logo.
  return get_bloginfo('name');
}





/**************************************************************************************************************/

/**
 * This function ensures that any "note" post type created by users is automatically set to "private" 
 * and limits the number of notes a user can create to a maximum of 5. It also sanitizes the 
 * title and content fields of the "note" post type to prevent unwanted input.
 */

add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

function makeNotePrivate($data, $postarr)
{
  // Check if the post being inserted or updated is of post type 'note'
  if ($data['post_type'] == 'note') {

    // If the current user has created more than 4 notes and is creating a new one (ID is not set), terminate and display an error
    if (count_user_posts(get_current_user_id(), 'note') > 4 && !$postarr['ID']) {
      die("You have reached your note limit.");
    }

    // Sanitize the post title to ensure clean and safe data is saved
    $data['post_title'] = sanitize_text_field($data['post_title']);

    // Sanitize the post content to ensure clean and safe data is saved in the content
    $data['post_content'] = sanitize_textarea_field($data['post_content']);
  }

  // Ensure that all 'note' posts are saved with a status of 'private', unless they are in the trash
  if ($data['post_type'] == 'note' && $data['post_status'] != 'trash') {
    $data['post_status'] = 'private';
  }

  // Return the modified data to be saved in the database
  return $data;
}




/**************************************************************************************************************/

/**
 * Class JSXBlock - Registers a custom block type in WordPress using JavaScript and PHP.
 */
class JSXBlock
{

  // Constructor method that sets up the block properties and hooks into WordPress
  function __construct($name, $renderCallback = null, $data = null)
  {
    $this->name = $name; // Block name (used as the script and block handle)
    $this->data = $data; // Optional data to be localized for the script
    $this->renderCallback = $renderCallback; // Optional callback function for rendering the block
    add_action('init', [$this, 'onInit']); // Hook into the 'init' action to initialize the block
  }

  // Callback function for rendering block content in PHP (server-side rendering)
  function ourRenderCallback($attributes, $content)
  {
    ob_start(); // Start output buffering
    require get_theme_file_path("/our-blocks/{$this->name}.php"); // Load the PHP file corresponding to the block
    return ob_get_clean(); // Return the buffered output (HTML)
  }

  // Method to register the block and enqueue the associated JavaScript
  function onInit()
  {
    // Register the JavaScript file for the block, with dependencies on WordPress block and editor scripts
    wp_register_script($this->name, get_stylesheet_directory_uri() . "/build/{$this->name}.js", array('wp-blocks', 'wp-editor'));

    // Localize script with data if provided (e.g., to pass PHP data to JavaScript)
    if ($this->data) {
      wp_localize_script($this->name, $this->name, $this->data);
    }

    // Arguments for block registration, including editor script handle and optional render callback
    $ourArgs = array(
      'editor_script' => $this->name
    );

    // If a render callback is provided, add it to the block registration arguments
    if ($this->renderCallback) {
      $ourArgs['render_callback'] = [$this, 'ourRenderCallback'];
    }

    // Register the block type with WordPress, using the name and arguments
    register_block_type("ourblocktheme/{$this->name}", $ourArgs);
  }
}

// Instantiate new blocks using the JSXBlock class, each with its name, optional render callback, and data
new JSXBlock('banner', true, ['fallbackimage' => get_theme_file_uri('/images/library-hero.jpg')]); // Banner block with fallback image
new JSXBlock('genericheading'); // Generic heading block
new JSXBlock('genericbutton'); // Generic button block



/**************************************************************************************************************/

/**
 * Class for registering a custom placeholder block in WordPress.
 */
class PlaceholderBlock
{
  /**
   * Constructor to initialize the block with a specified name.
   *
   * @param string $name The name of the block.
   */
  function __construct($name)
  {
    $this->name = $name; // Store the block name
    add_action('init', [$this, 'onInit']); // Hook into WordPress init action to register the block
  }

  /**
   * Callback function for rendering the block on the frontend.
   *
   * @param array $attributes Block attributes.
   * @param string $content Inner content of the block.
   * @return string HTML output of the block.
   */
  function ourRenderCallback($attributes, $content)
  {
    ob_start(); // Start output buffering
    require get_theme_file_path("/our-blocks/{$this->name}.php"); // Include the block's PHP template file from the theme directory
    return ob_get_clean(); // Return the buffered output as a string
  }

  /**
   * Registers the block with WordPress, including JavaScript and render callback.
   */
  function onInit()
  {
    // Register the block's JavaScript file, defining dependencies on core block and editor scripts
    wp_register_script($this->name, get_stylesheet_directory_uri() . "/our-blocks/{$this->name}.js", array('wp-blocks', 'wp-editor'));

    // Register the block type with specified editor script and server-side render callback
    register_block_type("ourblocktheme/{$this->name}", array(
      'editor_script' => $this->name, // Reference to the registered JavaScript
      'render_callback' => [$this, 'ourRenderCallback'] // Server-side render callback function
    ));
  }
}


new PlaceholderBlock("eventsandblogs"); // Instantiate the PlaceholderBlock class to register the "eventsandblogs" block
new PlaceholderBlock("header"); // Instantiate the PlaceholderBlock class to register the "header" block
new PlaceholderBlock("footer"); // Instantiate the PlaceholderBlock class to register the "footer" block
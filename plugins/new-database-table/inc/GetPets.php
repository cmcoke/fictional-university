<?php

/**
 * Class to fetch pets from the database with specific filters, utilizing the $wpdb global object
 * for database interactions in WordPress.
 */


class GetPets
{
  /**
   * Constructor method that initializes necessary variables and executes queries to fetch pets.
   */
  function __construct()
  {
    global $wpdb; // Access the global $wpdb object to interact with the database.
    $tablename = $wpdb->prefix . 'pets'; // Define the table name with the correct WordPress table prefix.

    $this->args = $this->getArgs(); // Retrieve user-provided filters from the URL as an associative array.
    $this->placeholders = $this->createPlaceholders(); // Map values from $this->args for prepared statements.

    $query = "SELECT * FROM $tablename "; // Initialize the main query to fetch pet records.
    $countQuery = "SELECT COUNT(*) FROM $tablename "; // Initialize the count query to get the total number of matching pets.
    $query .= $this->createWhereText(); // Append the WHERE clause for the main query based on filters.
    $countQuery .= $this->createWhereText(); // Append the WHERE clause for the count query.
    $query .= " LIMIT 100"; // Limit results to 100 pets.

    // Execute queries using $wpdb's prepare method to prevent SQL injection.
    $this->count = $wpdb->get_var($wpdb->prepare($countQuery, $this->placeholders)); // Fetch the total count of matching pets.
    $this->pets = $wpdb->get_results($wpdb->prepare($query, $this->placeholders)); // Fetch the pet records that match the filters.
  }

  /**
   * Retrieves and sanitizes filter values from the URL (GET parameters) if they exist.
   *
   * @return array Filtered and sanitized associative array of filter arguments.
   */
  function getArgs()
  {
    $temp = []; // Initialize an empty array to store sanitized filters.

    // Check for each filter in the URL parameters, sanitize, and add to the array if present.
    if (isset($_GET['favcolor'])) $temp['favcolor'] = sanitize_text_field($_GET['favcolor']);
    if (isset($_GET['species'])) $temp['species'] = sanitize_text_field($_GET['species']);
    if (isset($_GET['minyear'])) $temp['minyear'] = sanitize_text_field($_GET['minyear']);
    if (isset($_GET['maxyear'])) $temp['maxyear'] = sanitize_text_field($_GET['maxyear']);
    if (isset($_GET['minweight'])) $temp['minweight'] = sanitize_text_field($_GET['minweight']);
    if (isset($_GET['maxweight'])) $temp['maxweight'] = sanitize_text_field($_GET['maxweight']);
    if (isset($_GET['favhobby'])) $temp['favhobby'] = sanitize_text_field($_GET['favhobby']);
    if (isset($_GET['favfood'])) $temp['favfood'] = sanitize_text_field($_GET['favfood']);

    return array_filter($temp, function ($x) {
      return $x; // Filter out empty values from the array.
    });
  }

  /**
   * Maps $this->args values into an array of placeholders for prepared statements.
   *
   * @return array Placeholders for use in SQL query preparation.
   */
  function createPlaceholders()
  {
    return array_map(function ($x) {
      return $x; // Map each argument value for use in SQL placeholders.
    }, $this->args);
  }

  /**
   * Builds the WHERE clause of the SQL query based on the filters in $this->args.
   *
   * @return string The complete WHERE clause with appropriate conditions.
   */
  function createWhereText()
  {
    $whereQuery = ""; // Initialize the WHERE clause text.

    if (count($this->args)) {
      $whereQuery = "WHERE "; // Start the WHERE clause if there are any filters.
    }

    $currentPosition = 0; // Track the current position in the $this->args array.

    foreach ($this->args as $index => $item) {
      $whereQuery .= $this->specificQuery($index); // Append specific condition for each filter.

      // Add 'AND' between conditions if it's not the last condition.
      if ($currentPosition != count($this->args) - 1) {
        $whereQuery .= " AND ";
      }
      $currentPosition++;
    }

    return $whereQuery; // Return the complete WHERE clause.
  }

  /**
   * Generates specific query condition based on the filter key.
   *
   * @param string $index The filter key (e.g., 'minweight', 'species').
   * @return string Condition for the SQL WHERE clause.
   */
  function specificQuery($index)
  {
    switch ($index) {
      case "minweight":
        return "petweight >= %d"; // Minimum weight filter.
      case "maxweight":
        return "petweight <= %d"; // Maximum weight filter.
      case "minyear":
        return "birthyear >= %d"; // Minimum birth year filter.
      case "maxyear":
        return "birthyear <= %d"; // Maximum birth year filter.
      default:
        return $index . " = %s"; // General condition for other filters using string placeholders.
    }
  }
}
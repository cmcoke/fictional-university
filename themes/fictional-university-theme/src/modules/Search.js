import $ from "jquery"; // Import jQuery

// Define the Search class
class Search {

  // 1. Describe and create/initiate our object
  constructor() {
    // Select the DOM element where search results will be displayed
    this.resultsDiv = $("#search-overlay__results");

    // Select the button that triggers the search overlay
    this.openButton = $(".js-search-trigger");

    // Select the button that closes the search overlay
    this.closeButton = $(".search-overlay__close");

    // Select the search overlay element
    this.searchOverlay = $(".search-overlay");

    // Select the search input field
    this.searchField = $("#search-term");

    // Call the events method to bind event handlers
    this.events();

    // Initialize a flag to track whether the search overlay is open
    this.isOverlayOpen = false;

    // Initialize a flag to track whether the loading spinner is visible
    this.isSpinnerVisible = false;

    // Store the previous value of the search input field
    this.previousValue;

    // Initialize a timer variable for the typing logic
    this.typingTimer;
  }

  // 2. Define events and bind them to the class methods
  events() {
    // Bind the click event on the openButton to the openOverlay method
    this.openButton.on("click", this.openOverlay.bind(this));

    // Bind the click event on the closeButton to the closeOverlay method
    this.closeButton.on("click", this.closeOverlay.bind(this));

    // Bind the keydown event on the document to the keyPressDispatcher method
    $(document).on("keydown", this.keyPressDispatcher.bind(this));

    // Bind the keyup event on the searchField to the typingLogic method
    this.searchField.on("keyup", this.typingLogic.bind(this));
  }

  // 3. Define methods (function, action...)
  typingLogic() {
    // Check if the current value of the searchField differs from the previous value
    if (this.searchField.val() != this.previousValue) {

      // Clear any existing typing timer
      clearTimeout(this.typingTimer);

      // If there is a value in the searchField
      if (this.searchField.val()) {

        // If the spinner is not already visible, show it
        if (!this.isSpinnerVisible) {
          this.resultsDiv.html('<div class="spinner-loader"></div>');
          this.isSpinnerVisible = true;
        }

        // Set a timer to trigger the getResults method after 2 seconds
        this.typingTimer = setTimeout(this.getResults.bind(this), 2000);
      } else {
        // If the search field is empty, clear the results and hide the spinner
        this.resultsDiv.html("");
        this.isSpinnerVisible = false;
      }
    }

    // Store the current value of the searchField as the previous value
    this.previousValue = this.searchField.val();
  }

  getResults() {
    // Display placeholder search results
    this.resultsDiv.html("Imagine real search results here...");

    // Hide the spinner
    this.isSpinnerVisible = false;
  }

  keyPressDispatcher(e) {

    // console.log(e.keyCode);

    // Check for 's' key press (keyCode 83) to open the search overlay
    if (e.keyCode == 83 && !this.isOverlayOpen && !$("input, textarea").is(":focus")) {
      this.openOverlay();
    }

    // Check for 'Escape' key press (keyCode 27) to close the search overlay
    if (e.keyCode == 27 && this.isOverlayOpen) {
      this.closeOverlay();
    }
  }

  openOverlay() {
    // Add the class to make the search overlay visible
    this.searchOverlay.addClass("search-overlay--active");

    // Prevent body scrolling when overlay is open
    $("body").addClass("body-no-scroll");

    console.log("our open method just ran!"); // Log a message to the console

    // Set the overlay state to open
    this.isOverlayOpen = true;
  }

  closeOverlay() {
    // Remove the class to hide the search overlay
    this.searchOverlay.removeClass("search-overlay--active");

    // Allow body scrolling when overlay is closed
    $("body").removeClass("body-no-scroll");

    console.log("our close method just ran!"); // Log a message to the console

    // Set the overlay state to closed
    this.isOverlayOpen = false;
  }
}

// Export the Search class as the default export
export default Search;

import axios from "axios"; // Import the axios library for making HTTP requests

class Search {
  // 1. Constructor to initialize the Search object and set up properties and methods.
  constructor() {
    this.addSearchHTML(); // Add the search HTML structure to the page.
    this.resultsDiv = document.querySelector("#search-overlay__results"); // Element to display search results.
    this.openButton = document.querySelectorAll(".js-search-trigger"); // Buttons that trigger the search overlay.
    this.closeButton = document.querySelector(".search-overlay__close"); // Close button for the search overlay.
    this.searchOverlay = document.querySelector(".search-overlay"); // The search overlay element.
    this.searchField = document.querySelector("#search-term"); // Input field for the search term.
    this.isOverlayOpen = false; // Track whether the overlay is open.
    this.isSpinnerVisible = false; // Track whether the loading spinner is visible.
    this.previousValue; // Store the previous value entered in the search field.
    this.typingTimer; // Timer to control search requests.
    this.events(); // Set up event listeners for user interaction.
  }

  // 2. Set up event listeners for the search functionality.
  events() {
    // Add a click event listener to each search trigger button.
    this.openButton.forEach(el => {
      el.addEventListener("click", e => {
        e.preventDefault();
        this.openOverlay(); // Open the search overlay when the button is clicked.
      });
    });

    // Add click event listener to the close button.
    this.closeButton.addEventListener("click", () => this.closeOverlay()); // Close the overlay on button click.

    // Listen for keyboard events (e.g., pressing keys).
    document.addEventListener("keydown", e => this.keyPressDispatcher(e)); // Handle key presses like "Escape" or "s".

    // Detect typing in the search field.
    this.searchField.addEventListener("keyup", () => this.typingLogic()); // Handle typing in the search field.
  }

  // 3. Define methods (functions) for search logic.

  // Logic to handle typing in the search field and triggering the search.
  typingLogic() {
    // Check if the input value has changed since the last keystroke.
    if (this.searchField.value != this.previousValue) {
      clearTimeout(this.typingTimer); // Clear any previous timer.

      // If the search field is not empty, display a spinner while waiting for search results.
      if (this.searchField.value) {
        if (!this.isSpinnerVisible) {
          this.resultsDiv.innerHTML = '<div class="spinner-loader"></div>'; // Show loading spinner.
          this.isSpinnerVisible = true; // Set spinner visibility to true.
        }
        this.typingTimer = setTimeout(this.getResults.bind(this), 750); // Wait 750ms before making the search request.
      } else {
        this.resultsDiv.innerHTML = ""; // Clear results if the search field is empty.
        this.isSpinnerVisible = false; // Set spinner visibility to false.
      }
    }

    // Update the previous value to the current search field value.
    this.previousValue = this.searchField.value;
  }

  // Method to get search results using an asynchronous HTTP request.
  async getResults() {
    try {
      const response = await axios.get(universityData.root_url + "/wp-json/university/v1/search?term=" + this.searchField.value); // Fetch results from the API.
      const results = response.data; // Extract search results data.

      // Display the search results in HTML format.
      this.resultsDiv.innerHTML = `
        <div class="row">
          <div class="one-third">
            <h2 class="search-overlay__section-title">General Information</h2>
            ${results.generalInfo.length ? '<ul class="link-list min-list">' : "<p>No general information matches that search.</p>"}
              ${results.generalInfo.map(item => `<li><a href="${item.permalink}">${item.title}</a> ${item.postType == "post" ? `by ${item.authorName}` : ""}</li>`).join("")}
            ${results.generalInfo.length ? "</ul>" : ""}
          </div>
          <div class="one-third">
            <h2 class="search-overlay__section-title">Programs</h2>
            ${results.programs.length ? '<ul class="link-list min-list">' : `<p>No programs match that search. <a href="${universityData.root_url}/programs">View all programs</a></p>`}
              ${results.programs.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join("")}
            ${results.programs.length ? "</ul>" : ""}

            <h2 class="search-overlay__section-title">Professors</h2>
            ${results.professors.length ? '<ul class="professor-cards">' : `<p>No professors match that search.</p>`}
              ${results.professors
          .map(
            item => `
                <li class="professor-card__list-item">
                  <a class="professor-card" href="${item.permalink}">
                    <img class="professor-card__image" src="${item.image}">
                    <span class="professor-card__name">${item.title}</span>
                  </a>
                </li>
              `
          )
          .join("")}
            ${results.professors.length ? "</ul>" : ""}

          </div>
          <div class="one-third">
            <h2 class="search-overlay__section-title">Campuses</h2>
            ${results.campuses.length ? '<ul class="link-list min-list">' : `<p>No campuses match that search. <a href="${universityData.root_url}/campuses">View all campuses</a></p>`}
              ${results.campuses.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join("")}
            ${results.campuses.length ? "</ul>" : ""}

            <h2 class="search-overlay__section-title">Events</h2>
            ${results.events.length ? "" : `<p>No events match that search. <a href="${universityData.root_url}/events">View all events</a></p>`}
              ${results.events
          .map(
            item => `
                <div class="event-summary">
                  <a class="event-summary__date t-center" href="${item.permalink}">
                    <span class="event-summary__month">${item.month}</span>
                    <span class="event-summary__day">${item.day}</span>  
                  </a>
                  <div class="event-summary__content">
                    <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
                    <p>${item.description} <a href="${item.permalink}" class="nu gray">Learn more</a></p>
                  </div>
                </div>
              `
          )
          .join("")}

          </div>
        </div>
      `;
      this.isSpinnerVisible = false; // Hide the spinner after results are displayed.
    } catch (e) {
      console.log(e); // Log any errors that occur during the request.
    }
  }

  // Method to handle key press events.
  keyPressDispatcher(e) {
    // If the "s" key is pressed and the overlay is not open, and the user is not typing in an input or textarea, open the search overlay.
    if (e.keyCode == 83 && !this.isOverlayOpen && document.activeElement.tagName != "INPUT" && document.activeElement.tagName != "TEXTAREA") {
      this.openOverlay();
    }

    // If the "Escape" key is pressed while the overlay is open, close it.
    if (e.keyCode == 27 && this.isOverlayOpen) {
      this.closeOverlay();
    }
  }

  // Method to open the search overlay.
  openOverlay() {
    this.searchOverlay.classList.add("search-overlay--active"); // Add a class to make the overlay visible.
    document.body.classList.add("body-no-scroll"); // Prevent the body from scrolling while the overlay is open.
    this.searchField.value = ""; // Clear the search field when the overlay opens.
    setTimeout(() => this.searchField.focus(), 301); // Focus on the search field after a small delay.
    this.isOverlayOpen = true; // Mark the overlay as open.
    return false;
  }

  // Method to close the search overlay.
  closeOverlay() {
    this.searchOverlay.classList.remove("search-overlay--active"); // Remove the class that makes the overlay visible.
    document.body.classList.remove("body-no-scroll"); // Re-enable body scrolling.
    this.isOverlayOpen = false; // Mark the overlay as closed.
  }

  // Method to add the search overlay HTML to the DOM.
  addSearchHTML() {
    document.body.insertAdjacentHTML(
      "beforeend",
      `
      <div class="search-overlay">
        <div class="search-overlay__top">
          <div class="container">
            <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
            <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
            <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
          </div>
        </div>
        
        <div class="container">
          <div id="search-overlay__results"></div>
        </div>
      </div>
    `
    );
  }
}

export default Search; // Export the Search class for use in other files.

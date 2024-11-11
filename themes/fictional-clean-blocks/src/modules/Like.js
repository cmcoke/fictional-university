/**
 * 
 * This code handles the liking and unliking of professor posts in a WordPress theme. 
 * It uses the WordPress REST API and axios to send AJAX requests for liking or 
 * unliking posts, and updates the UI to reflect the like status. 
 * It checks if the user is logged in, and then either creates or deletes 
 * a "like" entry in the database based on user interaction with the like button.
 */

// Import the axios library for making HTTP requests
import axios from "axios";

// Define the Like class to handle like/unlike functionality
class Like {
  // Constructor method is called when a new instance of Like is created
  constructor() {
    // Check if any element with the class "like-box" exists on the page
    if (document.querySelector(".like-box")) {
      // Set the default header for axios requests to include the WordPress nonce for security
      axios.defaults.headers.common["X-WP-Nonce"] = universityData.nonce;

      // Call the events method to set up event listeners
      this.events();
    }
  }

  // Method to set up event listeners
  events() {
    // Add a click event listener to the first element with the class "like-box"
    document.querySelector(".like-box").addEventListener("click", e => this.ourClickDispatcher(e));
  }

  // Method to handle click events on the like button
  ourClickDispatcher(e) {
    // Initialize variable to store the current like box element (the clicked element or its parent)
    let currentLikeBox = e.target;

    // Traverse up the DOM until an element with the class "like-box" is found
    while (!currentLikeBox.classList.contains("like-box")) {
      currentLikeBox = currentLikeBox.parentElement;
    }

    // Check if the like already exists (based on a data attribute)
    if (currentLikeBox.getAttribute("data-exists") == "yes") {
      // If the like exists, call the deleteLike method
      this.deleteLike(currentLikeBox);
    } else {
      // If the like does not exist, call the createLike method
      this.createLike(currentLikeBox);
    }
  }

  // Method to create a new like via an API call
  async createLike(currentLikeBox) {
    try {
      // Send a POST request to the REST API to create a like for the professor
      const response = await axios.post(universityData.root_url + "/wp-json/university/v1/manageLike", {
        "professorId": currentLikeBox.getAttribute("data-professor")
      });

      // If the response does not indicate an unauthorized user, update the like status
      if (response.data != "Only logged in users can create a like.") {
        // Set the data attribute to indicate the like now exists
        currentLikeBox.setAttribute("data-exists", "yes");

        // Update the like count displayed in the UI
        var likeCount = parseInt(currentLikeBox.querySelector(".like-count").innerHTML, 10);
        likeCount++;
        currentLikeBox.querySelector(".like-count").innerHTML = likeCount;

        // Store the newly created like's ID in the element's data attribute
        currentLikeBox.setAttribute("data-like", response.data);
      }

      // Log the response for debugging purposes
      console.log(response.data);
    } catch (e) {
      // If an error occurs during the request, log "Sorry" in the console
      console.log("Sorry");
    }
  }

  // Method to delete an existing like via an API call
  async deleteLike(currentLikeBox) {
    try {
      // Send a DELETE request to the REST API to remove the like for the professor
      const response = await axios({
        url: universityData.root_url + "/wp-json/university/v1/manageLike", // REST API endpoint
        method: 'delete', // HTTP method
        data: { "like": currentLikeBox.getAttribute("data-like") }, // Send the ID of the like to delete
      });

      // Set the data attribute to indicate the like no longer exists
      currentLikeBox.setAttribute("data-exists", "no");

      // Update the like count displayed in the UI
      var likeCount = parseInt(currentLikeBox.querySelector(".like-count").innerHTML, 10);
      likeCount--;
      currentLikeBox.querySelector(".like-count").innerHTML = likeCount;

      // Clear the data-like attribute (like no longer exists)
      currentLikeBox.setAttribute("data-like", "");

      // Log the response for debugging purposes
      console.log(response.data);
    } catch (e) {
      // If an error occurs during the request, log the error in the console
      console.log(e);
    }
  }
}

// Export the Like class as the default export of the module
export default Like;

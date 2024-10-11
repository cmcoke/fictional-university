import axios from "axios";

class MyNotes {
  /**
   * This JavaScript class is responsible for managing user-created notes on a WordPress site.
   * It uses the WordPress REST API (via Axios) to allow logged-in users to:
   * - Create new notes.
   * - Edit or delete their existing notes.
   * - Update notes dynamically without reloading the page.
   * The class is initialized only if the notes section ("#my-notes") is present on the page.
   */
  constructor() {
    // Check if the notes section exists on the page
    if (document.querySelector("#my-notes")) {
      // Set the Axios header for security using the WP Nonce
      axios.defaults.headers.common["X-WP-Nonce"] = universityData.nonce;
      this.myNotes = document.querySelector("#my-notes");
      this.events(); // Bind event listeners
    }
  }

  /**
   * Bind events to DOM elements:
   * - Detect clicks for editing, deleting, or updating notes.
   * - Detect clicks to create new notes.
   */
  events() {
    // Handle click events for notes (edit, delete, update)
    this.myNotes.addEventListener("click", e => this.clickHandler(e));

    // Handle click event for creating a new note
    document.querySelector(".submit-note").addEventListener("click", () => this.createNote());
  }

  /**
   * Event handler to determine if the user clicked on delete, edit, or update buttons.
   * @param {Event} e - The event object triggered by a user action.
   */
  clickHandler(e) {
    // If the delete icon or button is clicked
    if (e.target.classList.contains("delete-note") || e.target.classList.contains("fa-trash-o")) {
      this.deleteNote(e);
    }

    // If the edit or cancel icon/button is clicked
    if (e.target.classList.contains("edit-note") || e.target.classList.contains("fa-pencil") || e.target.classList.contains("fa-times")) {
      this.editNote(e);
    }

    // If the update icon/button is clicked
    if (e.target.classList.contains("update-note") || e.target.classList.contains("fa-arrow-right")) {
      this.updateNote(e);
    }
  }

  /**
   * Find the nearest parent <li> element (the container for a note).
   * This ensures actions are targeting the correct note.
   * @param {HTMLElement} el - The element where the event was triggered.
   * @returns {HTMLElement} - The parent <li> element containing the note.
   */
  findNearestParentLi(el) {
    let thisNote = el;
    // Traverse up the DOM tree until the <li> element is found
    while (thisNote.tagName != "LI") {
      thisNote = thisNote.parentElement;
    }
    return thisNote;
  }

  /**
   * Toggle between editable and read-only states for a note.
   * @param {Event} e - The event object triggered by a user action.
   */
  editNote(e) {

    const thisNote = this.findNearestParentLi(e.target);

    // If the note is in "editable" state, make it read-only
    if (thisNote.getAttribute("data-state") == "editable") {
      this.makeNoteReadOnly(thisNote);
    } else {
      // Otherwise, make the note editable
      this.makeNoteEditable(thisNote);
    }

  }

  /**
   * Make a note editable, allowing the user to change its title and content.
   * @param {HTMLElement} thisNote - The note element to be made editable.
   */
  makeNoteEditable(thisNote) {
    thisNote.querySelector(".edit-note").innerHTML = '<i class="fa fa-times" aria-hidden="true"></i> Cancel';
    thisNote.querySelector(".note-title-field").removeAttribute("readonly");
    thisNote.querySelector(".note-body-field").removeAttribute("readonly");
    thisNote.querySelector(".note-title-field").classList.add("note-active-field");
    thisNote.querySelector(".note-body-field").classList.add("note-active-field");
    thisNote.querySelector(".update-note").classList.add("update-note--visible");
    thisNote.setAttribute("data-state", "editable");
  }

  /**
   * Make a note read-only, preventing further editing.
   * @param {HTMLElement} thisNote - The note element to be made read-only.
   */
  makeNoteReadOnly(thisNote) {
    thisNote.querySelector(".edit-note").innerHTML = '<i class="fa fa-pencil" aria-hidden="true"></i> Edit';
    thisNote.querySelector(".note-title-field").setAttribute("readonly", "true");
    thisNote.querySelector(".note-body-field").setAttribute("readonly", "true");
    thisNote.querySelector(".note-title-field").classList.remove("note-active-field");
    thisNote.querySelector(".note-body-field").classList.remove("note-active-field");
    thisNote.querySelector(".update-note").classList.remove("update-note--visible");
    thisNote.setAttribute("data-state", "cancel");
  }

  /**
   * Delete a note via the WordPress REST API.
   * @param {Event} e - The event object triggered by a user action.
   */
  async deleteNote(e) {
    const thisNote = this.findNearestParentLi(e.target);

    try {
      // Send DELETE request to remove the note using the REST API
      const response = await axios.delete(universityData.root_url + "/wp-json/wp/v2/note/" + thisNote.getAttribute("data-id"));

      // Animate the removal of the note element
      thisNote.style.height = `${thisNote.offsetHeight}px`;

      setTimeout(function () {
        thisNote.classList.add("fade-out");
      }, 20);

      setTimeout(function () {
        thisNote.remove();
      }, 401);

      // If user is under the note limit, show a message
      if (response.data.userNoteCount < 5) {
        document.querySelector(".note-limit-message").classList.remove("active");
      }

    } catch (e) {
      console.log("Sorry"); // Handle any errors
    }
  }

  /**
   * Update a note via the WordPress REST API.
   * @param {Event} e - The event object triggered by a user action.
   */
  async updateNote(e) {
    const thisNote = this.findNearestParentLi(e.target);

    // Prepare the updated note data
    var ourUpdatedPost = {
      "title": thisNote.querySelector(".note-title-field").value,
      "content": thisNote.querySelector(".note-body-field").value
    };

    try {

      // Send POST request to update the note using the REST API
      const response = await axios.post(universityData.root_url + "/wp-json/wp/v2/note/" + thisNote.getAttribute("data-id"), ourUpdatedPost);

      this.makeNoteReadOnly(thisNote); // Make the note read-only after updating

    } catch (e) {
      console.log("Sorry"); // Handle any errors
    }
  }

  /**
   * Create a new note via the WordPress REST API.
   */
  async createNote() {
    // Prepare the new note data
    var ourNewPost = {
      "title": document.querySelector(".new-note-title").value,
      "content": document.querySelector(".new-note-body").value,
      "status": "publish"
    };

    try {
      // Send POST request to create a new note using the REST API
      const response = await axios.post(universityData.root_url + "/wp-json/wp/v2/note/", ourNewPost);

      // If the note limit hasn't been reached, insert the new note into the DOM
      if (response.data != "You have reached your note limit.") {
        document.querySelector(".new-note-title").value = "";
        document.querySelector(".new-note-body").value = "";
        document.querySelector("#my-notes").insertAdjacentHTML(
          "afterbegin",
          `<li data-id="${response.data.id}" class="fade-in-calc">
            <input readonly class="note-title-field" value="${response.data.title.raw}">
            <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
            <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
            <textarea readonly class="note-body-field">${response.data.content.raw}</textarea>
            <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
          </li>`
        );


        // notice in the above HTML for the new <li> I gave it a class of fade-in-calc which will make it invisible temporarily so we can count its natural height

        let finalHeight; // browser needs a specific height to transition to, you can't transition to 'auto' height

        let newlyCreated = document.querySelector("#my-notes li");

        // give the browser 30 milliseconds to have the invisible element added to the DOM before moving on
        setTimeout(function () {
          finalHeight = `${newlyCreated.offsetHeight}px`;
          newlyCreated.style.height = "0px";
        }, 30);

        // give the browser another 20 milliseconds to count the height of the invisible element before moving on
        setTimeout(function () {
          newlyCreated.classList.remove("fade-in-calc");
          newlyCreated.style.height = finalHeight;
        }, 50);

        // wait the duration of the CSS transition before removing the hardcoded calculated height from the element so that our design is responsive once again
        setTimeout(function () {
          newlyCreated.style.removeProperty("height");
        }, 450);

      } else {
        document.querySelector(".note-limit-message").classList.add("active"); // Display message if note limit reached
      }
    } catch (e) {
      console.error(e); // Handle any errors
    }
  }
}

export default MyNotes;

import Glide from "@glidejs/glide"; // Importing the Glide.js library to control the slider functionality.

class HeroSlider {
  constructor() {
    // Select all elements with the 'hero-slider' class, which will contain individual sliders.
    const allSlideshows = document.querySelectorAll('.hero-slider');

    // Loop through each 'hero-slider' to apply the Glide.js functionality to each slideshow.
    allSlideshows.forEach(function (currentSlideshow) {

      // Count how many slides are in the current slideshow by selecting elements with the 'hero-slider__slide' class.
      const dotCount = currentSlideshow.querySelectorAll(".hero-slider__slide").length;

      // Generate the HTML for the navigation dots based on the number of slides.
      let dotHTML = "";
      for (let i = 0; i < dotCount; i++) {
        // Create a button for each dot and set the 'data-glide-dir' attribute for Glide.js to link to the slide.
        dotHTML += `<button class="slider__bullet glide__bullet" data-glide-dir="=${i}"></button>`;
      }

      // Insert the generated dot HTML into the '.glide__bullets' container inside the current slideshow.
      currentSlideshow.querySelector(".glide__bullets").insertAdjacentHTML("beforeend", dotHTML);

      // Initialize the Glide.js slider for the current slideshow with specific settings.
      var glide = new Glide(currentSlideshow, {
        type: "carousel",  // Carousel type means slides will loop continuously.
        perView: 1,        // Display one slide at a time.
        autoplay: 3000     // Automatically move to the next slide every 3000 milliseconds (3 seconds).
      });

      glide.mount(); // Mounts the Glide instance, initializing the slider functionality.

    });
  }
}

export default HeroSlider; // Exports the HeroSlider class for use elsewhere in the project.

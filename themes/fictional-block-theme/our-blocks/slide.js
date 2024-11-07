/**
 * Registers a custom "Slide" block type in WordPress with the following features:
 * - The block supports full-width alignment and includes attributes for theme image, alignment, image ID, and image URL.
 * - The `EditComponent` manages block content in the editor, allowing users to select an image for the slide background and place nested blocks (like heading or button).
 * - It fetches the image URL when the `imgID` is selected and updates the image URL accordingly using the WordPress REST API.
 * - The `SaveComponent` outputs the content of any inner blocks when the block is saved.
 * - The `InspectorControls` panel enables the user to upload a background image from the media library.
 */

// Import necessary dependencies and components from WordPress packages
import apiFetch from "@wordpress/api-fetch"; // Import apiFetch to make API requests
import { Button, PanelBody, PanelRow } from "@wordpress/components"; // Import UI components for the block settings panel
import { InnerBlocks, InspectorControls, MediaUpload, MediaUploadCheck } from "@wordpress/block-editor"; // Import components for block editor and media upload functionality
import { registerBlockType } from "@wordpress/blocks"; // Import registerBlockType to register custom block types
import { useEffect } from "@wordpress/element"; // Import useEffect for handling side effects in the component

// Register a new custom block type 'slide'
registerBlockType("ourblocktheme/slide", {
  title: "Slide", // Set the block title in the block editor
  supports: {
    align: ["full"] // Support full-width alignment for the block
  },
  attributes: {
    themeimage: { type: "string" }, // Attribute for storing the theme image (string)
    align: { type: "string", default: "full" }, // Attribute for alignment, default is full width
    imgID: { type: "number" }, // Attribute to store the image ID
    imgURL: { type: "string", default: banner.fallbackimage } // Attribute for storing the image URL, with a fallback image
  },
  edit: EditComponent, // Edit component for rendering the block in the editor
  save: SaveComponent // Save component for rendering the block content on the front end
});

// Edit component for the slide block
function EditComponent(props) {

  // Effect hook to update the imgURL when themeimage is set
  useEffect(function () {
    if (props.attributes.themeimage) {
      // Set imgURL using the themeimage path and the selected themeimage
      props.setAttributes({ imgURL: `${slide.themeimagepath}${props.attributes.themeimage}` });
    }
  }, []); // The empty dependency array ensures this effect runs only once, on mount

  // Effect hook to update imgURL based on the imgID
  useEffect(
    function () {
      if (props.attributes.imgID) {
        async function go() {
          // Fetch the media details using the WordPress REST API
          const response = await apiFetch({
            path: `/wp/v2/media/${props.attributes.imgID}`,
            method: "GET"
          });
          // Set the image URL using the fetched media details
          props.setAttributes({ themeimage: "", imgURL: response.media_details.sizes.pageBanner.source_url });
        }
        go(); // Call the async function to fetch image data
      }
    },
    [props.attributes.imgID] // Dependency array to re-run this effect when imgID changes
  );

  // Handler for when a file is selected from the media library
  function onFileSelect(x) {
    // Set the imgID attribute with the selected image's ID
    props.setAttributes({ imgID: x.id });
  }

  // Block editor UI rendering
  return (
    <>
      {/* Inspector Controls for block settings in the sidebar */}
      <InspectorControls>
        <PanelBody title="Background" initialOpen={true}> {/* Panel for background settings */}
          <PanelRow>
            {/* MediaUploadCheck ensures the user has permission to upload media */}
            <MediaUploadCheck>
              {/* MediaUpload component for selecting an image */}
              <MediaUpload
                onSelect={onFileSelect} // Handler for when a file is selected
                value={props.attributes.imgID} // Current selected image ID
                render={({ open }) => {
                  return <Button onClick={open}>Choose Image</Button>; // Render a button to open the media library
                }}
              />
            </MediaUploadCheck>
          </PanelRow>
        </PanelBody>
      </InspectorControls>

      {/* Render the block's visual layout */}
      <div className="hero-slider__slide" style={{ backgroundImage: `url('${props.attributes.imgURL}')` }}>
        {/* Background image is applied inline, based on imgURL attribute */}
        <div className="hero-slider__interior container">
          <div className="hero-slider__overlay t-center">
            {/* InnerBlocks allows other blocks to be added inside this block */}
            <InnerBlocks allowedBlocks={["ourblocktheme/genericheading", "ourblocktheme/genericbutton"]} />
          </div>
        </div>
      </div>
    </>
  );
}

// Save component for rendering the content on the front end (server-side rendering)
function SaveComponent() {
  return <InnerBlocks.Content />; // Output the inner blocks content when the block is saved
}

/**
 * WordPress custom block for creating a banner with an optional background image.
 */

import apiFetch from "@wordpress/api-fetch"; // Allows fetching data from WordPress REST API
import { Button, PanelBody, PanelRow } from "@wordpress/components"; // WordPress UI components for the editor
import { InnerBlocks, InspectorControls, MediaUpload, MediaUploadCheck } from "@wordpress/block-editor"; // Editor components for block creation
import { registerBlockType } from "@wordpress/blocks"; // Method to register a custom block
import { useEffect } from "@wordpress/element"; // React hook for handling side effects

// Register the "banner" block type with specific attributes and components
registerBlockType("ourblocktheme/banner", {
  title: "Banner", // Block title displayed in the editor
  supports: {
    align: ["full"] // Allows full-width alignment support in the editor
  },
  attributes: {
    align: { type: "string", default: "full" }, // Alignment attribute, defaulting to full-width
    imgID: { type: "number" }, // Stores the ID of the selected image
    imgURL: { type: "string", default: banner.fallbackimage } // Stores the URL of the selected image or a fallback image
  },
  edit: EditComponent, // Component for block editor interface
  save: SaveComponent // Component for block frontend rendering
});

// Component rendering the block editor interface
function EditComponent(props) {
  // useEffect hook to fetch image URL whenever the image ID is set or updated
  useEffect(
    function () {
      if (props.attributes.imgID) { // Only run if an image ID is present
        async function go() {
          const response = await apiFetch({
            path: `/wp/v2/media/${props.attributes.imgID}`, // Fetches media details based on the image ID
            method: "GET"
          });
          // Update imgURL attribute with the image URL from the media details
          props.setAttributes({ imgURL: response.media_details.sizes.pageBanner.source_url });
        }
        go(); // Call the async function to fetch image data
      }
    },
    [props.attributes.imgID] // Effect dependency array, triggers when imgID changes
  );

  // Handler for selecting an image, setting the image ID attribute
  function onFileSelect(x) {
    props.setAttributes({ imgID: x.id });
  }

  return (
    <>
      {/* Inspector Controls panel for selecting background image */}
      <InspectorControls>
        <PanelBody title="Background" initialOpen={true}>
          <PanelRow>
            <MediaUploadCheck>
              <MediaUpload
                onSelect={onFileSelect} // Sets imgID on image selection
                value={props.attributes.imgID} // Current image ID
                render={({ open }) => {
                  return <Button onClick={open}>Choose Image</Button>; // Opens media library on click
                }}
              />
            </MediaUploadCheck>
          </PanelRow>
        </PanelBody>
      </InspectorControls>

      {/* Block content preview with the selected background image */}
      <div className="page-banner">
        {/* Background image is set via inline style using imgURL attribute */}
        <div className="page-banner__bg-image" style={{ backgroundImage: `url('${props.attributes.imgURL}')` }}></div>
        <div className="page-banner__content container t-center c-white">
          <InnerBlocks allowedBlocks={["ourblocktheme/genericheading", "ourblocktheme/genericbutton"]} /> {/* Allows inner blocks */}
        </div>
      </div>
    </>
  );
}

// Save function defining frontend HTML output for the block
function SaveComponent() {
  return <InnerBlocks.Content />; // Saves inner blocks content for frontend display
}

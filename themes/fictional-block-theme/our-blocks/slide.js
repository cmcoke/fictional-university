/**
 * Custom block for a slide component in WordPress.
 */

import apiFetch from "@wordpress/api-fetch";
import { Button, PanelBody, PanelRow } from "@wordpress/components";
import { InnerBlocks, InspectorControls, MediaUpload, MediaUploadCheck } from "@wordpress/block-editor";
import { registerBlockType } from "@wordpress/blocks";
import { useEffect } from "@wordpress/element";

// Registering the block type with block settings
registerBlockType("ourblocktheme/slide", {
  title: "Slide", // Block title in the editor
  supports: {
    align: ["full"] // Allows block to be aligned full-width
  },
  attributes: {
    align: { type: "string", default: "full" }, // Alignment attribute with default full-width
    imgID: { type: "number" }, // Image ID for selected media
    imgURL: { type: "string", default: banner.fallbackimage } // Image URL with a default fallback image
  },
  edit: EditComponent, // The editor component for this block
  save: SaveComponent // The frontend render function for this block
});

// The Edit component that renders the block interface in the editor
function EditComponent(props) {
  // useEffect to fetch image URL if an image ID is present
  useEffect(
    function () {
      if (props.attributes.imgID) {
        async function go() {
          // Fetching media details for selected image ID
          const response = await apiFetch({
            path: `/wp/v2/media/${props.attributes.imgID}`, // REST API request to get media
            method: "GET"
          });
          // Setting imgURL attribute with the selected image URL
          props.setAttributes({ imgURL: response.media_details.sizes.pageBanner.source_url });
        }
        go();
      }
    },
    [props.attributes.imgID] // Dependency on imgID attribute
  );

  // Updates imgID attribute when a file is selected
  function onFileSelect(x) {
    props.setAttributes({ imgID: x.id });
  }

  return (
    <>
      {/* Block settings panel in the editor sidebar */}
      <InspectorControls>
        <PanelBody title="Background" initialOpen={true}>
          <PanelRow>
            <MediaUploadCheck>
              <MediaUpload
                onSelect={onFileSelect} // Triggers onFileSelect when an image is chosen
                value={props.attributes.imgID} // Current image ID attribute
                render={({ open }) => {
                  return <Button onClick={open}>Choose Image</Button>; // Button to open media library
                }}
              />
            </MediaUploadCheck>
          </PanelRow>
        </PanelBody>
      </InspectorControls>

      {/* Slide layout with a background image and inner blocks */}
      <div className="hero-slider__slide" style={{ backgroundImage: `url('${props.attributes.imgURL}')` }}>
        <div className="hero-slider__interior container">
          <div className="hero-slider__overlay t-center">
            {/* Allows nested blocks, limited to specific types */}
            <InnerBlocks allowedBlocks={["ourblocktheme/genericheading", "ourblocktheme/genericbutton"]} />
          </div>
        </div>
      </div>
    </>
  );
}

// The Save component that defines the front-end output of the block
function SaveComponent() {
  return <InnerBlocks.Content />; // Renders nested blocks in the saved output
}

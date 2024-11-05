/**
 * Register a custom "Banner" block for WordPress with editable and save components
 */

import { InnerBlocks } from "@wordpress/block-editor"; // Imports InnerBlocks for nesting blocks
import { registerBlockType } from "@wordpress/blocks"; // Imports registerBlockType for block registration
import bgImage from "../images/library-hero.jpg"; // Imports a background image for the banner

// Registers the "Banner" block with its title, edit component, and save component
registerBlockType("ourblocktheme/banner", {
  title: "Banner", // Title of the block shown in the editor
  edit: EditComponent, // Function defining the block's editing interface
  save: SaveComponent // Function defining the block's saved output
});

// Function defining the editor interface for the Banner block
function EditComponent() {

  // Defines reusable JSX structure for later use if needed
  const useMeLater = (
    <>
      <h1 className="headline headline--large">Welcome!</h1>
      <h2 className="headline headline--medium">We think you&rsquo;ll like it here.</h2>
      <h3 className="headline headline--small">
        Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re interested in?
      </h3>
      <a href="#" className="btn btn--large btn--blue">
        Find Your Major
      </a>
    </>
  );

  return (
    <div className="page-banner">
      {/* Applies the background image using inline styles */}
      <div className="page-banner__bg-image" style={{ backgroundImage: `url(${bgImage})` }}></div>
      <div className="page-banner__content container t-center c-white">
        {/* InnerBlocks component to allow other blocks to be added inside the banner */}
        <InnerBlocks allowedBlocks={["ourblocktheme/genericheading"]} />
      </div>
    </div>
  );
}

// Function defining the saved output for the Banner block
function SaveComponent() {
  return (
    <div className="page-banner">
      {/* Background image with a hardcoded path to the theme directory */}
      <div className="page-banner__bg-image" style={{ backgroundImage: "url('/wp-content/themes/fictional-block-theme/images/library-hero.jpg')" }}></div>
      <div className="page-banner__content container t-center c-white">
        {/* Renders the saved content of nested InnerBlocks */}
        <InnerBlocks.Content />
      </div>
    </div>
  );
}

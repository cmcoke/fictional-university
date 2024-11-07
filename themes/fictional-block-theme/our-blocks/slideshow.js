/**
 * Register the 'Slideshow' block and configure it with block attributes and editor display.
 */

import { InnerBlocks } from "@wordpress/block-editor";
import { registerBlockType } from "@wordpress/blocks";

// Register a custom block type 'Slideshow' with specific attributes
registerBlockType("ourblocktheme/slideshow", {
  title: "Slideshow", // Block title as shown in the editor
  supports: {
    align: ["full"] // Allows the block to have a full-width alignment
  },
  attributes: {
    align: { type: "string", default: "full" } // Default alignment set to full-width
  },
  edit: EditComponent, // Component for rendering block in the editor
  save: SaveComponent  // Component for rendering block on the frontend
});

// Component for saving the block content
function SaveComponent() {
  // Saves nested blocks (slides) added within this slideshow block
  return <InnerBlocks.Content />;
}

// Component for editing the block content in the editor
function EditComponent() {
  return (
    <div style={{ backgroundColor: "#333", padding: "35px" }}>
      <p style={{ textAlign: "center", fontSize: "20px", color: "#FFF" }}>Slideshow</p>
      {/* Allows the addition of 'slide' blocks within this 'slideshow' block */}
      <InnerBlocks allowedBlocks={["ourblocktheme/slide"]} />
    </div>
  );
}

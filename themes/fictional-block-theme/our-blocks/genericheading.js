/**
 * Registers a "Generic Heading" block in WordPress that allows users to add and style headings of different sizes
 */

import { ToolbarGroup, ToolbarButton } from "@wordpress/components"; // Toolbar components for block controls
import { RichText, BlockControls } from "@wordpress/block-editor"; // Rich text editing and block controls
import { registerBlockType } from "@wordpress/blocks"; // Function to register a new block

// Registers the "Generic Heading" block with attributes for text content and size
registerBlockType("ourblocktheme/genericheading", {
  title: "Generic Heading", // Block title
  attributes: {
    text: { type: "string" }, // Stores the text content
    size: { type: "string", default: "large" } // Stores the size attribute (large, medium, or small)
  },
  edit: EditComponent, // Component for the editor view
  save: SaveComponent // Component for the saved output
});

// Component for the editor interface of the "Generic Heading" block
function EditComponent(props) {

  // Updates the text attribute with the value from RichText
  function handleTextChange(x) {
    props.setAttributes({ text: x });
  }

  return (
    <>
      {/* BlockControls component for custom toolbar buttons */}
      <BlockControls>
        <ToolbarGroup>
          {/* Toolbar buttons to toggle heading size between large, medium, and small */}
          <ToolbarButton
            isPressed={props.attributes.size === "large"}
            onClick={() => props.setAttributes({ size: "large" })}
          >
            Large
          </ToolbarButton>
          <ToolbarButton
            isPressed={props.attributes.size === "medium"}
            onClick={() => props.setAttributes({ size: "medium" })}
          >
            Medium
          </ToolbarButton>
          <ToolbarButton
            isPressed={props.attributes.size === "small"}
            onClick={() => props.setAttributes({ size: "small" })}
          >
            Small
          </ToolbarButton>
        </ToolbarGroup>
      </BlockControls>

      {/* RichText component to allow text editing, with specific formats and styling based on size */}
      <RichText
        allowedFormats={["core/bold", "core/italic"]} // Allow bold and italic formatting
        tagName="h1" // Default tag name (changed in SaveComponent based on size)
        className={`headline headline--${props.attributes.size}`} // Dynamic class based on size
        value={props.attributes.text} // Content of the heading
        onChange={handleTextChange} // Updates text attribute on change
      />

    </>
  );
}

// Component for the saved output of the "Generic Heading" block
function SaveComponent(props) {

  // Determines the heading tag (h1, h2, or h3) based on size
  function createTagName() {
    switch (props.attributes.size) {
      case "large":
        return "h1";
      case "medium":
        return "h2";
      case "small":
        return "h3";
    }
  }

  // RichText.Content to render the saved heading with the chosen tag, text, and styling
  return (
    <RichText.Content
      tagName={createTagName()} // Dynamically set tag name based on size
      value={props.attributes.text} // Saved text content
      className={`headline headline--${props.attributes.size}`} // Dynamic class for styling
    />
  );
}

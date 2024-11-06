/**
 * Registers a "Generic Button" block that allows users to create a customizable button with text, size, link, and color options.
 */

import ourColors from "../inc/ourColors"; // Custom color options imported from a separate file
import { link } from "@wordpress/icons"; // Icon for the link button in the toolbar
import { ToolbarGroup, ToolbarButton, Popover, Button, PanelBody, PanelRow, ColorPalette } from "@wordpress/components"; // UI components for toolbar, popover, button, and color palette
import { RichText, InspectorControls, BlockControls, __experimentalLinkControl as LinkControl, getColorObjectByColorValue } from "@wordpress/block-editor"; // Components for text editing, block controls, inspector controls, and color management
import { registerBlockType } from "@wordpress/blocks"; // Function to register the new block
import { useState } from "@wordpress/element"; // State management for visibility of the link picker

// Registers the "Generic Button" block with customizable text, size, link URL, and color options
registerBlockType("ourblocktheme/genericbutton", {
  title: "Generic Button", // Title displayed in the block editor
  attributes: {
    text: { type: "string" }, // Stores button text
    size: { type: "string", default: "large" }, // Size of button, default is large
    linkObject: { type: "object", default: { url: "" } }, // Link object to store URL
    colorName: { type: "string", default: "blue" } // Color name for button styling
  },
  edit: EditComponent, // Component for editing the block
  save: SaveComponent // Component for saving block output
});

// Component that provides the editor interface for the "Generic Button" block
function EditComponent(props) {

  // State to control visibility of the link picker popover
  const [isLinkPickerVisible, setIsLinkPickerVisible] = useState(false);

  // Updates the text attribute based on RichText input
  function handleTextChange(x) {
    props.setAttributes({ text: x });
  }

  // Toggles the visibility of the link picker popover
  function buttonHandler() {
    setIsLinkPickerVisible(prev => !prev);
  }

  // Updates the link object attribute when a new link is chosen
  function handleLinkChange(newLink) {
    props.setAttributes({ linkObject: newLink });
  }

  // Retrieves the current color value based on the color name stored in attributes
  const currentColorValue = ourColors.filter(color => {
    return color.name == props.attributes.colorName;
  })[0].color;

  // Updates the color name attribute when a new color is chosen from the ColorPalette
  function handleColorChange(colorCode) {
    const { name } = getColorObjectByColorValue(ourColors, colorCode); // Finds the name of the selected color based on color code
    props.setAttributes({ colorName: name });
  }

  return (
    <>
      {/* BlockControls provides toolbar options for link and size selection */}
      <BlockControls>

        <ToolbarGroup>
          {/* Button to toggle link picker visibility */}
          <ToolbarButton onClick={buttonHandler} icon={link} />
        </ToolbarGroup>

        <ToolbarGroup>
          {/* Buttons to set the button size (large, medium, small) */}
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

      {/* InspectorControls provides panel options in the editor sidebar */}
      <InspectorControls>
        <PanelBody title="Color" initialOpen={true}>
          <PanelRow>
            {/* ColorPalette to select from predefined colors */}
            <ColorPalette
              disableCustomColors={true} // Disables custom colors outside the predefined palette
              clearable={false} // Disables clearing the color
              colors={ourColors} // Predefined color options
              value={currentColorValue} // Current selected color
              onChange={handleColorChange} // Updates color attribute on change
            />
          </PanelRow>
        </PanelBody>
      </InspectorControls>

      {/* RichText component for editing button text with custom styles for size and color */}
      <RichText
        allowedFormats={[]} // Disables rich text formatting options
        tagName="a" // Tag for the button link
        className={`btn btn--${props.attributes.size} btn--${props.attributes.colorName}`} // Dynamic class based on size and color
        value={props.attributes.text} // Button text content
        onChange={handleTextChange} // Updates text attribute on change
      />

      {/* Popover for link selection, displayed when link picker is visible */}
      {isLinkPickerVisible && (
        <Popover position="middle center" onFocusOutside={() => setIsLinkPickerVisible(false)}>
          {/* LinkControl for selecting or modifying the link URL */}
          <LinkControl
            settings={[]} // Disables additional link settings
            value={props.attributes.linkObject} // Link object with URL
            onChange={handleLinkChange} // Updates link object attribute on change
          />
          {/* Button to confirm link choice and close the link picker */}
          <Button
            variant="primary"
            onClick={() => setIsLinkPickerVisible(false)}
            style={{ display: "block", width: "100%" }}
          >
            Confirm Link
          </Button>
        </Popover>
      )}
    </>
  );
}

// Component for saving the output of the "Generic Button" block
function SaveComponent(props) {
  return (
    // Renders the button with link, text, size, and color attributes
    <a href={props.attributes.linkObject.url} className={`btn btn--${props.attributes.size} btn--${props.attributes.colorName}`}>
      {props.attributes.text}
    </a>
  );
}

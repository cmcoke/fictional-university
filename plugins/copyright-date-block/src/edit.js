/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';

import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {

  // Destructure the `showStartingYear` and `startingYear` attributes from the `attributes` object located in the block.json file
  const { showStartingYear, startingYear } = attributes;

  // Get the current year as a string (e.g., "2024")
  const currentYear = new Date().getFullYear().toString();

  // Define a variable to hold the display date
  let displayDate;

  // Set `displayDate` based on whether `showStartingYear` is true and a `startingYear` is provided
  if (showStartingYear && startingYear) {
    displayDate = startingYear + '-' + currentYear; // Display as a range (e.g., "2020-2024")
  } else {
    displayDate = currentYear; // Display only the current year if no starting year
  }

  return (
    <>
      {/* Inspector Controls for editing settings in the block sidebar */}
      <InspectorControls>
        <PanelBody title={__('Settings', 'copyright-date-block')}>
          {/* Toggle to show or hide the starting year */}
          <ToggleControl
            checked={!!showStartingYear} // Boolean check if `showStartingYear` is true
            label={__('Show starting year', 'copyright-date-block')}
            onChange={() =>
              // Toggle `showStartingYear` and update attribute
              setAttributes({
                showStartingYear: !showStartingYear,
              })
            }
          />
          {/* Input field for starting year, displayed only if `showStartingYear` is true */}
          {showStartingYear && (
            <TextControl
              label={__('Starting year', 'copyright-date-block')}
              value={startingYear || ''} // Set the input value to the starting year or empty if not set
              onChange={(value) =>
                // Update `startingYear` with the input value
                setAttributes({ startingYear: value })
              }
            />
          )}
        </PanelBody>
      </InspectorControls>
      {/* Display the copyright date in the block content */}
      <p {...useBlockProps()}>© {displayDate}</p>
    </>
  );
}



// console.log(attributes);
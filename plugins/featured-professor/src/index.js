/**
 * This script registers a custom Gutenberg block called "Professor Callout" that enables users to select a professor
 * from a dropdown, display a short preview of the selected professor's content, and save the professor's ID as metadata.
 * The block dynamically fetches professor details from a custom REST API endpoint and updates metadata using WordPress data.
 */

import "./index.scss"; // Imports the block’s custom SCSS styles
import { useSelect } from "@wordpress/data";
import { useState, useEffect } from "react";
import apiFetch from "@wordpress/api-fetch";
const __ = wp.i18n.__; // Enables internationalization for text strings

// Registers the custom "Professor Callout" Gutenberg block.
wp.blocks.registerBlockType("ourplugin/featured-professor", {
  title: "Professor Callout", // Block title in the block editor
  description: "Include a short description and link to a professor of your choice", // Brief block description
  icon: "welcome-learn-more", // Icon used in the block selector
  category: "common", // Block category
  attributes: {
    profId: { type: "string" } // Defines a single attribute for storing professor ID as a string
  },
  edit: EditComponent, // Links to the editor component function
  save: function () {
    return null; // Block is rendered dynamically; save function returns null
  }
});

// Defines the editor component for the "Professor Callout" block.
function EditComponent(props) {
  const [thePreview, setThePreview] = useState(""); // State to store professor preview HTML content

  // Effect hook triggered when the professor ID attribute changes.
  useEffect(() => {
    if (props.attributes.profId) {
      updateTheMeta(); // Updates metadata with selected professor IDs
      async function go() {
        // Fetches professor preview HTML from REST API endpoint.
        const response = await apiFetch({
          path: `/featuredProfessor/v1/getHTML?profId=${props.attributes.profId}`,
          method: "GET"
        });
        setThePreview(response); // Updates thePreview state with the fetched HTML content
      }
      go();
    }
  }, [props.attributes.profId]); // Runs effect only when profId attribute changes

  // Cleans up by updating metadata upon component unmount.
  useEffect(() => {
    return () => {
      updateTheMeta(); // Updates metadata when the component unmounts
    };
  }, []);

  // Function to update metadata with selected professor IDs for the entire post.
  function updateTheMeta() {
    // Retrieves IDs of all selected professors within "featured-professor" blocks.
    const profsForMeta = wp.data.select("core/block-editor")
      .getBlocks()
      .filter(x => x.name == "ourplugin/featured-professor") // Filters blocks to find "featured-professor" blocks
      .map(x => x.attributes.profId) // Extracts each block's profId attribute
      .filter((x, index, arr) => {
        return arr.indexOf(x) == index; // Removes duplicate IDs from the array
      });
    console.log(profsForMeta); // Logs the array of unique professor IDs
    wp.data.dispatch("core/editor").editPost({ meta: { featuredprofessor: profsForMeta } }); // Updates post metadata
  }

  // Retrieves all "professor" post records using the `useSelect` hook.
  const allProfs = useSelect(select => {
    return select("core").getEntityRecords("postType", "professor", { per_page: -1 }); // Retrieves all professor posts
  });

  console.log(allProfs); // Logs all retrieved professors

  if (allProfs == undefined) return <p>Loading...</p>; // Displays loading message while professors are being fetched

  // Renders the block’s editor UI.
  return (
    <div className="featured-professor-wrapper">
      <div className="professor-select-container">
        {/* Dropdown for selecting a professor, updating the profId attribute on selection */}
        <select onChange={e => props.setAttributes({ profId: e.target.value })}>
          <option value="">{__("Select a professor", "featured-professor")}</option>
          {allProfs.map(prof => {
            return (
              <option value={prof.id} selected={props.attributes.profId == prof.id}>
                {prof.title.rendered} {/* Displays each professor's title in the dropdown */}
              </option>
            );
          })}
        </select>
      </div>
      {/* Displays the preview HTML content of the selected professor, marked as safe for rendering */}
      <div dangerouslySetInnerHTML={{ __html: thePreview }}></div>
    </div>
  );
}

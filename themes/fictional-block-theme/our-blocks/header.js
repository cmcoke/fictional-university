/**
 * Registers a custom Gutenberg block for the header section
 */

wp.blocks.registerBlockType("ourblocktheme/header", {

  title: "Fictional University Header", // Sets the title of the block as it will appear in the editor's block list

  // Defines the block's editor view
  edit: function () {
    // Returns a placeholder div to display in the editor, indicating where the header will appear
    return wp.element.createElement("div", { className: "our-placeholder-block" }, "Header Placeholder");
  },

  // Defines the block's front-end view
  save: function () {
    // Returns null, using a PHP render callback for rendering content on the front end
    return null;
  }
});

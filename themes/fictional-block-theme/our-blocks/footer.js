/**
 * Registers a custom Gutenberg block for the footer section
 */

wp.blocks.registerBlockType("ourblocktheme/footer", {

  title: "Fictional University Footer", // Sets the title of the block as it will appear in the editor

  // Defines the block's editor view
  edit: function () {
    // Returns a placeholder div in the editor to indicate where the footer will appear
    return wp.element.createElement("div", { className: "our-placeholder-block" }, "Footer Placeholder");
  },

  // Defines the block's front-end view
  save: function () {
    // Returns null to use a PHP render callback for the front-end display
    return null;
  }
});

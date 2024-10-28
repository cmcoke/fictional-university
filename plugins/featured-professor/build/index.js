/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/index.scss":
/*!************************!*\
  !*** ./src/index.scss ***!
  \************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["ReactJSXRuntime"];

/***/ }),

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["wp"]["apiFetch"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["data"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./index.scss */ "./src/index.scss");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__);
/**
 * This script registers a custom Gutenberg block called "Professor Callout" that enables users to select a professor
 * from a dropdown, display a short preview of the selected professor's content, and save the professor's ID as metadata.
 * The block dynamically fetches professor details from a custom REST API endpoint and updates metadata using WordPress data.
 */

 // Imports the block’s custom SCSS styles




const __ = wp.i18n.__; // Enables internationalization for text strings

// Registers the custom "Professor Callout" Gutenberg block.
wp.blocks.registerBlockType("ourplugin/featured-professor", {
  title: "Professor Callout",
  // Block title in the block editor
  description: "Include a short description and link to a professor of your choice",
  // Brief block description
  icon: "welcome-learn-more",
  // Icon used in the block selector
  category: "common",
  // Block category
  attributes: {
    profId: {
      type: "string"
    } // Defines a single attribute for storing professor ID as a string
  },
  edit: EditComponent,
  // Links to the editor component function
  save: function () {
    return null; // Block is rendered dynamically; save function returns null
  }
});

// Defines the editor component for the "Professor Callout" block.
function EditComponent(props) {
  const [thePreview, setThePreview] = (0,react__WEBPACK_IMPORTED_MODULE_2__.useState)(""); // State to store professor preview HTML content

  // Effect hook triggered when the professor ID attribute changes.
  (0,react__WEBPACK_IMPORTED_MODULE_2__.useEffect)(() => {
    if (props.attributes.profId) {
      updateTheMeta(); // Updates metadata with selected professor IDs
      async function go() {
        // Fetches professor preview HTML from REST API endpoint.
        const response = await _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default()({
          path: `/featuredProfessor/v1/getHTML?profId=${props.attributes.profId}`,
          method: "GET"
        });
        setThePreview(response); // Updates thePreview state with the fetched HTML content
      }
      go();
    }
  }, [props.attributes.profId]); // Runs effect only when profId attribute changes

  // Cleans up by updating metadata upon component unmount.
  (0,react__WEBPACK_IMPORTED_MODULE_2__.useEffect)(() => {
    return () => {
      updateTheMeta(); // Updates metadata when the component unmounts
    };
  }, []);

  // Function to update metadata with selected professor IDs for the entire post.
  function updateTheMeta() {
    // Retrieves IDs of all selected professors within "featured-professor" blocks.
    const profsForMeta = wp.data.select("core/block-editor").getBlocks().filter(x => x.name == "ourplugin/featured-professor") // Filters blocks to find "featured-professor" blocks
    .map(x => x.attributes.profId) // Extracts each block's profId attribute
    .filter((x, index, arr) => {
      return arr.indexOf(x) == index; // Removes duplicate IDs from the array
    });
    console.log(profsForMeta); // Logs the array of unique professor IDs
    wp.data.dispatch("core/editor").editPost({
      meta: {
        featuredprofessor: profsForMeta
      }
    }); // Updates post metadata
  }

  // Retrieves all "professor" post records using the `useSelect` hook.
  const allProfs = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.useSelect)(select => {
    return select("core").getEntityRecords("postType", "professor", {
      per_page: -1
    }); // Retrieves all professor posts
  });
  console.log(allProfs); // Logs all retrieved professors

  if (allProfs == undefined) return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("p", {
    children: "Loading..."
  }); // Displays loading message while professors are being fetched

  // Renders the block’s editor UI.
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)("div", {
    className: "featured-professor-wrapper",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("div", {
      className: "professor-select-container",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)("select", {
        onChange: e => props.setAttributes({
          profId: e.target.value
        }),
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("option", {
          value: "",
          children: __("Select a professor", "featured-professor")
        }), allProfs.map(prof => {
          return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)("option", {
            value: prof.id,
            selected: props.attributes.profId == prof.id,
            children: [prof.title.rendered, " "]
          });
        })]
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("div", {
      dangerouslySetInnerHTML: {
        __html: thePreview
      }
    })]
  });
}
})();

/******/ })()
;
//# sourceMappingURL=index.js.map
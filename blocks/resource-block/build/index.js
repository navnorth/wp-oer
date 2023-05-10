/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/edit.js":
/*!*********************!*\
  !*** ./src/edit.js ***!
  \*********************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ Edit; }
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./editor.scss */ "./src/editor.scss");


/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */





/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */


/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */



function preview_block(args, blockId) {
  var data = {
    action: 'oer_display_resource_block',
    params: args
  };
  jquery__WEBPACK_IMPORTED_MODULE_4___default().ajax({
    url: oer_resource.ajaxurl,
    type: 'POST',
    data: data,
    success: function (response) {
      jquery__WEBPACK_IMPORTED_MODULE_4___default()('#block-' + blockId + ' .wp-block-wp-oer-plugin-wp-oer-resource-block').html("");
      jquery__WEBPACK_IMPORTED_MODULE_4___default()('#block-' + blockId + ' .wp-block-wp-oer-plugin-wp-oer-resource-block').html(response);
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      console.log(errorThrown);
    }
  });
}
/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */


function Edit(props) {
  var display = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Loading...", "wp-oer-resource-block");

  var resourceOptions;
  var optionResources = [{
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Select a resource', 'wp-oer-resource-block'),
    value: ''
  }];
  var showThumbnail = false;
  var showTitle = false;
  var showDescription = false;
  var showSubjects = false;
  var showGradeLevels = false;
  var wBorder = false;
  const {
    attributes,
    setAttributes,
    className,
    clientId
  } = props;
  const alignmentOptions = [{
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Select Alignment', 'wp-oer-resource-block'),
    value: ''
  }, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Left', 'wp-oer-resource-block'),
    value: 'left'
  }, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Center', 'wp-oer-resource-block'),
    value: 'center'
  }, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Right', 'wp-oer-resource-block'),
    value: 'right'
  }]; // get all resources

  const getResources = () => {
    if (oer_resource.perm_structure) {
      wp.apiFetch({
        url: oer_resource.home_url + '/wp-json/oer-resource-block/v1/resources'
      }).then(resources => {
        setAttributes({
          resources: resources
        });
      });
    } else {
      wp.apiFetch({
        url: oer_resource.home_url + '?rest_route=/oer-resource-block/v1/resources'
      }).then(resources => {
        setAttributes({
          resources: resources
        });
      });
    }
  };

  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    getResources();
  }, []);

  if (typeof attributes.resources !== 'undefined') {
    if (attributes.resources.length > 0) display = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('There are ', 'wp-oer-resource-block') + attributes.resources.length + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)(' resources to choose from. Please select one.', 'wp-oer-resource-block');
    let resources = attributes.resources.map(resource => {
      return {
        label: resource.title,
        value: resource.id
      };
    });
    optionResources = optionResources.concat(resources);
  }

  const onChangeResource = newResource => {
    setAttributes({
      selectedResource: parseInt(newResource),
      isChanged: true
    });
  };

  const onChangeAlignment = newAlignment => {
    setAttributes({
      alignment: newAlignment,
      isChanged: true
    });
  };

  const onChangeWidth = newWidth => {
    setAttributes({
      blockWidth: newWidth,
      isChanged: true
    });
  };

  const onShowThumbnail = checked => {
    setAttributes({
      showThumbnail: checked,
      isChanged: true
    });
  };

  const onShowTitle = checked => {
    setAttributes({
      showTitle: checked,
      isChanged: true
    });
  };

  const onShowDescription = checked => {
    setAttributes({
      showDescription: checked,
      isChanged: true
    });
  };

  const onShowSubjects = checked => {
    setAttributes({
      showSubjects: checked,
      isChanged: true
    });
  };

  const onShowGradeLevels = checked => {
    setAttributes({
      showGrades: checked,
      isChanged: true
    });
  };

  const onWithBorder = checked => {
    setAttributes({
      withBorder: checked,
      isChanged: true
    });
  };

  const setBlockId = blockId => {
    setAttributes({
      blockId
    });
  };

  if (clientId !== attributes.blockId) {
    setBlockId(clientId);
  }

  if (typeof attributes.selectedResource !== 'undefined') {
    preview_block(attributes, attributes.blockId);
  }

  return [(0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.InspectorControls, {
    className: className
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('OER Resource Options', 'wp-oer-resource-block'),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {
    className: 'oer-resource-block-resource-field',
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Resource', 'wp-oer-resource-block'),
    value: attributes.selectedResource,
    options: optionResources,
    onChange: onChangeResource
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {
    className: 'oer-resource-block-alignment-field',
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Alignment', 'wp-oer-resource-block'),
    value: attributes.alignment,
    options: alignmentOptions,
    onChange: onChangeAlignment
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
    className: 'oer-resource-block-width-field',
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Width in pixels(optional)', 'wp-oer-resource-block'),
    value: attributes.blockWidth,
    onChange: onChangeWidth
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.CheckboxControl, {
    className: 'oer-resource-block-show-thumbnail',
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Show thumbnail', 'wp-oer-resource-block'),
    checked: attributes.showThumbnail,
    onChange: onShowThumbnail
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.CheckboxControl, {
    className: 'oer-resource-block-show-title',
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Show title', 'wp-oer-resource-block'),
    checked: attributes.showTitle,
    onChange: onShowTitle
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.CheckboxControl, {
    className: 'oer-resource-block-show-description',
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Show description', 'wp-oer-resource-block'),
    checked: attributes.showDescription,
    onChange: onShowDescription
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.CheckboxControl, {
    className: 'oer-resource-block-show-subjects',
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Show subjects', 'wp-oer-resource-block'),
    checked: attributes.showSubjects,
    onChange: onShowSubjects
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.CheckboxControl, {
    className: 'oer-resource-block-show-grade-levels',
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Show grade levels', 'wp-oer-resource-block'),
    checked: attributes.showGrades,
    onChange: onShowGradeLevels
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.CheckboxControl, {
    className: 'oer-resource-block-show-with-border',
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Show block with border', 'wp-oer-resource-block'),
    checked: attributes.withBorder,
    onChange: onWithBorder
  }))))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.useBlockProps)(), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "wp-block-wp-oer-plugin-wp-oer-resource-block"
  }, display))];
}

/***/ }),

/***/ "./src/index.js":
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./style.scss */ "./src/style.scss");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../block.json */ "./block.json");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./edit */ "./src/edit.js");
/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */


/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */



/**
 * Internal dependencies
 */


/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
// registerBlockType('wp-oer-plugin/wp-oer-resource-block', {
//     /**
//      * This is the display title for your block, which can be translated with `i18n` functions.
//      * The block inserter will show this name.
//      */
//     title: __('OER Resource Block', 'wp-oer-resource-block'),
//     /**
//      * This is a short description for your block, can be translated with `i18n` functions.
//      * It will be shown in the Block Tab in the Settings Sidebar.
//      */
//     description: __('This block displays a single resource on a page.','wp-oer-resource-block'),
//     /**
//      * Blocks are grouped into categories to help users browse and discover them.
//      * The categories provided by core are `common`, `embed`, `formatting`, `layout` and `widgets`.
//      */
//     category: 'oer-block-category',
//     /**
//      * An icon property should be specified to make it easier to identify a block.
//      * These can be any of WordPress’ Dashicons, or a custom svg element.
//      */
//     icon: {
//         foreground: '#121212',
//         src: 'media-document'
//     },
//     keywords: [__('OER', 'oer-subject-resource-block'), __('Resource', 'oer-subject-resource-block')],
//     attributes : {
//         resources: {
//             type: 'object'
//         },
//         selectedResource: {
//             type: 'number'
//         },
//         alignment: {
//             type: 'string',
//         },
//         blockWidth: {
//             type: 'string'
//         },
//         showThumbnail: {
//             type:'boolean',
//             default: false
//         }, 
//         showTitle: {
//             type:'boolean',
//             default:false
//         },
//         showDescription: {
//             type:'boolean',
//             default: false
//         }, 
//         showSubjects: {
//             type: 'boolean',
//             default: false
//         },
//         showGrades: {
//             type: 'boolean',
//             default: false
//         },
//         withBorder: {
//             type: 'boolean',
//             default: false
//         },
//         blockId: {
//             type: 'string'
//         },
//         firstLoad: {
//             type: 'boolean',
//             default: true
//         },
//         isChanged: {
//             type: 'boolean',
//             default: false
//         }
//     },
// 	/**
// 	 * @see ./edit.js
// 	 */
// 	edit: Edit,
// });

(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)(_block_json__WEBPACK_IMPORTED_MODULE_3__, {
  edit: _edit__WEBPACK_IMPORTED_MODULE_4__["default"]
});

/***/ }),

/***/ "./src/editor.scss":
/*!*************************!*\
  !*** ./src/editor.scss ***!
  \*************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/style.scss":
/*!************************!*\
  !*** ./src/style.scss ***!
  \************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "jquery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/***/ (function(module) {

module.exports = window["jQuery"];

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ (function(module) {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ (function(module) {

module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ (function(module) {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "./block.json":
/*!********************!*\
  !*** ./block.json ***!
  \********************/
/***/ (function(module) {

module.exports = JSON.parse('{"$schema":"https://json.schemastore.org/block.json","apiVersion":2,"name":"wp-oer-plugin/wp-oer-resource-block","version":"0.1.0","title":"OER Resource Block","category":"oer-block-category","keywords":["OER","resource"],"icon":{"foreground":"#121212","src":"media-document"},"description":"This block displays a single resource on a page.","attributes":{"resources":{"type":"object"},"selectedResource":{"type":"number"},"alignment":{"type":"string"},"blockWidth":{"type":"string"},"showThumbnail":{"type":"boolean","default":false},"showTitle":{"type":"boolean","default":false},"showDescription":{"type":"boolean","default":false},"showSubjects":{"type":"boolean","default":false},"showGrades":{"type":"boolean","default":false},"withBorder":{"type":"boolean","default":false},"blockId":{"type":"string"},"firstLoad":{"type":"boolean","default":true},"isChanged":{"type":"boolean","default":false}},"supports":{"html":false},"textdomain":"wp-oer-resource-block","editorScript":"file:./build/index.js","editorStyle":"file:./build/index.css","style":"file:./build/style-index.css"}');

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
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	!function() {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = function(result, chunkIds, fn, priority) {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var chunkIds = deferred[i][0];
/******/ 				var fn = deferred[i][1];
/******/ 				var priority = deferred[i][2];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every(function(key) { return __webpack_require__.O[key](chunkIds[j]); })) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	!function() {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"index": 0,
/******/ 			"style-index": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = function(chunkId) { return installedChunks[chunkId] === 0; };
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = function(parentChunkLoadingFunction, data) {
/******/ 			var chunkIds = data[0];
/******/ 			var moreModules = data[1];
/******/ 			var runtime = data[2];
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some(function(id) { return installedChunks[id] !== 0; })) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkIds[i]] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunkwp_oer_resource_block"] = self["webpackChunkwp_oer_resource_block"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	}();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["style-index"], function() { return __webpack_require__("./src/index.js"); })
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
//# sourceMappingURL=index.js.map
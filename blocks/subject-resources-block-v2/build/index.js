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
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./editor.scss */ "./src/editor.scss");


/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */






/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */


/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @param {Object} [props]           Properties passed from the editor.
 * @param {string} [props.className] Class name generated for the block.
 *
 * @return {WPElement} Element to render.
 */

function Edit(props) {
  var subject_display = [];
  var bOutput = [];
  var childSubjects = [];
  var subjects = [];
  var key;
  let sortbox;
  let heading;
  var checkedState;
  var selectedSortOption;
  const checkedBox = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" role="img" class="components-checkbox-control__checked" aria-hidden="true" focusable="false"><path d="M18.3 5.6L9.9 16.9l-4.6-3.4-.9 1.2 5.8 4.3 9.3-12.6z"></path></svg>';

  String.prototype.ucwords = function () {
    var str = this.toLowerCase();
    return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, function (s) {
      return s.toUpperCase();
    });
  }; // Check if function is a constructor


  const isConstructor = func => {
    try {
      new func();
    } catch (err) {
      return false;
    }

    return true;
  };

  const {
    attributes,
    setAttributes,
    clientId
  } = props;

  const getSubjectAreas = () => {
    // pretty permalink structure
    if (oer_subject_resources.perm_structure) {
      wp.apiFetch({
        url: oer_subject_resources.home_url + '/wp-json/oer/v2/subjects/'
      }).then(subjects => {
        setAttributes({
          subjects: subjects
        });
      });
    } else {
      // plain permalink structure
      wp.apiFetch({
        url: oer_subject_resources.home_url + '?rest_route=/oer/v2/subjects/'
      }).then(subjects => {
        setAttributes({
          subjects: subjects
        });
      });
    }
  };

  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    getSubjectAreas();
  }, []);

  const getOptions = () => {
    if (typeof wp.api.collections !== 'undefined') {
      if (typeof wp.api.collections === 'object' && isConstructor(wp.api.collections.Posts)) {
        return new wp.api.collections.Posts().fetch().then(posts => {
          if (typeof this.state.selectedSubject !== 'undefined') {
            if (posts && 0 !== this.state.selectedSubject) {
              const post = posts.find(item => {
                return item.id == this.state.selectedSubject;
              });
              this.setState({
                post,
                posts
              });
            } else {
              this.setState({
                posts
              });
            }
          }
        });
      }
    }
  };

  const onChangeDisplayCount = newValue => {
    setAttributes({
      displayCount: newValue,
      isChanged: true
    });
    selectResources(true);
  };

  const onChangeSelectedSubject = newValue => {
    const post = this.state.posts.find(item => {
      return item.id == parseInt(newValue);
    });
    this.setState({
      selectedSubject: parseInt(newValue),
      post
    });
    setAttributes({
      selectedSubject: parseInt(value),
      title: post.title.rendered,
      content: post.excerpt.rendered,
      link: post.link,
      isChanged: true
    });
    selectResources(true);
  };

  const onChangeSorting = newValue => {
    setAttributes({
      sort: newValue,
      isChanged: true
    });
    selectResources(true);
  };

  const sortSubjects = (a, b) => {
    if (a.label < b.label) {
      return -1;
    }

    if (a.label > b.label) {
      return 1;
    }

    return 0;
  };

  const getChildSubjects = subject_id => {
    var output;
    var subjectAreas = new wp.api.models.ResourceSubjectArea();
    var resource_subjects = [];
    subjectAreas.fetch({
      data: {
        per_page: 100,
        parent: subject_id
      }
    }).then(subjects => {
      if (subjects.length > 0) {
        resource_subjects = [].concat.apply(resource_subjects, subjects);
        resource_subjects = resource_subjects.sort(this.sortSubjects);
        this.setState({
          childSubjects: resource_subjects
        });
        return resource_subjects;
      }
    });
  };
  /**-- Select resources after selecting a subject --**/


  const selectResources = reload => {
    var subjects = attributes.selectedSubjects;
    var count = attributes.displayCount;
    var sortOption = attributes.sort;
    var resources;

    if (typeof wp.api.collections === 'object' && isConstructor(wp.api.collections.Resource)) {
      resources = new wp.api.collections.Resource();

      if (attributes.selectedSubjects) {
        resources.fetch({
          data: {
            per_page: count,
            orderby: sortOption,
            order: 'asc',
            'resource-subject-area': subjects
          }
        }).then(posts => {
          setAttributes({
            selectedSubjectResources: posts
          });
        });
      } else {
        resources.fetch({
          data: {
            per_page: count,
            orderby: sortOption,
            order: 'asc'
          }
        }).then(posts => {
          setAttributes({
            selectedSubjectResources: posts
          });
        });
      }
    }
  };

  const oer_preview_subject_resources = props => {
    var responseText;
    var data = {
      action: 'oer_get_subject_resources',
      attributes: JSON.stringify(props)
    };
    jquery__WEBPACK_IMPORTED_MODULE_5___default().ajax({
      url: oer_subject_resources.ajax_url,
      type: 'POST',
      data: data,
      dataType: 'html',
      async: false,
      success: function (response) {
        responseText = response;
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        console.log(errorThrown);
      }
    });
    return responseText;
  };

  const setBlockId = blockId => {
    setAttributes({
      blockId
    });
  };

  if (clientId !== attributes.blockId) {
    setBlockId(clientId);
  }

  if (attributes.firstLoad) {
    setTimeout(function () {
      selectResources(false);
    }, 2000);
    setAttributes({
      firstLoad: false
    });
  }

  let display_count_options = [{
    value: "5",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('5')
  }, {
    value: "10",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('10')
  }, {
    value: "15",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('15')
  }, {
    value: "20",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('20')
  }, {
    value: "25",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('25')
  }, {
    value: "30",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('30')
  }];
  let sort_options = [{
    value: 'modified',
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Date Updated', 'oer-subject-resources-block')
  }, {
    value: 'date',
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Date Added', 'oer-subject-resources-block')
  }, {
    value: 'title',
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Title a-z', 'oer-subject-resources-block')
  }];

  if (typeof attributes.subjects === 'undefined') {
    bOutput = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Loading...', 'oer-subject-resources-block');
  } else {
    subjects = attributes.subjects.sort(sortSubjects);
  }

  const subject_areas_display = subjects.map((subject, index) => {
    if (jquery__WEBPACK_IMPORTED_MODULE_5___default().inArray(subject.term_id, attributes.selectedSubjects) !== -1) checkedState = true;else checkedState = false;
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.CheckboxControl, {
      value: subject.term_id,
      label: subject.name,
      key: index,
      name: "selectedSubjects[]",
      className: subject.type == 'child' ? 'oer-subject-area-child' : 'oer-subject-area-parent',
      checked: checkedState[index],
      id: subject.term_id,
      onChange: function (val) {
        let data = attributes.selectedSubjects ? attributes.selectedSubjects : [];
        checkedState = val;
        jquery__WEBPACK_IMPORTED_MODULE_5___default()("#" + subject.term_id).prop("checked", checkedState);
        if (checkedState) jquery__WEBPACK_IMPORTED_MODULE_5___default()("#" + subject.term_id).parent().append(checkedBox);

        if (val) {
          checkedState = true;
          data.push(subject.term_id);
          /**--if (typeof data !== 'undefined') {
              if (data.indexOf(subject.term_id) === -1) {
                  data.push(subject.term_id)
              }
          } else {
              data.push(subject.term_id);
          }--**/
        } else {
          checkedState = false;
          data = attributes.selectedSubjects.filter(v => v !== subject.term_id);
        }

        setAttributes({
          selectedSubjects: data,
          isChanged: true
        });
        selectResources(true);
      }
    });
  });

  if (typeof attributes === 'undefined') {
    bOutput = 'Loading...';
  } else {
    bOutput = oer_preview_subject_resources(attributes);
    setAttributes({
      isChanged: false
    });
  }

  return [(0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.InspectorControls, {
    className: "oer-subject-resources-block-inspector"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("OER Subject Resources Option", "oer-subject-resources-block"),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "form-group oer-inspector-subjects"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Subject(s):", "oer-subject-resources-block")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "editor-post-taxonomies__hierarchical-terms-list"
  }, subject_areas_display))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {
    className: "display-count",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("# of Resources to display:", "oer-subject-resources-block"),
    value: attributes.displayCount,
    options: display_count_options,
    onChange: onChangeDisplayCount
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {
    className: "sort-option",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Sort:", "oer-subject-resources-block"),
    value: attributes.sort,
    options: sort_options,
    onChange: onChangeSorting
  }))))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.useBlockProps)(), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "oer-subject-resources-list",
    dangerouslySetInnerHTML: {
      __html: bOutput
    }
  }))];
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
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./style.scss */ "./src/style.scss");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../block.json */ "./block.json");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./edit */ "./src/edit.js");
/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */

/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
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

if (oer_subject_resources.version_58 == 1) {
  (0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)(_block_json__WEBPACK_IMPORTED_MODULE_4__, {
    edit: _edit__WEBPACK_IMPORTED_MODULE_5__["default"]
  });
} else {
  (0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)('oer-block/subject-resources-block', {
    /**
     * This is the display title for your block, which can be translated with `i18n` functions.
     * The block inserter will show this name.
     */
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('WP OER Subject Resources Block', 'oer-subject-resources-block'),

    /**
     * This is a short description for your block, can be translated with `i18n` functions.
     * It will be shown in the Block Tab in the Settings Sidebar.
     */
    description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Displays list of resources according to subject areas.', 'oer-subject-resources-block'),

    /**
     * Blocks are grouped into categories to help users browse and discover them.
     * The categories provided by core are `common`, `embed`, `formatting`, `layout` and `widgets`.
     */
    category: 'oer-block-category',

    /**
     * An icon property should be specified to make it easier to identify a block.
     * These can be any of WordPress’ Dashicons, or a custom svg element.
     */
    icon: {
      foreground: '#121212',
      src: 'welcome-widgets-menus'
    },
    keywords: [(0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('OER', 'oer-subject-resources-block'), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Resource', 'oer-subject-resources-block'), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Subject Areas', 'oer-subject-resources-block')],
    attributes: {
      content: {
        type: 'string',
        selector: 'p'
      },
      title: {
        type: 'string',
        selector: 'h2'
      },
      link: {
        type: 'string',
        selector: 'a'
      },
      displayCount: {
        type: 'string',
        selector: 'display-count',
        default: 5
      },
      selectedSubject: {
        type: 'number',
        default: 0
      },
      sort: {
        type: 'string',
        selector: 'sort-option',
        default: "modified"
      },
      selectedSubjectResources: {
        type: 'array'
      },
      subjects: {
        type: 'array'
      },
      selectedSubjects: {
        type: 'array'
      },
      childSubjects: {
        type: 'array'
      },
      isChanged: {
        type: 'boolean',
        default: false
      },
      blockId: {
        type: 'string'
      },
      firstLoad: {
        type: 'boolean',
        default: true
      }
    },

    /**
     * @see ./edit.js
     */
    edit: _edit__WEBPACK_IMPORTED_MODULE_5__["default"]
    /**
     * @see ./save.js
     */
    //save,

  });
}

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

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/***/ (function(module) {

module.exports = window["wp"]["apiFetch"];

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

module.exports = JSON.parse('{"$schema":"https://json.schemastore.org/block.json","apiVersion":2,"name":"wp-oer-plugin/subject-resources-block","version":"0.1.0","title":"OER Subject Resources","category":"oer-block-category","keywords":["OER","resource","subject areas"],"icon":{"foreground":"#121212","src":"welcome-widgets-menus"},"description":"Displays list of resources according to subject areas.","attributes":{"content":{"type":"string","selector":"p"},"title":{"type":"string","selector":"h2"},"link":{"type":"string","selector":"a"},"displayCount":{"type":"string","selector":"display-count","default":5},"selectedSubject":{"type":"number","default":0},"sort":{"type":"string","selector":"sort-option","default":"modified"},"selectedSubjectResources":{"type":"array"},"subjects":{"type":"array"},"selectedSubjects":{"type":"array"},"childSubjects":{"type":"array"},"isChanged":{"type":"boolean","default":false},"blockId":{"type":"string"},"firstLoad":{"type":"boolean","default":true}},"supports":{"html":false},"textdomain":"oer-subject-resources-block","editorScript":"file:./build/index.js","editorStyle":"file:./build/index.css","style":"file:./build/style-index.css"}');

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
/******/ 		var chunkLoadingGlobal = self["webpackChunkoer_subject_resources_block"] = self["webpackChunkoer_subject_resources_block"] || [];
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
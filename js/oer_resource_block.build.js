/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var __ = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;
var InspectorControls = wp.editor.InspectorControls;
var SelectControl = wp.components.SelectControl;
var Component = wp.element.Component;
var elem = wp.element.createElement;

var mySelectResource = function (_Component) {
    _inherits(mySelectResource, _Component);

    _createClass(mySelectResource, null, [{
        key: 'getInitialState',
        value: function getInitialState(selectedResource) {
            return {
                posts: [],
                selectedResource: selectedResource,
                post: {}
            };
        }
    }]);

    function mySelectResource() {
        _classCallCheck(this, mySelectResource);

        var _this = _possibleConstructorReturn(this, (mySelectResource.__proto__ || Object.getPrototypeOf(mySelectResource)).apply(this, arguments));

        _this.state = _this.constructor.getInitialState(_this.props.attributes.selectedResource);

        _this.getOptions = _this.getOptions.bind(_this);

        _this.getOptions();
        return _this;
    }

    _createClass(mySelectResource, [{
        key: 'getOptions',
        value: function getOptions() {
            var _this2 = this;

            return new wp.api.collections.Posts().fetch({ data: { type: 'resource' } }).then(function (posts) {
                if (posts && 0 !== _this2.state.selectedResource) {
                    var post = posts.find(function (item) {
                        return item.id == _this2.state.selectedResource;
                    });
                    _this2.setState({ post: post, posts: posts });
                } else {
                    _this2.setState({ posts: posts });
                }
            });
        }
    }, {
        key: 'render',
        value: function render() {

            var options = [{ value: 0, label: __('Select a resource') }];
            var output = __('Loading Resources');

            if (this.state.posts.length > 0) {
                var loading = __('We have %d resources. Choose one.');
                output = loading.replace('%d', this.state.posts.length);
                this.state.posts.forEach(function (post) {
                    options.push({ value: post.id, label: post.title.rendered });
                });
            } else {
                output = __('No resource found. Please create some first.');
            }

            return [!!this.props.isSelected && wp.element.createElement(
                InspectorControls,
                { key: 'inspector' },
                wp.element.createElement(SelectControl, { value: this.props.attributes.selectedResource, label: __('Select a Resource'), options: options })
            ), output];
        }
    }]);

    return mySelectResource;
}(Component);

registerBlockType('wp-oer-plugin/oer-resource-block', {
    title: __('OER Resource'),
    category: 'widgets',
    icon: {
        foreground: '#121212',
        src: 'media-document'
    },
    keywords: [__('OER'), __('Resource'), __('History')],
    attributes: {
        content: {
            type: 'array',
            source: 'children',
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
        selectedResource: {
            type: 'number',
            default: 0
        }
    },
    edit: mySelectResource,
    save: function save(props) {
        return elem('p', props.attributes.content, 'Saved Embed Resource');
    }
});

/***/ })
/******/ ]);
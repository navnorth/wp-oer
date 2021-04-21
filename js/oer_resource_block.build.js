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
var CheckboxControl = wp.components.CheckboxControl;
var TextControl = wp.components.TextControl;
var elem = wp.element.createElement;

var mySelectResource = function (_Component) {
    _inherits(mySelectResource, _Component);

    _createClass(mySelectResource, null, [{
        key: 'getInitialState',
        value: function getInitialState(selectedResource) {
            return {
                posts: [],
                subjectAreas: [],
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

        _this.onChangeSelectResource = _this.onChangeSelectResource.bind(_this);

        _this.onChangeAlignment = _this.onChangeAlignment.bind(_this);

        _this.onChangeShowTitle = _this.onChangeShowTitle.bind(_this);

        _this.onChangeShowDescription = _this.onChangeShowDescription.bind(_this);

        _this.onChangeShowSubjects = _this.onChangeShowSubjects.bind(_this);

        _this.onChangeShowGradeLevels = _this.onChangeShowGradeLevels.bind(_this);

        _this.onChangeShowThumbnail = _this.onChangeShowThumbnail.bind(_this);

        _this.onChangeWidth = _this.onChangeWidth.bind(_this);

        _this.onChangeWithBorder = _this.onChangeWithBorder.bind(_this);
        return _this;
    }

    _createClass(mySelectResource, [{
        key: 'onChangeSelectResource',
        value: function onChangeSelectResource(value) {
            var subject;
            var subjects = [];

            var post = this.state.posts.find(function (item) {
                return item.id == parseInt(value);
            });
            var subs = post['resource-subject-area'];

            for (i = 0; i < subs.length; i++) {
                subject = new wp.api.models.ResourceSubjectArea({ id: subs[i] }).fetch().then(function (subs) {
                    subjects.push(subs);
                });
            }

            this.setState({ selectedResource: parseInt(value), post: post });

            this.props.setAttributes({
                selectedResource: parseInt(value),
                title: post.title.rendered,
                content: post.content.rendered,
                link: post.link,
                subjectAreas: subjects,
                gradeLevels: post.oer_grade,
                featuredImage: post.fimg_url,
                resourceUrl: post.oer_resourceurl
            });
        }
    }, {
        key: 'onChangeAlignment',
        value: function onChangeAlignment(value) {
            this.props.setAttributes({
                alignment: value
            });
        }
    }, {
        key: 'onChangeShowTitle',
        value: function onChangeShowTitle(checked) {
            this.props.setAttributes({ showTitle: checked });
        }
    }, {
        key: 'onChangeShowDescription',
        value: function onChangeShowDescription(checked) {
            this.props.setAttributes({ showDescription: checked });
        }
    }, {
        key: 'onChangeShowSubjects',
        value: function onChangeShowSubjects(checked) {
            this.props.setAttributes({ showSubjectAreas: checked });
        }
    }, {
        key: 'onChangeShowGradeLevels',
        value: function onChangeShowGradeLevels(checked) {
            this.props.setAttributes({ showGradeLevels: checked });
        }
    }, {
        key: 'onChangeShowThumbnail',
        value: function onChangeShowThumbnail(checked) {
            this.props.setAttributes({ showThumbnail: checked });
        }
    }, {
        key: 'onChangeWidth',
        value: function onChangeWidth(value) {
            this.props.setAttributes({ blockWidth: value });
        }
    }, {
        key: 'onChangeWithBorder',
        value: function onChangeWithBorder(checked) {
            this.props.setAttributes({ withBorder: checked });
        }
    }, {
        key: 'getOptions',
        value: function getOptions() {
            var _this2 = this;

            var resources = new wp.api.collections.Resource();

            return resources.fetch( { data: { per_page: 100 }} ).then(function (posts) {
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
            var aOptions = [{ value: 'none', label: __('Select Alignment') }, { value: 'left', label: __('Left') }, { value: 'center', label: __('Center') }, { value: 'right', label: __('Right') }];
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

            if (this.state.post.hasOwnProperty('title')) {
                output = wp.element.createElement(
                    'div',
                    { className: 'post' },
                    wp.element.createElement('h2', { dangerouslySetInnerHTML: { __html: this.state.post.title.rendered } }),
                    this.props.showDescription === true && wp.element.createElement('p', { dangerouslySetInnerHTML: { __html: this.state.post.content.rendered } })
                );
                this.props.className += ' has-post';
            } else {
                this.props.className += ' no-post';
            }

            return [!!this.props.isSelected && wp.element.createElement(
                InspectorControls,
                { key: 'inspector' },
                wp.element.createElement(SelectControl, { onChange: this.onChangeSelectResource, value: this.props.attributes.selectedResource, label: __('Resource:'), options: options }),
                wp.element.createElement(SelectControl, { onChange: this.onChangeAlignment, value: this.props.attributes.alignment, label: __('Alignment:'), options: aOptions }),
                wp.element.createElement(TextControl, { onChange: this.onChangeWidth, value: this.props.attributes.blockWidth, label: __('Width in pixels(optional)') }),
                wp.element.createElement(CheckboxControl, {
                    id: 'oerShowThumbnail',
                    label: __('Show Thumbnail'),
                    checked: this.props.attributes.showThumbnail,
                    onChange: this.onChangeShowThumbnail }),
                wp.element.createElement(CheckboxControl, {
                    id: 'oerShowTitle',
                    label: __('Show Title'),
                    checked: this.props.attributes.showTitle,
                    onChange: this.onChangeShowTitle }),
                wp.element.createElement(CheckboxControl, {
                    id: 'oerShowDesc',
                    label: __('Show Description'),
                    checked: this.props.attributes.showDescription,
                    onChange: this.onChangeShowDescription }),
                wp.element.createElement(CheckboxControl, {
                    id: 'oerShowSubjects',
                    label: __('Show Subject Areas'),
                    checked: this.props.attributes.showSubjectAreas,
                    onChange: this.onChangeShowSubjects }),
                wp.element.createElement(CheckboxControl, {
                    id: 'oerShowGradeLevels',
                    label: __('Show Grade Levels'),
                    checked: this.props.attributes.showGradeLevels,
                    onChange: this.onChangeShowGradeLevels }),
                wp.element.createElement(CheckboxControl, {
                    id: 'oerWithBorder',
                    label: __('Show Block with Border'),
                    checked: this.props.attributes.withBorder,
                    onChange: this.onChangeWithBorder })
            ), wp.element.createElement(
                'div',
                { className: this.props.className },
                output
            )];
        }
    }]);

    return mySelectResource;
}(Component);

registerBlockType('wp-oer/oer-resource-block', {
    title: __('OER Resource'),
    category: 'widgets',
    icon: {
        foreground: '#121212',
        src: 'media-document'
    },
    keywords: [__('OER'), __('Resource'), __('History')],
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
        selectedResource: {
            type: 'number',
            default: 0
        },
        showTitle: {
            type: 'boolean'
        },
        showDescription: {
            type: 'boolean'
        },
        showSubjectAreas: {
            type: 'boolean'
        },
        showGradeLevels: {
            type: 'boolean',
            default: ''
        },
        showThumbnail: {
            type: 'boolean'
        },
        subjectAreas: {
            type: 'array'
        },
        gradeLevels: {
            type: 'string'
        },
        featuredImage: {
            type: 'string',
            selector: 'img'
        },
        resourceUrl: {
            type: 'string'
        },
        alignment: {
            type: 'string',
            default: 'none'
        },
        blockWidth: {
            type: 'integer'
        },
        withBorder: {
            type: 'boolean'
        }
    },
    edit: mySelectResource,
    save: function save(props) {
        var wImage = false;
        var imgClass = "col-md-12";
        var wSubjects = false;
        var aCenter = false;
        var aLign = "none";
        var width = "auto";
        var border = "none";
        var wGrades = false;
        if (props.attributes.alignment == "center") {
            aCenter = true;
        } else {
            aLign = props.attributes.alignment;
        }
        if (props.attributes.showThumbnail === true && props.attributes.featuredImage !== "") {
            wImage = true;
            imgClass = "col-md-7";
        }
        if (props.attributes.showSubjectAreas === true && props.attributes.subjectAreas.length > 0) wSubjects = true;
        if (props.attributes.blockWidth !== "") {
            width = props.attributes.blockWidth + "px";
        }
        if (props.attributes.withBorder === true) {
            border = "1px solid #cdcdcd";
        }
        if (wSubjects) {
            var listItems = props.attributes.subjectAreas.map(function (d) {
                return wp.element.createElement(
                    'li',
                    { key: d.name },
                    wp.element.createElement(
                        'span',
                        null,
                        wp.element.createElement(
                            'a',
                            { href: d.link },
                            d.name
                        )
                    )
                );
            });
        }
        
        if (props.attributes.showGradeLevels === true && props.attributes.hasOwnProperty("gradeLevels") && (props.attributes.gradeLevels !=="" && props.attributes.gradeLevels !== null) )
            wGrades = true;
        
        return wp.element.createElement(
            'div',
            { className: props.className, style: { textAlign: aCenter == true ? 'center' : 'auto' } },
            wp.element.createElement(
                'div',
                { className: 'post', style: { float: aLign, textAlign: 'left', width: width, overflow: 'hidden', border: border } },
                props.attributes.showTitle === true && wp.element.createElement(
                    'a',
                    { href: props.attributes.link },
                    wp.element.createElement('h2', { dangerouslySetInnerHTML: { __html: props.attributes.title } })
                ),
                wImage && wp.element.createElement(
                    'div',
                    { className: 'col-md-5' },
                    wp.element.createElement(
                        'a',
                        { href: props.attributes.resourceUrl },
                        wp.element.createElement('img', { src: props.attributes.featuredImage })
                    )
                ),
                wp.element.createElement(
                    'div',
                    { className: imgClass },
                    props.attributes.showDescription === true && wp.element.createElement('div', { dangerouslySetInnerHTML: { __html: props.attributes.content } })
                ),
                wSubjects && wp.element.createElement(
                    'div',
                    { 'class': 'row resource-block-subjects oer-rsrcctgries tagcloud' },
                    wp.element.createElement(
                        'ul',
                        null,
                        listItems
                    )
                ),
                wGrades && wp.element.createElement('div', { dangerouslySetInnerHTML: { __html: '<strong>Grade Levels</strong> : ' + props.attributes.gradeLevels } })
            )
        );
    }
});

/***/ })
/******/ ]);

!function(e){var t={};function n(s){if(t[s])return t[s].exports;var r=t[s]={i:s,l:!1,exports:{}};return e[s].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=e,n.c=t,n.d=function(e,t,s){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:s})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var s=Object.create(null);if(n.r(s),Object.defineProperty(s,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)n.d(s,r,function(t){return e[t]}.bind(null,r));return s},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=0)}([function(e,t){function n(e){return(n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function s(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function r(e,t){for(var n=0;n<t.length;n++){var s=t[n];s.enumerable=s.enumerable||!1,s.configurable=!0,"value"in s&&(s.writable=!0),Object.defineProperty(e,s.key,s)}}function o(e,t,n){return t&&r(e.prototype,t),n&&r(e,n),e}function a(e,t){return(a=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}function l(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,s=p(e);if(t){var r=p(this).constructor;n=Reflect.construct(s,arguments,r)}else n=s.apply(this,arguments);return u(this,n)}}function u(e,t){return!t||"object"!==n(t)&&"function"!=typeof t?c(e):t}function c(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function p(e){return(p=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}var b=wp.i18n.__,d=wp.blocks.registerBlockType,f=wp.blockEditor.InspectorControls,h=wp.components.SelectControl,m=wp.element.Component,y=wp.components.CheckboxControl,S=(wp.components.NumberControl,wp.components.TextControl,wp.element.createElement,wp.components.PanelBody),v=wp.components.PanelRow,j=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&a(e,t)}(n,e);var t=l(n);function n(e){var r;return s(this,n),(r=t.apply(this,arguments)).state=r.constructor.getInitialState(r.props.attributes.selectedSubjectResources),r.props=e,r.getOptions=r.getOptions.bind(c(r)),r.getSubjectAreas=r.getSubjectAreas.bind(c(r)),r.sortSubjects=r.sortSubjects.bind(c(r)),r.getChildSubjects=r.getChildSubjects.bind(c(r)),r.getOptions(),r.getSubjectAreas(),r.onChangeDisplayCount=r.onChangeDisplayCount.bind(c(r)),r.onChangeSelectedSubject=r.onChangeSelectedSubject.bind(c(r)),r.onChangeSorting=r.onChangeSorting.bind(c(r)),r}return o(n,null,[{key:"getInitialState",value:function(e){return{posts:[],subjects:[],selectedSubject:e,selectedSubjects:[],childSubjects:[],post:{}}}}]),o(n,[{key:"getOptions",value:function(){var e=this;return(new wp.api.collections.Posts).fetch().then((function(t){if(t&&0!==e.state.selectedSubject){var n=t.find((function(t){return t.id==e.state.selectedSubject}));e.setState({post:n,posts:t})}else e.setState({posts:t})}))}},{key:"getSubjectAreas",value:function(){var e=this,t=new wp.api.models.ResourceSubjectArea,n=[];for(i=1;i<=5;i++)t.fetch({data:{per_page:100,page:i}}).then((function(t){t.length>0&&(n=[].concat.apply(n,t),e.setState({subjects:n}))}));return n}},{key:"onChangeDisplayCount",value:function(e){this.setState({displayCount:e}),this.props.setAttributes({displayCount:e})}},{key:"onChangeSelectedSubject",value:function(e){var t=this.state.posts.find((function(t){return t.id==parseInt(e)}));this.setState({selectedSubject:parseInt(e),post:t}),this.props.setAttributes({selectedSubject:parseInt(e),title:t.title.rendered,content:t.excerpt.rendered,link:t.link})}},{key:"onChangeSorting",value:function(e){this.setState({sort:e}),this.props.setAttributes({sort:e})}},{key:"sortSubjects",value:function(e,t){return e.label<t.label?-1:e.label>t.label?1:0}},{key:"getChildSubjects",value:function(e){var t=this,n=new wp.api.models.ResourceSubjectArea,s=[];n.fetch({data:{per_page:100,parent:e}}).then((function(e){if(e.length>0)return s=(s=[].concat.apply(s,e)).sort(t.sortSubjects),t.setState({childSubjects:s}),s}))}},{key:"render",value:function(){var e,t=[{value:0,label:b("Select a Subject")}],n=[],s=[],r={},o=b("Loading Resources"),a=[{value:"5",label:b("5")},{value:"10",label:b("10")},{value:"15",label:b("15")},{value:"20",label:b("20")},{value:"25",label:b("25")},{value:"30",label:b("30")}],l=[{value:"dateupdated",label:b("Date Updated")},{value:"dateadded",label:b("Date Added")},{value:"title",label:b("Title a-z")}];(this.props.className+="loading",this.state.posts.length>0)?(o=b("We have %d posts. Choose one.").replace("%d",this.state.posts.length),this.state.posts.forEach((function(e){t.push({value:e.id,label:e.title.rendered})}))):o=b("No posts found. Please create some first.");this.state.subjects=this.state.subjects.sort((function(e,t){return e.name-t.name})),void 0!==this.state.subjects&&this.state.subjects.forEach((function(t){0==t.parent?n.push({value:t.id,label:t.name}):(e=t.parent,void 0===r[t.parent]?r[e]=[{value:t.id,label:t.name}]:r[e].push({value:t.id,label:t.name}))}));var u=this;return n=n.sort(this.sortSubjects),jQuery.each(n,(function(e,t){var n=!!u.props.attributes.selectedSubjects&&u.props.attributes.selectedSubjects.indexOf(t.value)>-1;s.push(wp.element.createElement(y,{value:t.value,label:t.label,name:"selectedSubjects[]",checked:n,onChange:function(e){var n=u.props.attributes.selectedSubjects;e?-1===n.indexOf(t.value)&&n.push(t.value):n=u.props.attributes.selectedSubjects.filter((function(e){return e!==t.value})),this.props.setAttributes({selectedSubjects:n})}}));var o=r[t.value];o=o.sort(u.sortSubjects),jQuery.each(o,(function(e,t){var n=!!u.props.attributes.selectedSubjects&&u.props.attributes.selectedSubjects.indexOf(t.value)>-1;s.push(wp.element.createElement(y,{value:t.value,label:t.label,name:"selectedSubjects[]",checked:n,className:"oer-child-subject",onChange:function(e){var n=u.props.attributes.selectedSubjects;e?-1===n.indexOf(t.value)&&n.push(t.value):n=u.props.attributes.selectedSubjects.filter((function(e){return e!==t.value})),this.props.setAttributes({selectedSubjects:n})}}))}))})),void 0!==this.state.post?this.state.post.hasOwnProperty("title")&&(o=wp.element.createElement("div",{className:"post"},wp.element.createElement("a",{href:this.state.post.link},wp.element.createElement("h2",{dangerouslySetInnerHTML:{__html:this.state.post.title.rendered}})),wp.element.createElement("p",{dangerouslySetInnerHTML:{__html:this.state.post.excerpt.rendered}})),this.props.className+=" has-post"):this.props.className+=" no-post",[!!this.props.isSelected&&wp.element.createElement(f,{key:"subjectOptions"},wp.element.createElement(S,{title:"OER Subject Resources Option",initialOpen:!0,className:"subject-block"},wp.element.createElement(v,null,wp.element.createElement("div",{className:"form-group"},wp.element.createElement(h,{label:b("# of Resources to display:"),value:this.props.attributes.displayCount,options:a,onChange:this.onChangeDisplayCount})),wp.element.createElement("div",{className:"form-group oer-inspector-subjects"},wp.element.createElement("label",null,"Select subject(s);"),wp.element.createElement("div",{className:"editor-post-taxonomies__hierarchical-terms-list"},s)),wp.element.createElement("div",{className:"form-group"},wp.element.createElement(h,{label:b("Sort:"),value:this.props.attributes.sort,options:l,onChange:this.onChangeSorting}))))),wp.element.createElement("div",{className:this.props.className},o)]}}]),n}(m);d("wp-oer-plugin/oer-subject-resources-block",{title:b("OER Subject Resources"),category:"widgets",icon:{foreground:"#121212",src:"media-document"},keywords:[b("OER"),b("Resource"),b("Subject Areas")],attributes:{content:{type:"string",selector:"p"},title:{type:"string",selector:"h2"},link:{type:"string",selector:"a"},displayCount:{type:"string"},selectedSubject:{type:"number",default:0},sort:{type:"string",default:"dateupdated"},selectedSubjectResources:{type:"array"},subjects:{type:"array"},selectedSubjects:{type:"array"},childSubjects:{type:"array"}},edit:j,save:function(e){if(0!==e.attributes.selectedSubject)return wp.element.createElement("div",{className:e.className},wp.element.createElement("div",{className:"post"},wp.element.createElement("a",{href:e.attributes.link},wp.element.createElement("h2",{dangerouslySetInnerHTML:{__html:e.attributes.title}})),wp.element.createElement("p",{dangerouslySetInnerHTML:{__html:e.attributes.content}})))}})}]);
(window.webpackJsonp=window.webpackJsonp||[]).push([[1],{5:function(e,t,c){}}]),function(e){function t(t){for(var r,l,o=t[0],a=t[1],i=t[2],b=0,p=[];b<o.length;b++)l=o[b],Object.prototype.hasOwnProperty.call(s,l)&&s[l]&&p.push(s[l][0]),s[l]=0;for(r in a)Object.prototype.hasOwnProperty.call(a,r)&&(e[r]=a[r]);for(u&&u(t);p.length;)p.shift()();return n.push.apply(n,i||[]),c()}function c(){for(var e,t=0;t<n.length;t++){for(var c=n[t],r=!0,o=1;o<c.length;o++){var a=c[o];0!==s[a]&&(r=!1)}r&&(n.splice(t--,1),e=l(l.s=c[0]))}return e}var r={},s={0:0},n=[];function l(t){if(r[t])return r[t].exports;var c=r[t]={i:t,l:!1,exports:{}};return e[t].call(c.exports,c,c.exports,l),c.l=!0,c.exports}l.m=e,l.c=r,l.d=function(e,t,c){l.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:c})},l.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},l.t=function(e,t){if(1&t&&(e=l(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var c=Object.create(null);if(l.r(c),Object.defineProperty(c,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)l.d(c,r,function(t){return e[t]}.bind(null,r));return c},l.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return l.d(t,"a",t),t},l.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},l.p="";var o=window.webpackJsonp=window.webpackJsonp||[],a=o.push.bind(o);o.push=t,o=o.slice();for(var i=0;i<o.length;i++)t(o[i]);var u=a;n.push([8,1]),c()}([function(e,t){!function(){e.exports=this.wp.element}()},function(e,t){!function(){e.exports=this.wp.i18n}()},function(e,t){!function(){e.exports=this.wp.components}()},function(e,t){!function(){e.exports=this.wp.blocks}()},function(e,t){!function(){e.exports=this.wp.blockEditor}()},,function(e,t){!function(){e.exports=this.wp.apiFetch}()},function(e,t,c){},function(e,t,c){"use strict";c.r(t);var r=c(3),s=c(1),n=(c(5),c(0)),l=c(4),o=c(2);c(6),c(7);Object(r.registerBlockType)("wp-oer-plugin/wp-oer-resources-block",{title:Object(s.__)("WP OER Subject Resources","wp-oer-resources-block"),description:Object(s.__)("This is a block that displays resources according to subject areas.","wp-oer-resources-block"),category:"widgets",icon:{foreground:"#121212",src:"welcome-widgets-menus"},keywords:[Object(s.__)("OER"),Object(s.__)("Resource"),Object(s.__)("Subject Areas")],attributes:{content:{type:"string",selector:"p"},title:{type:"string",selector:"h2"},link:{type:"string",selector:"a"},displayCount:{type:"string",selector:"display-count",default:5},selectedSubject:{type:"number",default:0},sort:{type:"string",selector:"sort-option",default:"modified"},selectedSubjectResources:{type:"array"},subjects:{type:"array"},selectedSubjects:{type:"array"},childSubjects:{type:"array"}},edit:function(e){var t,c,r=[],a=[],i=e.attributes,u=e.setAttributes,b=function(e){i.selectedSubjects;var t=i.displayCount,c=i.sort;(new wp.api.collections.Resource).fetch({data:{per_page:t,orderby:c,order:"asc"}}).then((function(e){u({selectedSubjectResources:e})}))};wp.apiFetch({url:"/curriculum/wp-json/oer/v2/subjects"}).then((function(e){u({subjects:e})}));var p=[{value:"5",label:Object(s.__)("5")},{value:"10",label:Object(s.__)("10")},{value:"15",label:Object(s.__)("15")},{value:"20",label:Object(s.__)("20")},{value:"25",label:Object(s.__)("25")},{value:"30",label:Object(s.__)("30")}],d=[{value:"modified",label:Object(s.__)("Date Updated")},{value:"date",label:Object(s.__)("Date Added")},{value:"title",label:Object(s.__)("Title a-z")}];void 0===i.subjects?r=Object(s.__)("Loading..."):a=i.subjects.sort((function(e,t){return e.label<t.label?-1:e.label>t.label?1:0}));var m=a.map((function(e,t){var c=-1!==i.selectedSubjects.indexOf(e.term_id);return Object(n.createElement)(o.CheckboxControl,{value:e.term_id,label:e.name,key:t,name:"selectedSubjects[]",className:"child"==e.type?"oer-subject-area-child":"oer-subject-area-parent",checked:c,id:e.term_id,onChange:function(t){var r=i.selectedSubjects?i.selectedSubjects:[];console.log(r),t?(void 0!==r?-1===r.indexOf(e.term_id)&&r.push(e.term_id):r.push(e.term_id),c=!0):(r=i.selectedSubjects.filter((function(t){return t!==e.term_id})),c=!1),jQuery("#"+e.term_id).prop("checked",c),u({selectedSubjects:r})}})}));if(b(!1),void 0!==i.selectedSubjectResources&&i.selectedSubjectResources.length>0){var j=d.map((function(e,t){return Object(n.createElement)("li",{key:e.value,value:e.value},e.label)}));void 0!==i.sort&&(c=i.sort.ucwords());var f="Browse "+i.displayCount+" resources";t=Object(n.createElement)("div",{className:"oer-snglrsrchdng"},f,Object(n.createElement)("div",{className:"sort-box"},Object(n.createElement)("span",{className:"sortoption"},c),Object(n.createElement)("span",{className:"sort-resources",title:"Sort resources",tabIndex:"0",role:"button"},Object(n.createElement)("i",{className:"fa fa-sort","aria-hidden":"true"})),Object(n.createElement)("div",{className:"sort-options"},Object(n.createElement)("ul",{className:"sortList"},j)),Object(n.createElement)(o.SelectControl,{className:"sort-selectbox",value:i.sort,options:d}))),i.selectedSubjectResources.map((function(e,t){var c=[];e.subject_details.map((function(e,t){c.push(Object(n.createElement)("span",{key:t},Object(n.createElement)("a",{href:e.link},e.name)))})),r.push(Object(n.createElement)("div",{key:t,className:"post oer-snglrsrc"},Object(n.createElement)("a",{href:e.link,className:"oer-resource-link"},Object(n.createElement)("div",{className:"oer-snglimglft"},Object(n.createElement)("img",{src:e.fimg_url}))),Object(n.createElement)("div",{className:"oer-snglttldscrght"},Object(n.createElement)("div",{className:"ttl"},Object(n.createElement)("a",{href:e.link},e.title.rendered)),Object(n.createElement)("div",{className:"post-meta"},Object(n.createElement)("span",{className:"post-meta-box post-meta-grades"},Object(n.createElement)("strong",null,"Grades: "),e.oer_grade),Object(n.createElement)("span",{className:"post-meta-box post-meta-domain"},Object(n.createElement)("strong",null,"Domain: "),Object(n.createElement)("a",{href:e.link},e.domain))),Object(n.createElement)("div",{className:"desc"},Object(n.createElement)("div",{dangerouslySetInnerHTML:{__html:e.resource_excerpt}})),Object(n.createElement)("div",{className:"tagcloud"},c))))}))}return Object(n.createElement)("div",null,Object(n.createElement)(l.InspectorControls,{className:"oer-subject-resources-block-inspector"},Object(n.createElement)(o.PanelBody,{title:"OER Subject Resources Option",initialOpen:!0},Object(n.createElement)(o.PanelRow,null,Object(n.createElement)("div",{className:"form-group oer-inspector-subjects"},Object(n.createElement)("label",null,"Subject(s);"),Object(n.createElement)("div",{className:"editor-post-taxonomies__hierarchical-terms-list"},m))),Object(n.createElement)(o.PanelRow,null,Object(n.createElement)(o.SelectControl,{className:"display-count",label:Object(s.__)("# of Resources to display:"),value:i.displayCount,options:p,onChange:function(e){u({displayCount:e}),b(!0)}})),Object(n.createElement)(o.PanelRow,null,Object(n.createElement)(o.SelectControl,{className:"sort-option",label:Object(s.__)("Sort:"),value:i.sort,options:d,onChange:function(e){u({sort:e}),b(!0)}})))),Object(n.createElement)("div",{className:"oer-subject-resources-list"},t,r))}})}]);
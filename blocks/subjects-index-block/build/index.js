(window.webpackJsonp_wp_oer_subjects_index=window.webpackJsonp_wp_oer_subjects_index||[]).push([[1],{6:function(e,t,o){}}]),function(e){function t(t){for(var n,r,u=t[0],c=t[1],a=t[2],b=0,p=[];b<u.length;b++)r=u[b],Object.prototype.hasOwnProperty.call(l,r)&&l[r]&&p.push(l[r][0]),l[r]=0;for(n in c)Object.prototype.hasOwnProperty.call(c,n)&&(e[n]=c[n]);for(i&&i(t);p.length;)p.shift()();return s.push.apply(s,a||[]),o()}function o(){for(var e,t=0;t<s.length;t++){for(var o=s[t],n=!0,u=1;u<o.length;u++){var c=o[u];0!==l[c]&&(n=!1)}n&&(s.splice(t--,1),e=r(r.s=o[0]))}return e}var n={},l={0:0},s=[];function r(t){if(n[t])return n[t].exports;var o=n[t]={i:t,l:!1,exports:{}};return e[t].call(o.exports,o,o.exports,r),o.l=!0,o.exports}r.m=e,r.c=n,r.d=function(e,t,o){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(r.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var n in e)r.d(o,n,function(t){return e[t]}.bind(null,n));return o},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="";var u=window.webpackJsonp_wp_oer_subjects_index=window.webpackJsonp_wp_oer_subjects_index||[],c=u.push.bind(u);u.push=t,u=u.slice();for(var a=0;a<u.length;a++)t(u[a]);var i=c;s.push([8,1]),o()}([function(e,t){e.exports=window.wp.element},function(e,t){e.exports=window.wp.components},function(e,t){e.exports=window.wp.i18n},function(e,t){e.exports=window.jQuery},function(e,t){e.exports=window.wp.blocks},function(e,t){e.exports=window.wp.blockEditor},,function(e,t,o){},function(e,t,o){"use strict";o.r(t);var n=o(4),l=o(2),s=(o(6),o(0)),r=o(5),u=o(1),c=o(3),a=o.n(c);o(7),Object(n.registerBlockType)("wp-oer-plugin/wp-oer-subjects-index",{title:Object(l.__)("OER Subjects Index","wp-oer-subjects-index"),description:Object(l.__)("This is the block version of the OER Subjects Index Shortcode","wp-oer-subjects-index"),category:"widgets",icon:"index-card",supports:{html:!1},attributes:{size:{type:"string",default:"large"},columns:{type:"integer",default:"3"},showCount:{type:"boolean",default:!0},showSublevels:{type:"boolean",default:!1},blockId:{type:"string"},isChanged:{type:"boolean",default:!1},firstLoad:{type:"boolean",default:!0}},edit:function(e){var t,o,n,c=!1,i=!1,b=e.attributes,p=e.setAttributes,d=(e.isSelected,e.className),w=e.clientId;return w!==b.blockId&&p({blockId:w}),(b.isChanged||1==b.firstLoad)&&(t={size:b.size,columns:b.columns,showCount:b.showCount,showSublevels:b.showSublevels},o=b.blockId,n={action:"display_subjects_index",size:t.size,columns:t.columns,showCount:t.showCount,showSublevels:t.showSublevels},a.a.ajax({url:wp_oer.ajaxurl,type:"POST",data:n,success:function(e){a()("#block-"+o+" .wp-block-wp-oer-plugin-wp-oer-subjects-index").html(""),a()("#block-"+o+" .wp-block-wp-oer-plugin-wp-oer-subjects-index").html(e)},error:function(e,t,o){console.log(o)}})),b.showCount&&(c=!0),b.showSublevels&&(i=!0),[Object(s.createElement)(s.Fragment,null,Object(s.createElement)(r.InspectorControls,null,Object(s.createElement)(u.PanelBody,{title:Object(l.__)("OER Subjects Index Options","wp-oer-subjects-index"),initialOpen:!0},Object(s.createElement)(u.PanelRow,null,Object(s.createElement)(u.SelectControl,{className:Object(l.__)("oer-subjects-size-options","wp-oer-subjects-index"),label:"Size:",value:b.size,options:[{label:"Select block size",value:""},{label:"Small",value:"small"},{label:"Medium",value:"medium"},{label:"Large",value:"large"}],onChange:function(e){p({size:e,isChanged:!0})}})),Object(s.createElement)(u.PanelRow,null,Object(s.createElement)(u.SelectControl,{className:Object(l.__)("oer-subjects-columns-options","wp-oer-subjects-index"),label:"Columns:",value:b.columns,options:[{label:"Select # of Columns",value:""},{label:"1 Column",value:"1"},{label:"2 Columns",value:"2"},{label:"3 Columns",value:"3"},{label:"4 Columns",value:"4"}],onChange:function(e){p({columns:e,isChanged:!0})}})),Object(s.createElement)(u.PanelRow,null,Object(s.createElement)(u.CheckboxControl,{label:"Show count",help:"Do you want resource count to be shown in every category?",checked:c,onChange:function(e){p({showCount:e,isChanged:!0})}})),Object(s.createElement)(u.PanelRow,null,Object(s.createElement)(u.CheckboxControl,{label:"Show sublevels",help:"Do you want to show sublevels?",checked:i,onChange:function(e){p({showSublevels:e,isChanged:!0})}}))))),Object(s.createElement)("div",{className:d},"Loading...")]}})}]);
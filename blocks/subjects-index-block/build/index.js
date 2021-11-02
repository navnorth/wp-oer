(window.webpackJsonp_wp_oer_subjects_index=window.webpackJsonp_wp_oer_subjects_index||[]).push([[1],{6:function(e,t,o){}}]),function(e){function t(t){for(var n,c,r=t[0],u=t[1],i=t[2],b=0,p=[];b<r.length;b++)c=r[b],Object.prototype.hasOwnProperty.call(l,c)&&l[c]&&p.push(l[c][0]),l[c]=0;for(n in u)Object.prototype.hasOwnProperty.call(u,n)&&(e[n]=u[n]);for(a&&a(t);p.length;)p.shift()();return s.push.apply(s,i||[]),o()}function o(){for(var e,t=0;t<s.length;t++){for(var o=s[t],n=!0,r=1;r<o.length;r++){var u=o[r];0!==l[u]&&(n=!1)}n&&(s.splice(t--,1),e=c(c.s=o[0]))}return e}var n={},l={0:0},s=[];function c(t){if(n[t])return n[t].exports;var o=n[t]={i:t,l:!1,exports:{}};return e[t].call(o.exports,o,o.exports,c),o.l=!0,o.exports}c.m=e,c.c=n,c.d=function(e,t,o){c.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},c.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},c.t=function(e,t){if(1&t&&(e=c(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(c.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var n in e)c.d(o,n,function(t){return e[t]}.bind(null,n));return o},c.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return c.d(t,"a",t),t},c.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},c.p="";var r=window.webpackJsonp_wp_oer_subjects_index=window.webpackJsonp_wp_oer_subjects_index||[],u=r.push.bind(r);r.push=t,r=r.slice();for(var i=0;i<r.length;i++)t(r[i]);var a=u;s.push([8,1]),o()}([function(e,t){e.exports=window.wp.element},function(e,t){e.exports=window.wp.i18n},function(e,t){e.exports=window.wp.components},function(e,t){e.exports=window.jQuery},function(e,t){e.exports=window.wp.blocks},function(e,t){e.exports=window.wp.blockEditor},,function(e,t,o){},function(e,t,o){"use strict";o.r(t);var n=o(4),l=o(1),s=(o(6),o(0)),c=o(5),r=o(2),u=o(3),i=o.n(u);o(7),Object(n.registerBlockType)("wp-oer-plugin/wp-oer-subjects-index",{title:Object(l.__)("OER Subjects Index","wp-oer-subjects-index"),description:Object(l.__)("This is the block version of the OER Subjects Index Shortcode","wp-oer-subjects-index"),category:"widgets",icon:"index-card",supports:{html:!1},attributes:{size:{type:"string",default:"large"},columns:{type:"integer",default:"3"},showCount:{type:"boolean",default:!0},showSublevels:{type:"boolean",default:!1},blockId:{type:"string"},isChanged:{type:"boolean",default:!1},firstLoad:{type:"boolean",default:!0}},edit:function(e){var t,o,n,u=!1,a=!1,b=e.attributes,p=e.setAttributes,d=(e.isSelected,e.className),w=e.clientId;return w!==b.blockId&&p({blockId:w}),(b.isChanged||1==b.firstLoad)&&(t={size:b.size,columns:b.columns,showCount:b.showCount,showSublevels:b.showSublevels},o=b.blockId,n={action:"display_subjects_index",size:t.size,columns:t.columns,showCount:t.showCount,showSublevels:t.showSublevels},i.a.ajax({url:wp_oer.ajaxurl,type:"POST",data:n,success:function(e){i()("#block-"+o+" .wp-block-wp-oer-plugin-wp-oer-subjects-index").html(""),i()("#block-"+o+" .wp-block-wp-oer-plugin-wp-oer-subjects-index").html(e)},error:function(e,t,o){console.log(o)}})),b.showCount&&(u=!0),b.showSublevels&&(a=!0),[Object(s.createElement)(s.Fragment,null,Object(s.createElement)(c.InspectorControls,null,Object(s.createElement)(r.PanelBody,{title:Object(l.__)("OER Subjects Index Options","wp-oer-subjects-index"),initialOpen:!0},Object(s.createElement)(r.PanelRow,null,Object(s.createElement)(r.SelectControl,{className:Object(l.__)("oer-subjects-size-options","wp-oer-subjects-index"),label:Object(l.__)("Size:","wp-oer-subjects-index"),value:b.size,options:[{label:"Select block size",value:""},{label:"Small",value:"small"},{label:"Medium",value:"medium"},{label:"Large",value:"large"}],onChange:function(e){p({size:e,isChanged:!0})}})),Object(s.createElement)(r.PanelRow,null,Object(s.createElement)(r.SelectControl,{className:Object(l.__)("oer-subjects-columns-options","wp-oer-subjects-index"),label:Object(l.__)("Columns:","wp-oer-subjects-index"),value:b.columns,options:[{label:"Select # of Columns",value:""},{label:"1 Column",value:"1"},{label:"2 Columns",value:"2"},{label:"3 Columns",value:"3"},{label:"4 Columns",value:"4"}],onChange:function(e){p({columns:e,isChanged:!0})}})),Object(s.createElement)(r.PanelRow,null,Object(s.createElement)(r.CheckboxControl,{label:Object(l.__)("Show count","wp-oer-subjects-index"),help:Object(l.__)("Do you want resource count to be shown in every category?","wp-oer-subjects-index"),checked:u,onChange:function(e){p({showCount:e,isChanged:!0})}})),Object(s.createElement)(r.PanelRow,null,Object(s.createElement)(r.CheckboxControl,{label:Object(l.__)("Show sublevels","wp-oer-subjects-index"),help:Object(l.__)("Do you want to show sublevels?","wp-oer-subjects-index"),checked:a,onChange:function(e){p({showSublevels:e,isChanged:!0})}}))))),Object(s.createElement)("div",{className:d},"Loading...")]}})}]);
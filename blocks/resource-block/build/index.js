!function(){"use strict";var e,o={43:function(){var e=window.wp.blocks,o=window.wp.i18n,r=window.wp.element,t=window.wp.blockEditor;(0,e.registerBlockType)("wp-oer-plugin/wp-oer-resource-block",{title:(0,o.__)("OER Resource Block","wp-oer-resource-block"),description:(0,o.__)("This block displays a single resource on a page.","wp-oer-resource-block"),category:"oer-block-category",icon:{foreground:"#121212",src:"media-document"},keywords:[(0,o.__)("OER","oer-subject-resource-block"),(0,o.__)("Resource","oer-subject-resource-block")],attributes:{selectedResource:{type:"number",default:0},alignment:{type:"string"},width:{type:"string"},showThumbnail:{type:"boolean",default:!1},showTitle:{type:"boolean",default:!1},showDescription:{type:"boolean",default:!1},showSubjects:{type:"boolean",default:!1},showGrades:{type:"boolean",default:!1},withBorder:{type:"boolean",default:!1},blockId:{type:"string"},firstLoad:{type:"boolean",default:!0}},edit:function(){return(0,r.createElement)("p",(0,t.useBlockProps)(),(0,o.__)("Wp Oer Resource Block – hello from the editor!","wp-oer-resource-block"))}})}},r={};function t(e){var n=r[e];if(void 0!==n)return n.exports;var c=r[e]={exports:{}};return o[e](c,c.exports,t),c.exports}t.m=o,e=[],t.O=function(o,r,n,c){if(!r){var u=1/0;for(a=0;a<e.length;a++){r=e[a][0],n=e[a][1],c=e[a][2];for(var l=!0,i=0;i<r.length;i++)(!1&c||u>=c)&&Object.keys(t.O).every((function(e){return t.O[e](r[i])}))?r.splice(i--,1):(l=!1,c<u&&(u=c));if(l){e.splice(a--,1);var s=n();void 0!==s&&(o=s)}}return o}c=c||0;for(var a=e.length;a>0&&e[a-1][2]>c;a--)e[a]=e[a-1];e[a]=[r,n,c]},t.o=function(e,o){return Object.prototype.hasOwnProperty.call(e,o)},function(){var e={826:0,46:0};t.O.j=function(o){return 0===e[o]};var o=function(o,r){var n,c,u=r[0],l=r[1],i=r[2],s=0;if(u.some((function(o){return 0!==e[o]}))){for(n in l)t.o(l,n)&&(t.m[n]=l[n]);if(i)var a=i(t)}for(o&&o(r);s<u.length;s++)c=u[s],t.o(e,c)&&e[c]&&e[c][0](),e[u[s]]=0;return t.O(a)},r=self.webpackChunkwp_oer_resource_block=self.webpackChunkwp_oer_resource_block||[];r.forEach(o.bind(null,0)),r.push=o.bind(null,r.push.bind(r))}();var n=t.O(void 0,[46],(function(){return t(43)}));n=t.O(n)}();
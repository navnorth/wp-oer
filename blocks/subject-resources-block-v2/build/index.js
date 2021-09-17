!function(){"use strict";var e,t={722:function(e,t,s){var r=window.wp.blocks,l=window.wp.i18n,o=window.wp.element,a=window.wp.blockEditor,c=window.wp.components,n=(window.wp.apiFetch,window.jQuery),i=s.n(n);(0,r.registerBlockType)("oer-block/subject-resources-block",{title:(0,l.__)("WP OER Subject Resources Block v2","oer-subject-resources-block"),description:(0,l.__)("Displays list of resources according to subject areas.","oer-subject-resources-block"),category:"widgets",icon:{foreground:"#121212",src:"welcome-widgets-menus"},keywords:[(0,l.__)("OER"),(0,l.__)("Resource"),(0,l.__)("Subject Areas")],attributes:{content:{type:"string",selector:"p"},title:{type:"string",selector:"h2"},link:{type:"string",selector:"a"},displayCount:{type:"string",selector:"display-count",default:5},selectedSubject:{type:"number",default:0},sort:{type:"string",selector:"sort-option",default:"modified"},selectedSubjectResources:{type:"array"},subjects:{type:"array"},selectedSubjects:{type:"string"},childSubjects:{type:"array"},isChanged:{type:"boolean",default:!1},blockId:{type:"string"},firstLoad:{type:"boolean",default:!0}},edit:function(e){var t,s=[],r=[];let n,u;var d;String.prototype.ucwords=function(){return this.toLowerCase().replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,(function(e){return e.toUpperCase()}))};const{attributes:m,setAttributes:p,clientId:b}=e;b!==m.blockId&&p({blockId:b}),m.firstLoad&&(wp.apiFetch({url:oer_subject_resources.home_url+"/wp-json/oer/v2/subjects"}).then((e=>{p({subjects:e})})),(e=>{m.selectedSubjects;var t=m.displayCount,s=m.sort;"object"==typeof wp.api.collections&&(e=>{try{new e}catch(e){return!1}return!0})(wp.api.collections.Resource)&&(new wp.api.collections.Resource).fetch({data:{per_page:t,orderby:s,order:"asc"}}).then((e=>{p({selectedSubjectResources:e})}))})(),p({firstLoad:!1}));let _=[{value:"5",label:(0,l.__)("5")},{value:"10",label:(0,l.__)("10")},{value:"15",label:(0,l.__)("15")},{value:"20",label:(0,l.__)("20")},{value:"25",label:(0,l.__)("25")},{value:"30",label:(0,l.__)("30")}],f=[{value:"modified",label:(0,l.__)("Date Updated")},{value:"date",label:(0,l.__)("Date Added")},{value:"title",label:(0,l.__)("Title a-z")}];void 0===m.subjects?s=(0,l.__)("Loading..."):r=m.subjects.sort(((e,t)=>e.label<t.label?-1:e.label>t.label?1:0));const v=r.map(((e,s)=>(0,o.createElement)(c.CheckboxControl,{value:e.term_id,label:e.name,key:s,name:"selectedSubjects[]",className:"child"==e.type?"oer-subject-area-child":"oer-subject-area-parent",checked:t,id:e.term_id,onChange:function(s){let r=m.selectedSubjects?m.selectedSubjects:[];s?(void 0!==r?-1===r.indexOf(e.term_id)&&r.push(e.term_id):r.push(e.term_id),t=!0):(r=m.selectedSubjects.filter((t=>t!==e.term_id)),t=!1),jQuery("#"+e.term_id).prop("checked",t),p({selectedSubjects:r}),console.log(m.selectedSubjects)}})));if(!m.isChanged){let e=(e=>{var t={action:"oer_get_subject_resources",attributes:e};i().ajax({url:oer_subject_resources.ajax_url,type:"POST",data:t,success:function(e){console.log(e)},error:function(e,t,s){console.log(s)}})})(m);console.log(e),p({isChanged:!0})}if(void 0!==m.selectedSubjectResources&&m.selectedSubjectResources.length>0){n=f.map(((e,t)=>(0,o.createElement)("li",{key:e.value,value:e.value},e.label))),void 0!==m&&(d=m.sort.ucwords());const e="Browse "+m.selectedSubjectResources.length.toString()+" resources";u=(0,o.createElement)("div",{className:"oer-snglrsrchdng"},e,(0,o.createElement)("div",{className:"sort-box"},(0,o.createElement)("span",{className:"sortoption"},d),(0,o.createElement)("span",{className:"sort-resources",title:"Sort resources",tabindex:"0",role:"button"},(0,o.createElement)("i",{className:"fa fa-sort","aria-hidden":"true"})),(0,o.createElement)("div",{className:"sort-options"},(0,o.createElement)("ul",{className:"sortList"},n)),(0,o.createElement)(c.SelectControl,{className:"sort-selectbox",value:m.sort,options:f}))),m.selectedSubjectResources.map(((e,t)=>{let r=[];null!==e.subject_details&&e.subject_details.map(((e,t)=>{r.push((0,o.createElement)("span",{key:t},(0,o.createElement)("a",{href:e.link},e.name)))})),s.push((0,o.createElement)("div",{key:t,className:"post oer-snglrsrc"},(0,o.createElement)("a",{href:e.link,className:"oer-resource-link"},(0,o.createElement)("div",{className:"oer-snglimglft"},(0,o.createElement)("img",{src:e.fimg_url}))),(0,o.createElement)("div",{className:"oer-snglttldscrght"},(0,o.createElement)("div",{className:"ttl"},(0,o.createElement)("a",{href:e.link},e.title.rendered)),(0,o.createElement)("div",{className:"post-meta"},(0,o.createElement)("span",{className:"post-meta-box post-meta-grades"},(0,o.createElement)("strong",null,"Grades: "),e.oer_grade),(0,o.createElement)("span",{className:"post-meta-box post-meta-domain"},(0,o.createElement)("strong",null,"Domain: "),(0,o.createElement)("a",{href:e.link},e.domain))),(0,o.createElement)("div",{className:"desc"},(0,o.createElement)("div",{dangerouslySetInnerHTML:{__html:e.resource_excerpt}})),(0,o.createElement)("div",{className:"tagcloud"},r))))}))}return[(0,o.createElement)(o.Fragment,null,(0,o.createElement)(a.InspectorControls,{className:"oer-subject-resources-block-inspector"},(0,o.createElement)(c.PanelBody,{title:"OER Subject Resources Option",initialOpen:!0},(0,o.createElement)(c.PanelRow,null,(0,o.createElement)("div",{className:"form-group oer-inspector-subjects"},(0,o.createElement)("label",null,"Subject(s);"),(0,o.createElement)("div",{className:"editor-post-taxonomies__hierarchical-terms-list"},v))),(0,o.createElement)(c.PanelRow,null,(0,o.createElement)(c.SelectControl,{className:"display-count",label:(0,l.__)("# of Resources to display:"),value:m.displayCount,options:_,onChange:e=>{this.setAttributes({displayCount:e,isChanged:!0}),this.selectResources(!0)}})),(0,o.createElement)(c.PanelRow,null,(0,o.createElement)(c.SelectControl,{className:"sort-option",label:(0,l.__)("Sort:"),value:m.sort,options:f,onChange:e=>{this.setAttributes({sort:e,isChanged:!0}),this.selectResources(!0)}}))))),(0,o.createElement)("div",{className:"oer-subject-resources-list"},s)]}})}},s={};function r(e){var l=s[e];if(void 0!==l)return l.exports;var o=s[e]={exports:{}};return t[e](o,o.exports,r),o.exports}r.m=t,e=[],r.O=function(t,s,l,o){if(!s){var a=1/0;for(u=0;u<e.length;u++){s=e[u][0],l=e[u][1],o=e[u][2];for(var c=!0,n=0;n<s.length;n++)(!1&o||a>=o)&&Object.keys(r.O).every((function(e){return r.O[e](s[n])}))?s.splice(n--,1):(c=!1,o<a&&(a=o));if(c){e.splice(u--,1);var i=l();void 0!==i&&(t=i)}}return t}o=o||0;for(var u=e.length;u>0&&e[u-1][2]>o;u--)e[u]=e[u-1];e[u]=[s,l,o]},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,{a:t}),t},r.d=function(e,t){for(var s in t)r.o(t,s)&&!r.o(e,s)&&Object.defineProperty(e,s,{enumerable:!0,get:t[s]})},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},function(){var e={826:0,46:0};r.O.j=function(t){return 0===e[t]};var t=function(t,s){var l,o,a=s[0],c=s[1],n=s[2],i=0;if(a.some((function(t){return 0!==e[t]}))){for(l in c)r.o(c,l)&&(r.m[l]=c[l]);if(n)var u=n(r)}for(t&&t(s);i<a.length;i++)o=a[i],r.o(e,o)&&e[o]&&e[o][0](),e[a[i]]=0;return r.O(u)},s=self.webpackChunkoer_subject_resources_block=self.webpackChunkoer_subject_resources_block||[];s.forEach(t.bind(null,0)),s.push=t.bind(null,s.push.bind(s))}();var l=r.O(void 0,[46],(function(){return r(722)}));l=r.O(l)}();
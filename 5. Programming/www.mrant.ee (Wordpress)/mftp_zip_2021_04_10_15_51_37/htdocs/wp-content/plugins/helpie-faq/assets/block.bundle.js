!function(e){var t={};function r(n){if(t[n])return t[n].exports;var o=t[n]={i:n,l:!1,exports:{}};return e[n].call(o.exports,o,o.exports,r),o.l=!0,o.exports}r.m=e,r.c=t,r.d=function(e,t,n){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)r.d(n,o,function(t){return e[t]}.bind(null,o));return n},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="./",r(r.s=12)}({0:function(e,t,r){"use strict";(function(e){var r="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e};var n,o,i,a="undefined"!=typeof Symbol?Symbol("immer-nothing"):(i=!0,(o="immer-nothing")in(n={})?Object.defineProperty(n,o,{value:i,enumerable:!0,configurable:!0,writable:!0}):n[o]=i,n),u="undefined"!=typeof Symbol?Symbol("immer-proxy-state"):"__$immer_state",l="An immer producer returned a new value *and* modified its draft. Either return a new value *or* modify the draft.",c=!("verifyMinified"!==function(){}.name),f="undefined"!=typeof Proxy&&"undefined"!=typeof Reflect;function s(e){return!!e&&!!e[u]}function p(e){if(!e)return!1;if("object"!==(void 0===e?"undefined":r(e)))return!1;if(Array.isArray(e))return!0;var t=Object.getPrototypeOf(e);return null===t||t===Object.prototype}var y=Object.assign||function(e,t){for(var r in t)v(t,r)&&(e[r]=t[r]);return e};function h(e){if(Array.isArray(e))return e.slice();var t=void 0===e.__proto__?Object.create(null):{};return y(t,e)}function d(e,t){if(Array.isArray(e))for(var r=0;r<e.length;r++)t(r,e[r]);else for(var n in e)t(n,e[n])}function v(e,t){return Object.prototype.hasOwnProperty.call(e,t)}function b(e,t,r,n){if(s(e)){var o=e[u];if(!0!==o.modified)return o.base;if(!0===o.finalized)return o.copy;o.finalized=!0;var i=(a=f?o.copy:o.copy=h(e),y=t,m=r,g=n,w=(l=o).base,d(a,function(e,t){if(t!==w[e]){var r=m&&!v(l.assigned,e);a[e]=b(t,r&&y.concat(e),r&&m,g)}}),O=a,c&&Object.freeze(O),O);return function(e,t,r,n,o,i){var a,u,l,c,f;r&&(Array.isArray(o)?function(e,t,r,n,o,i){for(var a=Math.min(o.length,i.length),u=0;u<a;u++)if(e.assigned[u]&&o[u]!==i[u]){var l=t.concat(u);r.push({op:"replace",path:l,value:i[u]}),n.push({op:"replace",path:l,value:o[u]})}if(a<i.length){for(var c=a;c<i.length;c++){var f=t.concat(c);r.push({op:"add",path:f,value:i[c]})}n.push({op:"replace",path:t.concat("length"),value:o.length})}else if(a<o.length){r.push({op:"replace",path:t.concat("length"),value:i.length});for(var s=a;s<o.length;s++){var p=t.concat(s);n.push({op:"add",path:p,value:o[s]})}}}(e,t,r,n,o,i):(a=t,u=r,l=n,c=o,f=i,d(e.assigned,function(e,t){var r=c[e],n=f[e],o=t?e in c?"replace":"add":"remove";if(r!==c||"replace"!==o){var i=a.concat(e);u.push("remove"===o?{op:o,path:i}:{op:o,path:i,value:n}),l.push("add"===o?{op:"remove",path:i}:"remove"===o?{op:"add",path:i,value:r}:{op:"replace",path:i,value:r})}})))}(o,t,r,n,o.base,i),i}var a,l,y,m,g,w,O;return function e(t){p(t)&&(Object.isFrozen(t)||d(t,function(r,n){s(n)?t[r]=b(n):e(n)}))}(e),e}function m(e,t){return e===t?0!==e||1/e==1/t:e!=e&&t!=t}var g=null,w={get:function(e,t){if(t===u)return e;if(e.modified){var r=e.copy[t];return r===e.base[t]&&p(r)?e.copy[t]=E(e,r):r}if(v(e.proxies,t))return e.proxies[t];var n=e.base[t];return!s(n)&&p(n)?e.proxies[t]=E(e,n):n},has:function(e,t){return t in S(e)},ownKeys:function(e){return Reflect.ownKeys(S(e))},set:function(e,t,r){if(e.assigned[t]=!0,!e.modified){if(t in e.base&&m(e.base[t],r)||v(e.proxies,t)&&e.proxies[t]===r)return!0;j(e)}return e.copy[t]=r,!0},deleteProperty:function(e,t){return e.assigned[t]=!1,j(e),delete e.copy[t],!0},getOwnPropertyDescriptor:function(e,t){var r=e.modified?e.copy:v(e.proxies,t)?e.proxies:e.base,n=Reflect.getOwnPropertyDescriptor(r,t);return!n||Array.isArray(r)&&"length"===t||(n.configurable=!0),n},defineProperty:function(){throw new Error("Immer does not support defining properties on draft objects.")},setPrototypeOf:function(){throw new Error("Immer does not support `setPrototypeOf()`.")}},O={};function S(e){return!0===e.modified?e.copy:e.base}function j(e){e.modified||(e.modified=!0,e.copy=h(e.base),Object.assign(e.copy,e.proxies),e.parent&&j(e.parent))}function E(e,t){if(s(t))throw new Error("Immer bug. Plz report.");var r={modified:!1,assigned:{},finalized:!1,parent:e,base:t,copy:void 0,proxies:{}},n=Array.isArray(t)?Proxy.revocable([r],O):Proxy.revocable(r,w);return g.push(n),n.proxy}d(w,function(e,t){O[e]=function(){return arguments[0]=arguments[0][0],t.apply(this,arguments)}}),O.deleteProperty=function(e,t){if(isNaN(parseInt(t)))throw new Error("Immer does not support deleting properties from arrays: "+t);return w.deleteProperty.call(this,e[0],t)},O.set=function(e,t,r){if("length"!==t&&isNaN(parseInt(t)))throw new Error("Immer does not support setting non-numeric properties on arrays: "+t);return w.set.call(this,e[0],t,r)};var P={},_=null;function C(e){return e.hasCopy?e.copy:e.base}function x(e){e.modified||(e.modified=!0,e.parent&&x(e.parent))}function k(e){e.hasCopy||(e.hasCopy=!0,e.copy=h(e.base))}function A(e,t){var r=h(t);d(t,function(e){var t;Object.defineProperty(r,""+e,P[t=""+e]||(P[t]={configurable:!0,enumerable:!0,get:function(){return function(e,t){T(e);var r=C(e)[t];return!e.finalizing&&r===e.base[t]&&p(r)?(k(e),e.copy[t]=A(e,r)):r}(this[u],t)},set:function(e){!function(e,t,r){if(T(e),e.assigned[t]=!0,!e.modified){if(m(C(e)[t],r))return;x(e),k(e)}e.copy[t]=r}(this[u],t,e)}}))});var n,o,i,a={modified:!1,assigned:{},hasCopy:!1,parent:e,base:t,proxy:r,copy:void 0,finished:!1,finalizing:!1,finalized:!1};return n=r,o=u,i=a,Object.defineProperty(n,o,{value:i,enumerable:!1,writable:!0}),_.push(a),r}function T(e){if(!0===e.finished)throw new Error("Cannot use a proxy that has been revoked. Did you pass an object from inside an immer function to an async process? "+JSON.stringify(e.copy||e.base))}function R(e){for(var t=e.base,r=e.proxy,n=Object.keys(r),o=n.length;0!==o;){var i=n[--o];if(void 0===t[i]&&!v(t,i))return!0}return n.length!==Object.keys(t).length}function z(e){var t=e.proxy;if(t.length!==e.base.length)return!0;var r=Object.getOwnPropertyDescriptor(t,t.length-1);return!(!r||r.get)}function F(e,t,n){if(s(e)){var o=t.call(e,e);return void 0===o?e:o}var i=_;_=[];var a=n&&[],c=n&&[];try{var f=A(void 0,e),p=t.call(f,f);d(_,function(e,t){t.finalizing=!0});var y=void 0;if(void 0!==p&&p!==f){if(f[u].modified)throw new Error(l);y=b(p),a&&(a.push({op:"replace",path:[],value:y}),c.push({op:"replace",path:[],value:e}))}else n&&function e(t){if(t&&"object"===(void 0===t?"undefined":r(t))){var n=t[u];if(n){var o,i,a,l,c=n.proxy,f=n.base;if(Array.isArray(t)){if(z(n)){if(x(n),n.assigned.length=!0,c.length<f.length)for(var s=c.length;s<f.length;s++)n.assigned[s]=!1;else for(var p=f.length;p<c.length;p++)n.assigned[p]=!0;d(c,function(t,r){n.assigned[t]||e(r)})}}else{var y=(o=f,i=c,a=Object.keys(o),{added:(l=Object.keys(i)).filter(function(e){return-1===a.indexOf(e)}),removed:a.filter(function(e){return-1===l.indexOf(e)})}),h=y.added,v=y.removed;(0<h.length||0<v.length)&&x(n),d(h,function(e,t){n.assigned[t]=!0}),d(v,function(e,t){n.assigned[t]=!1}),d(c,function(t,r){n.assigned[t]||e(r)})}}}}(f),function(){for(var e=_.length-1;0<=e;e--){var t=_[e];!1===t.modified&&(Array.isArray(t.base)?z(t)&&x(t):R(t)&&x(t))}}(),y=b(f,[],a,c);return d(_,function(e,t){t.finished=!0}),n&&n(a,c),y}finally{_=i}}function I(e,t,r){if(arguments.length<1||3<arguments.length)throw new Error("produce expects 1 to 3 arguments, got "+arguments.length);if("function"==typeof e&&"function"!=typeof t){var n=t,o=e;return function(){for(var e=arguments.length,t=Array(1<e?e-1:0),r=1;r<e;r++)t[r-1]=arguments[r];return I(0<arguments.length&&void 0!==arguments[0]?arguments[0]:n,function(e){return o.call.apply(o,[e,e].concat(t))})}}if("function"!=typeof t)throw new Error("if first argument is not a function, the second argument to produce should be a function");if(void 0!==r&&"function"!=typeof r)throw new Error("the third argument of a producer should not be set or a function");if(p(e))return B(f?function(e,t,r){if(s(e)){var n=t.call(e,e);return void 0===n?e:n}var o=g;g=[];var i=r&&[],a=r&&[];try{var c=E(void 0,e),f=t.call(c,c),p=void 0;if(void 0!==f&&f!==c){if(c[u].modified)throw new Error(l);p=b(f),i&&(i.push({op:"replace",path:[],value:p}),a.push({op:"replace",path:[],value:e}))}else p=b(c,[],i,a);return d(g,function(e,t){return t.revoke()}),r&&r(i,a),p}finally{g=o}}(e,t,r):F(e,t,r));var i=t(e);return void 0===i?e:B(i)}function B(e){return e===a?void 0:e}I(function(e,t){for(var n=0;n<t.length;n++){var o=t[n],i=o.path;if(0===i.length&&"replace"===o.op)e=o.value;else{for(var a=e,u=0;u<i.length-1;u++)if(!(a=a[i[u]])||"object"!==(void 0===a?"undefined":r(a)))throw new Error("Cannot apply patch, path doesn't resolve: "+i.join("/"));var l=i[i.length-1];switch(o.op){case"replace":case"add":a[l]=o.value;break;case"remove":if(Array.isArray(a)){if(l!==a.length-1)throw new Error("Remove can only remove the last key of an array, index: "+l+", length: "+a.length);a.length-=1}else delete a[l];break;default:throw new Error("Unsupported patch operation: "+o.op)}}}return e}),t.a=I}).call(this,r(11))},11:function(e,t){var r,n,o=e.exports={};function i(){throw new Error("setTimeout has not been defined")}function a(){throw new Error("clearTimeout has not been defined")}function u(e){if(r===setTimeout)return setTimeout(e,0);if((r===i||!r)&&setTimeout)return r=setTimeout,setTimeout(e,0);try{return r(e,0)}catch(t){try{return r.call(null,e,0)}catch(t){return r.call(this,e,0)}}}!function(){try{r="function"==typeof setTimeout?setTimeout:i}catch(e){r=i}try{n="function"==typeof clearTimeout?clearTimeout:a}catch(e){n=a}}();var l,c=[],f=!1,s=-1;function p(){f&&l&&(f=!1,l.length?c=l.concat(c):s=-1,c.length&&y())}function y(){if(!f){var e=u(p);f=!0;for(var t=c.length;t;){for(l=c,c=[];++s<t;)l&&l[s].run();s=-1,t=c.length}l=null,f=!1,function(e){if(n===clearTimeout)return clearTimeout(e);if((n===a||!n)&&clearTimeout)return n=clearTimeout,clearTimeout(e);try{n(e)}catch(t){try{return n.call(null,e)}catch(t){return n.call(this,e)}}}(e)}}function h(e,t){this.fun=e,this.array=t}function d(){}o.nextTick=function(e){var t=new Array(arguments.length-1);if(1<arguments.length)for(var r=1;r<arguments.length;r++)t[r-1]=arguments[r];c.push(new h(e,t)),1!==c.length||f||u(y)},h.prototype.run=function(){this.fun.apply(null,this.array)},o.title="browser",o.browser=!0,o.env={},o.argv=[],o.version="",o.versions={},o.on=d,o.addListener=d,o.once=d,o.off=d,o.removeListener=d,o.removeAllListeners=d,o.emit=d,o.prependListener=d,o.prependOnceListener=d,o.listeners=function(e){return[]},o.binding=function(e){throw new Error("process.binding is not supported")},o.cwd=function(){return"/"},o.chdir=function(e){throw new Error("process.chdir is not supported")},o.umask=function(){return 0}},12:function(e,t,r){"use strict";r.r(t);var n=r(0);function o(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}var i=wp.components,a=i.SelectControl,u=i.TextControl,l=i.ToggleControl,c=wp.i18n.__;function f(e,t){var r=e.name;if("toggle"==e.type)return React.createElement(l,{label:c(e.label),onChange:function(e){return t.setAttributes(o({},r,e))}});if("text"==e.type||"number"==e.type)return React.createElement(u,{label:c(e.label),value:t.attributes[e.name],onChange:function(e){return t.setAttributes(o({},r,e))}});var n=[];if("select"==e.type||"multi-select"==e.type){var i=e.options;for(var f in i){var s={value:f,label:i[f]};n.push(s)}}return"select"==e.type?React.createElement(a,{label:c(e.label),value:t.attributes[e.name],onChange:function(e){return t.setAttributes(o({},r,e))},options:n}):"multi-select"==e.type?React.createElement(a,{multiple:!0,label:c(e.label),value:t.attributes[e.name],onChange:function(e){return t.setAttributes(o({},r,e))},options:n}):void 0}function s(e){return(s="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function p(e,t){for(var r=0;r<t.length;r++){var n=t[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}function y(e,t){return!t||"object"!==s(t)&&"function"!=typeof t?function(e){if(void 0!==e)return e;throw new ReferenceError("this hasn't been initialised - super() hasn't been called")}(e):t}function h(e){return(h=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function d(e,t){return(d=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}var v=wp.element.Component,b=wp.components,m=b.BaseControl,g=b.DropdownMenu,w=wp.editor,O=(w.InspectorControls,w.ColorPalette),S=(w.FontSizePicker,w.PanelColorSettings,wp.i18n.__),j=function(e){function t(){return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t),y(this,h(t).apply(this,arguments))}var r,n;return function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&d(e,t)}(t,v),r=t,(n=[{key:"capitalizeFirstLetter",value:function(e){return e.charAt(0).toUpperCase()+e.slice(1)}},{key:"getStyleElement",value:function(e,t){var r=this,n=this.capitalizeFirstLetter(e);return"border"==e&&(e="border-color"),"color"==e||"background"==e||"border-color"==e?React.createElement(m,{label:S(n)},React.createElement(O,{colors:[{name:"red",color:"#e44f51"},{name:"white",color:"#fff"},{name:"blue",color:"#1a98ce"}],value:this.props.value,label:e,onChange:function(n){return r.props.onChangeStyle(e,n,t)}})):"text-align"==e?React.createElement(m,{label:S(n)},React.createElement(g,{icon:"align-left",label:"Select a direction",controls:[{title:"Left",icon:"align-left",onClick:function(){return r.props.onChangeStyle(e,"left",t)}},{title:"Center",icon:"align-center",onClick:function(){return r.props.onChangeStyle(e,"center",t)}},{title:"Right",icon:"align-right",onClick:function(){return r.props.onChangeStyle(e,"right",t)}}]})):void 0}},{key:"render",value:function(){var e=this.getStyleElement(this.props.field,this.props.elementKey);return React.createElement("div",null,e)}}])&&p(r.prototype,n),t}();function E(e){return(E="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function P(e,t){for(var r=0;r<t.length;r++){var n=t[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}function _(e){return(_=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function C(e,t){return(C=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}var x=wp.element,k=x.Component,A=x.Fragment,T=(wp.i18n.__,wp.components),R=T.ServerSideRender,z=T.PanelBody,F=wp.editor.InspectorControls,I=function(e){function t(){return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t),function(e,t){return!t||"object"!==E(t)&&"function"!=typeof t?function(e){if(void 0!==e)return e;throw new ReferenceError("this hasn't been initialised - super() hasn't been called")}(e):t}(this,_(t).apply(this,arguments))}var r,o;return function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&C(e,t)}(t,k),r=t,(o=[{key:"onChange",value:function(e,t){var r,n,o;this.props.setAttributes((o=t,(n=e)in(r={})?Object.defineProperty(r,n,{value:o,enumerable:!0,configurable:!0,writable:!0}):r[n]=o,r))}},{key:"getQuerySettings",value:function(){var e=[];for(var t in BlockFields)if(BlockFields.hasOwnProperty(t)){var r=f(BlockFields[t],this.props);e.push(r)}return e}},{key:"onChangeStyle",value:function(e,t,r){var o={};this.props.attributes.style&&(o=this.props.attributes.style);var i=Object(n.a)(o,function(n){n[r]||(n[r]={}),n[r][e]=t});this.props.setAttributes({style:i})}},{key:"getStyleSettings",value:function(){var e=[],t=BlockFields.style;for(var r in t){var n=[],o=t[r].styleProps;for(var i in o)if(o.hasOwnProperty(i)){var a=o[i],u="";this.props.attributes.style&&this.props.attributes.style[r]&&this.props.attributes.style[r][i]&&(u=this.props.attributes.style[r][i]);var l=React.createElement(j,{value:u,field:a,elementKey:r,onChangeStyle:this.onChangeStyle.bind(this)});n.push(l)}var c=React.createElement(z,{initialOpen:!1,title:t[r].label+" Settings"},n);e.push(c)}return e}},{key:"render",value:function(){var e=React.createElement(F,null,React.createElement(z,{title:"Query Settings"},this.getQuerySettings()),this.getStyleSettings());return React.createElement(A,null,e,React.createElement(R,{block:"helpie-faq/helpie-faq",attributes:this.props.attributes}))}}])&&P(r.prototype,o),t}(),B=wp.i18n.__;(0,wp.blocks.registerBlockType)("helpie-faq/helpie-faq",{title:B("Helpie FAQ Block"),icon:"list-view",category:"common",edit:I,save:function(){return null}})}});
//# sourceMappingURL=block.bundle.js.map
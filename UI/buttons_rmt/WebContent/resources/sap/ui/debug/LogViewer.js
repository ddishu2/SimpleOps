/*!
 * UI development toolkit for HTML5 (OpenUI5)
 * (c) Copyright 2009-2015 SAP SE or an SAP affiliate company.
 * Licensed under the Apache License, Version 2.0 - see LICENSE.txt.
 */
sap.ui.define('sap/ui/debug/LogViewer',['jquery.sap.global'],function(q){"use strict";var L=function(w,r){this.oWindow=w;this.oDomNode=w.document.getElementById(r);if(!this.oDomNode){var d=this.oWindow.document.createElement("DIV");d.setAttribute("id",r);d.style.overflow="auto";d.style.tabIndex="-1";d.style.position="absolute";d.style.bottom="0px";d.style.left="0px";d.style.right="202px";d.style.height="200px";d.style.border="1px solid gray";d.style.fontFamily="Arial monospaced for SAP,monospace";d.style.fontSize="11px";d.style.zIndex="999999";this.oWindow.document.body.appendChild(d);this.oDomNode=d;}this.iLogLevel=3;this.sLogEntryClassPrefix=undefined;this.clear();this.setFilter(L.NO_FILTER);};L.NO_FILTER=function(l){return true;};L.prototype.clear=function(){this.oDomNode.innerHTML="";};L.xmlEscape=function(t){t=t.replace(/\&/g,"&amp;");t=t.replace(/\</g,"&lt;");t=t.replace(/\"/g,"&quot;");return t;};L.prototype.addEntry=function(l){var d=this.oWindow.document.createElement("div");if(this.sLogEntryClassPrefix){d.className=this.sLogEntryClassPrefix+l.level;}else{d.style.overflow="hidden";d.style.textOverflow="ellipsis";d.style.height="1.3em";d.style.width="100%";d.style.whiteSpace="noWrap";}var t=L.xmlEscape(l.time+"  "+l.message),T=this.oWindow.document.createTextNode(t);d.appendChild(T);d.title=l.message;d.style.display=this.oFilter(t)?"":"none";this.oDomNode.appendChild(d);return d;};L.prototype.fillFromLogger=function(f){this.clear();this.iFirstEntry=f;if(!this.oLogger){return;}var a=this.oLogger.getLog();for(var i=this.iFirstEntry,l=a.length;i<l;i++){if(a[i].level<=this.iLogLevel){this.addEntry(a[i]);}}this.scrollToBottom();};L.prototype.scrollToBottom=function(){this.oDomNode.scrollTop=this.oDomNode.scrollHeight;};L.prototype.truncate=function(){this.clear();this.fillFromLogger(this.oLogger.getLog().length);};L.prototype.setFilter=function(f){this.oFilter=f=f||L.NO_FILTER;var c=this.oDomNode.childNodes;for(var i=0,l=c.length;i<l;i++){var t=c[i].innerText;if(!t){t=c[i].innerHTML;}c[i].style.display=f(t)?"":"none";}this.scrollToBottom();};L.prototype.setLogLevel=function(l){this.iLogLevel=l;if(this.oLogger){this.oLogger.setLevel(l);}this.fillFromLogger(this.iFirstEntry);};L.prototype.lock=function(){this.bLocked=true;};L.prototype.unlock=function(){this.bLocked=false;this.fillFromLogger(0);};L.prototype.onAttachToLog=function(l){this.oLogger=l;this.oLogger.setLevel(this.iLogLevel);if(!this.bLocked){this.fillFromLogger(0);}};L.prototype.onDetachFromLog=function(l){this.oLogger=undefined;this.fillFromLogger(0);};L.prototype.onLogEntry=function(l){if(!this.bLocked){var d=this.addEntry(l);if(d&&d.style.display!=='none'){this.scrollToBottom();}}};return L;},true);

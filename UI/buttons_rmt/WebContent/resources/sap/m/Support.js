/*!
 * UI development toolkit for HTML5 (OpenUI5)
 * (c) Copyright 2009-2015 SAP SE or an SAP affiliate company.
 * Licensed under the Apache License, Version 2.0 - see LICENSE.txt.
 */
sap.ui.define(['jquery.sap.global'],function(q){"use strict";var S=(function($,d){var a,s,b,l;var t=0;var m=3000;var h=2;var c=3;var r=1,D={},e={btnStart:"startE2ETrace",selLevel:"logLevelE2ETrace",taContent:"outputE2ETrace",infoText:"Ent-to-End trace is running in the background."+" Navigate to the URL that you would like to trace."+" The result of the trace will be shown in dialog after the trace is terminated.",infoDuration:5000},f={dvLoadedLibs:"LoadedLibs",dvLoadedModules:"LoadedModules"};function g(i,v,z,A,B){i.push("<tr class='sapUiSelectable'><td class='sapUiSupportTechInfoBorder sapUiSelectable'><label class='sapUiSupportLabel sapUiSelectable'>",q.sap.escapeHTML(A),"</label><br>");var C=B;if($.isFunction(B)){C=B(i)||"";}i.push($.sap.escapeHTML(C));i.push("</td></tr>");}function j(z,A,B,C,E){g(z,A,B,C,function(z){z.push("<table class='sapMSupportTable' border='0' cellspacing='5' cellpadding='5' width='100%'><tbody>");$.each(E,function(i,v){var F="";if(v){if(typeof(v)=="string"||typeof(v)=="boolean"||($.isArray(v)&&v.length==1)){F=v;}else if(($.isArray(v)||$.isPlainObject(v))&&window.JSON){F=window.JSON.stringify(v);}}g(z,false,false,i,""+F);});z.push("</tbody></table>");});}function k(){var z,C=sap.ui.getCore().getConfiguration();var L={};q.each(sap.ui.getCore().getLoadedLibraries(),function(N,i){L[N]=i.version;});D={version:sap.ui.version,build:sap.ui.buildinfo.buildtime,change:sap.ui.buildinfo.lastchange,useragent:navigator.userAgent,docmode:d.documentMode||"",debug:$.sap.debug(),bootconfig:window["sap-ui-config"]||{},modules:$.sap.getAllDeclaredModules(),loadedlibs:L,uriparams:$.sap.getUriParameters().mParams,appurl:window.location.href,config:{theme:C.getTheme(),language:C.getLanguage(),formatLocale:C.getFormatLocale(),accessibility:""+C.getAccessibility(),animation:""+C.getAnimation(),rtl:""+C.getRTL(),debug:""+C.getDebug(),inspect:""+C.getInspect(),originInfo:""+C.getOriginInfo(),noDuplicateIds:""+C.getNoDuplicateIds()}};z=["<table class='sapUiSelectable' border='0' cellspacing='5' cellpadding='5' width='100%'><tbody class='sapUiSelectable'>"];g(z,true,true,"SAPUI5 Version",function(i){i.push(D.version," (built at ",D.build,", last change ",D.change,")");});g(z,true,true,"User Agent",function(i){i.push(D.useragent,(D.docmode?", Document Mode '"+D.docmode+"'":""));});g(z,true,true,"Debug Sources",function(i){i.push((D.debug?"ON":"OFF"));});g(z,true,true,"Application",D.appurl);j(z,true,true,"Configuration (bootstrap)",D.bootconfig);j(z,true,true,"Configuration (computed)",D.config);j(z,true,true,"URI Parameters",D.uriparams);g(z,true,true,"End-to-End Trace",function(i){i.push("<label class='sapUiSupportLabel'>Trace Level:</label>","<select id='"+n(e.selLevel)+"' class='sapUiSupportTxtFld' >","<option value='low'>LOW</option>","<option value='medium' selected>MEDIUM</option>","<option value='high'>HIGH</option>","</select>");i.push("<button id='"+n(e.btnStart)+"' class='sapUiSupportBtn'>Start</button>");i.push("<div class='sapUiSupportDiv'>");i.push("<label class='sapUiSupportLabel'>XML Output:</label>");i.push("<textarea id='"+n(e.taContent)+"' class='sapUiSupportTxtArea sapUiSelectable' readonly ></textarea>");i.push("</div>");});g(z,true,true,"Loaded Libraries",function(A){A.push("<ul class='sapUiSelectable'>");$.each(D.loadedlibs,function(i,v){if(v&&(typeof(v)==="string"||typeof(v)==="boolean")){A.push("<li class='sapUiSelectable'>",i+" "+v,"</li>");}});A.push("</ul>");});g(z,true,true,"Loaded Modules",function(i){i.push("<div class='sapUiSupportDiv sapUiSelectable' id='"+n(f.dvLoadedModules)+"' />");});z.push("</tbody></table>");return new sap.ui.core.HTML({content:z.join("").replace(/\{/g,"&#123;").replace(/\}/g,"&#125;")});}function n(i){return a.getId()+"-"+i;}function o(v,z){var A="Modules";var B=0,C=[];B=z.length;$.each(z.sort(),function(i,F){C.push(new sap.m.Label({text:" - "+F}).addStyleClass("sapUiSupportPnlLbl"));});var E=new sap.m.Panel({expandable:true,expanded:false,headerToolbar:new sap.m.Toolbar({design:sap.m.ToolbarDesign.Transparent,content:[new sap.m.Label({text:A+" ("+B+")",design:sap.m.LabelDesign.Bold})]}),content:C});E.placeAt(n(v),"only");}function p(){if(a.traceXml){a.$(e.taContent).text(a.traceXml);}if(a.e2eLogLevel){a.$(e.selLevel).val(a.e2eLogLevel);}o(f.dvLoadedModules,D.modules);a.$(e.btnStart).one("tap",function(){a.e2eLogLevel=a.$(e.selLevel).val();a.$(e.btnStart).addClass("sapUiSupportRunningTrace").text("Running...");a.traceXml="";a.$(e.taContent).text("");sap.ui.core.support.trace.E2eTraceLib.start(a.e2eLogLevel,function(i){a.traceXml=i;});sap.m.MessageToast.show(e.infoText,{duration:e.infoDuration});a.close();});}function u(){if(a){return a;}$.sap.require("sap.m.Dialog");$.sap.require("sap.m.Button");$.sap.require("sap.ui.core.HTML");$.sap.require("sap.m.MessageToast");$.sap.require("sap.ui.core.support.trace.E2eTraceLib");a=new sap.m.Dialog({title:"Technical Information",horizontalScrolling:true,verticalScrolling:true,stretch:q.device.is.phone,leftButton:new sap.m.Button({text:"Close",press:function(){a.close();}}),afterOpen:function(){S.off();},afterClose:function(){S.on();}}).addStyleClass("sapMSupport");return a;}function w(E){if(E.touches){var i=E.touches.length;if(i>c){d.removeEventListener('touchend',x);return;}switch(i){case h:s=Date.now();d.addEventListener('touchend',x);break;case c:if(s){t=Date.now()-s;l=E.touches[i-1].identifier;}break;}}}function x(E){d.removeEventListener('touchend',x);if(t>m&&E.touches.length===h&&E.changedTouches.length===r&&E.changedTouches[0].identifier===l){t=0;s=0;y();}}function y(){var i=u();i.removeAllAggregation("content");i.addAggregation("content",k());a.open();p();}return({on:function(){if(!b&&"ontouchstart"in d){b=true;d.addEventListener("touchstart",w);}return this;},off:function(){if(b){b=false;d.removeEventListener("touchstart",w);}return this;},open:function(){y();},isEventRegistered:function(){return b;}}).on();}(q,document));return S;},true);

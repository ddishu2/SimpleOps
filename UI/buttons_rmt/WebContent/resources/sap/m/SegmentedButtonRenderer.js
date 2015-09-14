/*!
 * UI development toolkit for HTML5 (OpenUI5)
 * (c) Copyright 2009-2015 SAP SE or an SAP affiliate company.
 * Licensed under the Apache License, Version 2.0 - see LICENSE.txt.
 */
sap.ui.define(['jquery.sap.global'],function(q){"use strict";var S={};S.render=function(r,c){var b=c.getButtons(),s=c.getSelectedButton(),B,t,a,d,i=0;r.write("<ul");r.addClass("sapMSegB");r.addClass("sapMSegBHide");r.writeClasses();if(c.getWidth()&&c.getWidth()!==''){r.addStyle('width',c.getWidth());}r.writeStyles();r.writeControlData(c);t=c.getTooltip_AsString();if(t){r.writeAttributeEscaped("title",t);}r.writeAccessibilityState(c,{role:"radiogroup"});r.write(">");for(;i<b.length;i++){B=b[i];var e=B.getText(),o=B.getIcon(),I="";if(o){var f=B._getImage((B.getId()+"-img"),o);if(f instanceof sap.m.Image){c._overwriteImageOnload(f);}else{I=c._getIconAriaLabel(f);}}r.write("<li");r.writeControlData(B);r.addClass("sapMSegBBtn");if(B.aCustomStyleClasses!==undefined&&B.aCustomStyleClasses instanceof Array){for(var j=0;j<B.aCustomStyleClasses.length;j++){r.addClass(B.aCustomStyleClasses[j]);}}if(B.getEnabled()){r.addClass("sapMSegBBtnFocusable");}else{r.addClass("sapMSegBBtnDis");}if(s===B.getId()){r.addClass("sapMSegBBtnSel");}if(o&&e!==''){r.addClass("sapMSegBBtnMixed");}r.writeClasses();a=B.getWidth();if(a){r.addStyle('width',a);r.writeStyles();}t=B.getTooltip_AsString();if(t){r.writeAttributeEscaped("title",t);}r.writeAttribute("tabindex",B.getEnabled()?"0":"-1");d=B.getTextDirection();if(d!==sap.ui.core.TextDirection.Inherit){r.writeAttribute("dir",d.toLowerCase());}r.writeAccessibilityState(B,{role:"radio",checked:s===B.getId()});if(f&&I!==""){if(e!==""){I+=" "+e;}r.writeAttributeEscaped("aria-label",I);}r.write('>');if(o&&f){r.renderControl(f);}if(e!==''){r.writeEscaped(e,false);}r.write("</li>");}r.write("</ul>");};return S;},true);

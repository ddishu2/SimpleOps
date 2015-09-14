/*!
 * UI development toolkit for HTML5 (OpenUI5)
 * (c) Copyright 2009-2015 SAP SE or an SAP affiliate company.
 * Licensed under the Apache License, Version 2.0 - see LICENSE.txt.
 */
sap.ui.define(['jquery.sap.global'],function(q){"use strict";var F={};F.render=function(r,c){var a=r;var f=c;a.write('<DIV');a.writeControlData(f);a.addClass('sapUiFeeder');switch(f.getType()){case sap.ui.ux3.FeederType.Medium:a.addClass('sapUiFeederMedium');break;case sap.ui.ux3.FeederType.Comment:a.addClass('sapUiFeederComment');break;default:a.addClass('sapUiFeederLarge');break;}a.writeClasses();a.write('>');a.write('<img id='+f.getId()+'-thumb');var t=f.getThumbnailSrc();if(!t){t=q.sap.getModulePath("sap.ui.ux3",'/')+"themes/"+sap.ui.getCore().getConfiguration().getTheme()+sap.ui.core.theming.Parameters.get('sapUiFeedPersonPlaceholder');}a.writeAttributeEscaped('src',t);a.writeClasses();a.write('>');a.write('<DIV id='+f.getId()+'-input contenteditable="true" class="sapUiFeederInput" >');if(f.getText()==''){a.write(this.getEmptyTextInfo(f));}else{a.writeEscaped(f.getText(),true);}a.write('</DIV>');f.initSendButton();a.renderControl(f.oSendButton);a.write('</DIV>');};F.getEmptyTextInfo=function(f){return"<span class='sapUiFeederEmptyText'>"+q.sap.encodeHTML(f.getPlaceholderText()||f.rb.getText("FEED_EMPTY_FEEDER"))+"</span>";};return F;},true);

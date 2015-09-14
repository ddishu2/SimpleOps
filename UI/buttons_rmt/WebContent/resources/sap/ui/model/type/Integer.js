/*!
 * UI development toolkit for HTML5 (OpenUI5)
 * (c) Copyright 2009-2015 SAP SE or an SAP affiliate company.
 * Licensed under the Apache License, Version 2.0 - see LICENSE.txt.
 */
sap.ui.define(['jquery.sap.global','sap/ui/core/format/NumberFormat','sap/ui/model/SimpleType'],function(q,N,S){"use strict";var I=S.extend("sap.ui.model.type.Integer",{constructor:function(){S.apply(this,arguments);this.sName="Integer";}});I.prototype.formatValue=function(v,i){var V=v;if(v==undefined||v==null){return null;}if(this.oInputFormat){V=this.oInputFormat.parse(v);if(V==null){throw new sap.ui.model.FormatException("Cannot format float: "+v+" has the wrong format");}}switch(this.getPrimitiveType(i)){case"string":return this.oOutputFormat.format(V);case"int":case"float":case"any":return V;default:throw new sap.ui.model.FormatException("Don't know how to format Integer to "+i);}};I.prototype.parseValue=function(v,i){var r,b;switch(this.getPrimitiveType(i)){case"string":r=this.oOutputFormat.parse(String(v));if(isNaN(r)){b=sap.ui.getCore().getLibraryResourceBundle();throw new sap.ui.model.ParseException(b.getText("Integer.Invalid"));}break;case"float":r=Math.floor(v);if(r!=v){b=sap.ui.getCore().getLibraryResourceBundle();throw new sap.ui.model.ParseException(b.getText("Integer.Invalid"));}break;case"int":r=v;break;default:throw new sap.ui.model.ParseException("Don't know how to parse Integer from "+i);}if(this.oInputFormat){r=this.oInputFormat.format(r);}return r;};I.prototype.validateValue=function(v){if(this.oConstraints){var b=sap.ui.getCore().getLibraryResourceBundle(),V=[],m=[];q.each(this.oConstraints,function(n,c){switch(n){case"minimum":if(v<c){V.push("minimum");m.push(b.getText("Integer.Minimum",[c]));}break;case"maximum":if(v>c){V.push("maximum");m.push(b.getText("Integer.Maximum",[c]));}}});if(V.length>0){throw new sap.ui.model.ValidateException(m.join(" "),V);}}};I.prototype.setFormatOptions=function(f){this.oFormatOptions=f;this._createFormats();};I.prototype._handleLocalizationChange=function(){this._createFormats();};I.prototype._createFormats=function(){var s=this.oFormatOptions.source;this.oOutputFormat=N.getIntegerInstance(this.oFormatOptions);if(s){if(q.isEmptyObject(s)){s={groupingEnabled:false,groupingSeparator:",",decimalSeparator:"."};}this.oInputFormat=N.getIntegerInstance(s);}};return I;},true);

/*!
 * UI development toolkit for HTML5 (OpenUI5)
 * (c) Copyright 2009-2015 SAP SE or an SAP affiliate company.
 * Licensed under the Apache License, Version 2.0 - see LICENSE.txt.
 */
sap.ui.define(['jquery.sap.global','./library','sap/ui/core/Control','sap/ui/core/EnabledPropagator','sap/ui/core/IconPool'],function(q,l,C,E,I){"use strict";var B=C.extend("sap.ui.commons.Button",{metadata:{interfaces:["sap.ui.commons.ToolbarItem"],library:"sap.ui.commons",properties:{text:{type:"string",group:"Appearance",defaultValue:''},enabled:{type:"boolean",group:"Behavior",defaultValue:true},width:{type:"sap.ui.core.CSSSize",group:"Dimension",defaultValue:null},helpId:{type:"string",group:"Behavior",defaultValue:''},icon:{type:"sap.ui.core.URI",group:"Appearance",defaultValue:''},iconHovered:{type:"sap.ui.core.URI",group:"Appearance",defaultValue:''},iconSelected:{type:"sap.ui.core.URI",group:"Appearance",defaultValue:''},iconFirst:{type:"boolean",group:"Appearance",defaultValue:true},height:{type:"sap.ui.core.CSSSize",group:"Dimension",defaultValue:null},styled:{type:"boolean",group:"Appearance",defaultValue:true},lite:{type:"boolean",group:"Appearance",defaultValue:false},style:{type:"sap.ui.commons.ButtonStyle",group:"Appearance",defaultValue:sap.ui.commons.ButtonStyle.Default}},associations:{ariaDescribedBy:{type:"sap.ui.core.Control",multiple:true,singularName:"ariaDescribedBy"},ariaLabelledBy:{type:"sap.ui.core.Control",multiple:true,singularName:"ariaLabelledBy"}},events:{press:{}}}});E.call(B.prototype);B.prototype.onclick=function(e){if(this.getEnabled()){this.firePress({});}e.preventDefault();e.stopPropagation();};B.prototype.onsapenter=function(e){e.stopPropagation();};B.prototype.onmousedown=function(e){this.handleMouseDown(e,true);};B.prototype.handleMouseDown=function(e,f){if(this.getEnabled()&&this.getRenderer().onactive){this.getRenderer().onactive(this);}if(f&&(!!sap.ui.Device.browser.webkit||(!!sap.ui.Device.browser.firefox&&navigator.platform.indexOf("Mac")===0))){if(sap.ui.Device.browser.mobile&&!!sap.ui.Device.browser.webkit){this.focus();}q.sap.delayedCall(0,this,function(){this.focus();});}};B.prototype.onmouseup=function(e){if(this.getEnabled()&&this.getRenderer().ondeactive){this.getRenderer().ondeactive(this);}};B.prototype.onmouseout=function(e){if(this.getEnabled()&&this.getRenderer().onmouseout){this.getRenderer().onmouseout(this);}};B.prototype.onmouseover=function(e){if(this.getEnabled()&&this.getRenderer().onmouseover){this.getRenderer().onmouseover(this);}};B.prototype.onfocusout=function(e){if(this.getEnabled()&&this.getRenderer().onblur){this.getRenderer().onblur(this);}};B.prototype.onfocusin=function(e){if(this.getEnabled()&&this.getRenderer().onfocus){this.getRenderer().onfocus(this);}};B.prototype.setIcon=function(i){this._setIcon(i,"icon");return this;};B.prototype.setIconHovered=function(i){this._setIcon(i,"iconHovered");return this;};B.prototype.setIconSelected=function(i){this._setIcon(i,"iconSelected");return this;};B.prototype._setIcon=function(i,p){var s=this.getProperty(p);if(s==i){return;}var u=false;if(I.isIconURI(s)){u=true;}var U=false;if(I.isIconURI(i)){U=true;}var S=true;if((!s&&i)||(s&&!i)||(u!=U)){S=false;}this.setProperty(p,i,S);if(S==true&&this.getDomRef()&&this.getRenderer().changeIcon){this.getRenderer().changeIcon(this);}};return B;},true);

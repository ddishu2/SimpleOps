/*!
 * UI development toolkit for HTML5 (OpenUI5)
 * (c) Copyright 2009-2015 SAP SE or an SAP affiliate company.
 * Licensed under the Apache License, Version 2.0 - see LICENSE.txt.
 */
sap.ui.define(['jquery.sap.global','./MessageBox','./Dialog','./library','sap/ui/core/Control','sap/ui/unified/FileUploaderParameter',"sap/ui/unified/FileUploader","./UploadCollectionItem","./BusyIndicator","./CustomListItem","./Link","./FlexItemData","./HBox","sap/ui/layout/HorizontalLayout","./CustomListItemRenderer","./LinkRenderer","./TextRenderer","./DialogRenderer","./HBoxRenderer"],function(q,M,D,L,C,F,a,U,B,b,c,d,H,e){"use strict";var f=C.extend("sap.m.UploadCollection",{metadata:{library:"sap.m",properties:{fileType:{type:"string[]",group:"Data",defaultValue:null},maximumFilenameLength:{type:"int",group:"Data",defaultValue:null},maximumFileSize:{type:"float",group:"Data",defaultValue:null},mimeType:{type:"string[]",group:"Data",defaultValue:null},multiple:{type:"boolean",group:"Behavior",defaultValue:false},noDataText:{type:"string",group:"Behavior",defaultValue:null},sameFilenameAllowed:{type:"boolean",group:"Behavior",defaultValue:false},showSeparators:{type:"sap.m.ListSeparators",group:"Appearance",defaultValue:sap.m.ListSeparators.None},uploadEnabled:{type:"boolean",group:"Behavior",defaultValue:true},uploadUrl:{type:"string",group:"Data",defaultValue:"../../../upload"}},defaultAggregation:"items",aggregations:{items:{type:"sap.m.UploadCollectionItem",multiple:true,singularName:"item"},headerParameters:{type:"sap.m.UploadCollectionParameter",multiple:true,singularName:"headerParameter"},parameters:{type:"sap.m.UploadCollectionParameter",multiple:true,singularName:"parameter"}},events:{change:{parameters:{documentId:{type:"string"},files:{type:"object[]"}}},fileDeleted:{parameters:{documentId:{type:"string"},item:{type:"sap.m.UploadCollectionItem"}}},filenameLengthExceed:{parameters:{documentId:{type:"string"},files:{type:"object[]"}}},fileRenamed:{parameters:{documentId:{type:"string"},fileName:{type:"string"},item:{type:"sap.m.UploadCollectionItem"}}},fileSizeExceed:{parameters:{documentId:{type:"string"},fileSize:{type:"string"},files:{type:"object[]"}}},typeMissmatch:{parameters:{documentId:{type:"string"},fileType:{type:"string"},mimeType:{type:"string"},files:{type:"object[]"}}},uploadComplete:{parameters:{readyStateXHR:{type:"string"},response:{type:"string"},status:{type:"string"},files:{type:"object[]"}}},uploadTerminated:{}}}});f._uploadingStatus="uploading";f._displayStatus="display";f._toBeDeletedStatus="toBeDeleted";f.prototype._requestIdName="requestId";f.prototype._requestIdValue=0;f.prototype.init=function(){sap.m.UploadCollection.prototype._oRb=sap.ui.getCore().getLibraryResourceBundle("sap.m");this._oList=new sap.m.List(this.getId()+"-list",{});this._oList.addStyleClass("sapMUCList");this._cAddItems=0;this.aItems=[];};f.prototype.setFileType=function(g){if(!g){return this;}var h=g.length;for(var i=0;i<h;i++){g[i]=g[i].toLowerCase();}this.setProperty("fileType",g);if(this._getFileUploader().getFileType()!==g){this._getFileUploader().setFileType(g);}return this;};f.prototype.setMaximumFilenameLength=function(m){this.setProperty("maximumFilenameLength",m);if(this._getFileUploader().getMaximumFilenameLength()!==m){this._getFileUploader().setMaximumFilenameLength(m);}return this;};f.prototype.setMaximumFileSize=function(m){this.setProperty("maximumFileSize",m);if(this._getFileUploader().getMaximumFileSize()!==m){this._getFileUploader().setMaximumFileSize(m);}return this;};f.prototype.setMimeType=function(m){this.setProperty("mimeType",m);if(this._getFileUploader().getMimeType()!==m){this._getFileUploader().setMimeType(m);}return this;};f.prototype.setMultiple=function(m){this.setProperty("multiple",m);if(this._getFileUploader().getMultiple()!==m){this._getFileUploader().setMultiple(m);}return this;};f.prototype.setNoDataText=function(n){this.setProperty("noDataText",n);if(this._oList.getNoDataText()!==n){this._oList.setNoDataText(n);}return this;};f.prototype.setShowSeparators=function(s){this.setProperty("showSeparators",s);if(this._oList.getShowSeparators()!==s){this._oList.setShowSeparators(s);}return this;};f.prototype.setUploadEnabled=function(u){this.setProperty("uploadEnabled",u);if(this._getFileUploader().getEnabled()!==u){this._getFileUploader().setEnabled(u);}return this;};f.prototype.setUploadUrl=function(u){this.setProperty("uploadUrl",u);if(this._getFileUploader().getUploadUrl()!==u){this._getFileUploader().setUploadUrl(u);}return this;};f.prototype.onBeforeRendering=function(){var n=n||{};var N=N||this.getNoDataText();var i,I,g;if(this.aItems.length>0){g=this.aItems.length;var u=[];for(i=0;i<g;i++){if(this.aItems[i]&&this.aItems[i]._status===f._uploadingStatus&&this.aItems[i]._percentUploaded!==100){u.push(this.aItems[i]);}else if(this.aItems[i]&&this.aItems[i]._status!==f._uploadingStatus&&this.aItems[i]._percentUploaded===100&&this.getItems().length===0){u.push(this.aItems[i]);}else if(this.aItems[i]&&this.aItems[i]._status===f._toBeDeletedStatus&&this.getItems().length===0){I=true;this.aItems.splice(i,1);}}if(u.length===0&&!I){this.aItems=this.getItems();}}else{this.aItems=this.getItems();}n=this._getNumberOfAttachmentsLabel(this.aItems.length);if(!this.oHeaderToolbar){this.oHeaderToolbar=new sap.m.Toolbar(this.getId()+"-toolbar",{content:[n,new sap.m.ToolbarSpacer(),this._getFileUploader()]});}else{var t=this.oHeaderToolbar.getContent();t[0]=n;this.oHeaderToolbar.content=t;}this.oHeaderToolbar.addStyleClass("sapMUCListHeader");if((sap.ui.Device.browser.msie&&sap.ui.Device.browser.version<=9)&&this.aItems.length>0&&this.aItems[0]._status===f._uploadingStatus){this._oFileUploader.setEnabled(false);}else{if(this.sErrorState!=="Error"){if(this.getUploadEnabled()!=this._oFileUploader.getEnabled()){this._oFileUploader.setEnabled(this.getUploadEnabled());}}else{this._oFileUploader.setEnabled(false);}}this._clearList();this._fillList(this.aItems);this._oList.setAggregation("headerToolbar",this.oHeaderToolbar,true);};f.prototype.onAfterRendering=function(){var t=this;for(var i=0;i<this._oList.aDelegates.length;i++){if(this._oList.aDelegates[i]._sId&&this._oList.aDelegates[i]._sId==="UploadCollection"){this._oList.aDelegates.splice(i,1);}}if(this.aItems||(this.aItems===this.getItems())){if(this.editModeItem){var $=q.sap.byId(this.editModeItem+"-ta_editFileName-inner");if($){var I=this.editModeItem;if(!sap.ui.Device.os.ios){$.focus(function(){$.selectText(0,$.val().length);});}$.focus();this._oList.addDelegate({onclick:function(E){sap.m.UploadCollection.prototype._handleClick(E,t,I);}});this._oList.aDelegates[this._oList.aDelegates.length-1]._sId="UploadCollection";}}else{if(this.sFocusId){sap.m.UploadCollection.prototype._setFocus2LineItem(this.sFocusId);this.sFocusId=null;}else if(this.sDeletedItemId){sap.m.UploadCollection.prototype._setFocusAfterDeletion(this.sDeletedItemId,t);this.sDeletedItemId=null;}}}};f.prototype.exit=function(){if(this._oList){this._oList.destroy();this._oList=null;}};f.prototype._mapItemToListItem=function(i){if(!i){return null;}var I=i.getId(),p=i._percentUploaded,s=i._status,g=i.getFileName(),t=this,E=true;var o,O,h,j,k,l,u,P,T,m,n,r,v,w,x,y,z,V;if(s===f._uploadingStatus){o=new sap.m.BusyIndicator(I+"-ia_indicator",{visible:true}).setSize("2.5rem").addStyleClass("sapMUCloadingIcon");}if(s==="Edit"){O=new sap.m.Button({id:I+"-okButton",text:this._oRb.getText("UPLOADCOLLECTION_OKBUTTON_TEXT"),type:sap.m.ButtonType.Transparent}).addStyleClass("sapMUCOkBtn");h=new sap.m.Button({id:I+"-cancelButton",text:this._oRb.getText("UPLOADCOLLECTION_CANCELBUTTON_TEXT"),type:sap.m.ButtonType.Transparent}).addStyleClass("sapMUCCancelBtn");}if(s===f._displayStatus){E=i.getEnableEdit();if(this.sErrorState==="Error"){E=false;}j=new sap.m.Button({id:I+"-editButton",icon:"sap-icon://edit",type:sap.m.ButtonType.Transparent,enabled:E,visible:i.getVisibleEdit(),press:[i,this._handleEdit,this]}).addStyleClass("sapMUCEditBtn");}if(s===f._displayStatus){z="deleteButton";k=this._createDeleteButton(I,z,i,this.sErrorState);k.attachPress(function(R){sap.m.UploadCollection.prototype._handleDelete(R,t);});}if(s===f._uploadingStatus&&!(sap.ui.Device.browser.msie&&sap.ui.Device.browser.version<=9)){z="terminateButton";k=this._createDeleteButton(I,z,i,this.sErrorState);k.attachPress(function(R){sap.m.UploadCollection.prototype._handleTerminate(R,t);});}v=new sap.ui.layout.HorizontalLayout(I+"-ba_innerHL",{content:[O,h,j,k]}).addStyleClass("sapMUCBtnHL");if(sap.ui.Device.browser.msie&&sap.ui.Device.browser.version<=9){v.addStyleClass("sapMUCBtnNoFlex");}if(s===f._displayStatus||s===f._uploadingStatus){E=true;if(this.sErrorState==="Error"||!q.trim(i.getUrl())){E=false;}l=new sap.m.Link(I+"-ta_filenameHL",{enabled:E,target:"_blank",press:function(R){sap.m.UploadCollection.prototype._triggerLink(R,t);}}).addStyleClass("sapMUCFileName");l.setModel(i.getModel());l.setText(g);}if(s===f._displayStatus){u=new sap.m.Label(I+"-ta_date");u.setModel(i.getModel());u.setText(i.getUploadedDate()+" "+i.getContributor());}if(s===f._uploadingStatus&&!(sap.ui.Device.browser.msie&&sap.ui.Device.browser.version<=9)){P=new sap.m.Label(I+"-ta_progress",{text:this._oRb.getText("UPLOADCOLLECTION_UPLOADING",[p])}).addStyleClass("sapMUCProgress");}if(s===f._displayStatus||s===f._uploadingStatus){T=new sap.ui.layout.HorizontalLayout(I+"-ta_descriptionHL",{content:[u,P]}).addStyleClass("sapMUCDescriptionHL");}if(s==="Edit"){var A=f.prototype._splitFilename(g);var G=t.getMaximumFilenameLength();var J="None";var S=false;var K=A.name;if(i.errorState==="Error"){S=true;J="Error";K=i.changedFileName;if(K.length===0){V=this._oRb.getText("UPLOADCOLLECTION_TYPE_FILENAME");}else{V=this._oRb.getText("UPLOADCOLLECTION_EXISTS");}}m=new sap.m.Input(I+"-ta_editFileName",{type:sap.m.InputType.Text,fieldWidth:"76%",valueState:J,valueStateText:V,showValueStateMessage:S,description:A.extension}).addStyleClass("sapMUCEditBox");m.setModel(i.getModel());m.setValue(K);if((G-A.extension.length)>0){m.setProperty("maxLength",G-A.extension.length,true);}m.setLayoutData(new sap.m.FlexItemData({growFactor:1}));w=new sap.m.HBox(I+"-ta_extensionHL",{items:[m]}).addStyleClass("sapMUCEditHL");}x=new sap.ui.layout.VerticalLayout(I+"-ta_textVL",{content:[l,w,T]}).addStyleClass("sapMUCText");if(s===f._displayStatus||s==="Edit"){var N=false;if(this.sErrorState==="Error"||!q.trim(i.getProperty("url"))){N=true;}r=i.getThumbnailUrl();if(r){n=new sap.m.Image(I+"-ia_imageHL",{src:sap.m.UploadCollection.prototype._getThumbnail(r,g),decorative:N}).addStyleClass("sapMUCItemImage");}else{n=new sap.ui.core.Icon(I+"-ia_iconHL",{src:sap.m.UploadCollection.prototype._getThumbnail(undefined,g),decorative:N}).setSize('2.5rem').addStyleClass("sapMUCItemIcon");}if(N===false){n.attachPress(function(R){sap.m.UploadCollection.prototype._triggerLink(R,t);});}}if(s==="Edit"){v.addStyleClass("sapMUCEditMode");}else{v.removeStyleClass("sapMUCEditMode");}y=new sap.m.CustomListItem(I+"-cli",{content:[o,n,x,v]});for(var Q in i.mProperties){if(i.mProperties.hasOwnProperty(Q)){y.mProperties[Q]=i.mProperties[Q];}}y._status=s;y.addStyleClass("sapMUCItem");return y;};f.prototype._createDeleteButton=function(i,s,I,E){var g=I.getEnableDelete();if(this.sErrorState==="Error"){g=false;}var o=new sap.m.Button({id:i+"-"+s,icon:"sap-icon://sys-cancel",type:sap.m.ButtonType.Transparent,enabled:g,visible:I.getVisibleDelete()}).addStyleClass("sapMUCDeleteBtn");return o;};f.prototype._fillList=function(i){var t=this;var m=i.length-1;q.each(i,function(I,o){if(!o._status){o._status=f._displayStatus;}if(!o._percentUploaded&&o._status===f._uploadingStatus){o._percentUploaded=0;}var l=t._mapItemToListItem(o);if(I===0&&m===0){l.addStyleClass("sapMUCListSingleItem");}else if(I===0){l.addStyleClass("sapMUCListFirstItem");}else if(I===m){l.addStyleClass("sapMUCListLastItem");}else{l.addStyleClass("sapMUCListItem");}t._oList.addAggregation("items",l,true);});};f.prototype._clearList=function(){if(this._oList){this._oList.destroyAggregation("items",true);}};f.prototype._getNumberOfAttachmentsLabel=function(i){var n=i||0;if(!this.oNumberOfAttachmentsLabel){this.oNumberOfAttachmentsLabel=new sap.m.Label(this.getId()+"-numberOfAttachmentsLabel",{design:sap.m.LabelDesign.Standard,text:this._oRb.getText("UPLOADCOLLECTION_ATTACHMENTS",[n])});}else{this.oNumberOfAttachmentsLabel.setText(this._oRb.getText("UPLOADCOLLECTION_ATTACHMENTS",[n]));}return this.oNumberOfAttachmentsLabel;};f.prototype._handleDelete=function(E,o){var p=E.getParameters();var I=o.getAggregation("items");var s=p.id.split("-deleteButton")[0];var g=null;var h="";var j;var m;o.sDeletedItemId=s;for(var i=0;i<I.length;i++){if(I[i].sId===s){g=i;break;}}if(q.sap.byId(o.sId).hasClass("sapUiSizeCompact")){h="sapUiSizeCompact";}if(o.editModeItem){sap.m.UploadCollection.prototype._handleOk(E,o,o.editModeItem,true);if(o.sErrorState==="Error"){return this;}}if(!!I[g]){j=I[g].getFileName();if(!j){m=this._oRb.getText("UPLOADCOLLECTION_DELETE_WITHOUT_FILENAME_TEXT");}else{m=this._oRb.getText("UPLOADCOLLECTION_DELETE_TEXT",j);}o._oItemForDelete=I[g];sap.m.MessageBox.show(m,{title:this._oRb.getText("UPLOADCOLLECTION_DELETE_TITLE"),actions:[sap.m.MessageBox.Action.OK,sap.m.MessageBox.Action.CANCEL],onClose:o._onCloseMessageBoxDeleteItem.bind(o),dialogId:"messageBoxDeleteFile",styleClass:h});}};f.prototype._onCloseMessageBoxDeleteItem=function(A){this._oItemForDelete._status=f._toBeDeletedStatus;if(A===sap.m.MessageBox.Action.OK){this.fireFileDeleted({documentId:this._oItemForDelete.getDocumentId(),item:this._oItemForDelete});this._oItemForDelete._status=f._toBeDeletedStatus;}};f.prototype._handleTerminate=function(E,o){var s="",u,g,l,h,i,j,k;if(q.sap.byId(o.sId).hasClass("sapUiSizeCompact")){s="sapUiSizeCompact";}u=this._splitString2Array(o._getFileUploader().getProperty("value"),o);for(i=0;i<u.length;i++){if(u[i].length===0){u.splice(i,1);}}for(i=0;i<o.aItems.length;i++){k=o.aItems[i].getFileName();if(o.aItems[i]._status===f._uploadingStatus&&u.indexOf(k)){u.push(k);}}g=new sap.m.List({});u.forEach(function(I){l=new sap.m.StandardListItem({title:I,icon:o._getIconFromFilename(I)});g.addAggregation("items",l,true);});h=new sap.m.Dialog({title:this._oRb.getText("UPLOADCOLLECTION_TERMINATE_TITLE"),content:[new sap.m.Text({text:this._oRb.getText("UPLOADCOLLECTION_TERMINATE_TEXT")}),g],buttons:[new sap.m.Button({text:this._oRb.getText("UPLOADCOLLECTION_OKBUTTON_TEXT"),press:function(){u=o._splitString2Array(o._getFileUploader().getProperty("value"),o);for(i=0;i<u.length;i++){for(j=0;j<o.aItems.length;j++){if(u[i]===o.aItems[j].getFileName()&&o.aItems[j]._status===f._displayStatus){o.fireFileDeleted({documentId:o.aItems[j].getDocumentId()});o.aItems[j]._status=f._toBeDeletedStatus;break;}else if(u[i]===o.aItems[j].getFileName()&&o.aItems[j]._status===f._uploadingStatus){o.aItems[j]._status=f._toBeDeletedStatus;o.aItems.splice(j,1);o.removeItem(o.aItems[j]);break;}}}o._getFileUploader().abort();o.invalidate();h.close();}}),new sap.m.Button({text:this._oRb.getText("UPLOADCOLLECTION_CANCELBUTTON_TEXT"),press:function(){h.close();}})],styleClass:s});h.open();};f.prototype._handleEdit=function(E,i){if(this.sErrorState!=="Error"){i._status="Edit";this.editModeItem=E.getSource().getId().split("-editButton")[0];this.invalidate();}};f.prototype._handleClick=function(E,o,s){if(E.target.id.lastIndexOf("editButton")>0){sap.m.UploadCollection.prototype._handleOk(E,o,s,false);}else if(E.target.id.lastIndexOf("cancelButton")>0){sap.m.UploadCollection.prototype._handleCancel(E,o,s);}else if(E.target.id.lastIndexOf("ia_imageHL")<0&&E.target.id.lastIndexOf("ia_iconHL")<0&&E.target.id.lastIndexOf("deleteButton")<0&&E.target.id.lastIndexOf("ta_editFileName")<0){if(E.target.id.lastIndexOf("cli")>0){o.sFocusId=E.target.id;}sap.m.UploadCollection.prototype._handleOk(E,o,s,true);}};f.prototype._handleOk=function(E,o,s,t){var T=true;var g=document.getElementById(s+"-ta_editFileName-inner");var n;var S=s.split("-").pop();var O=o.aItems[S].getProperty("fileName");var h=f.prototype._splitFilename(O);var i=sap.ui.getCore().byId(s+"-ta_editFileName");var j=o.aItems[S].errorState;var k=o.aItems[S].changedFileName;if(g!==null){n=g.value.replace(/^\s+/,"");}var l=E.srcControl?E.srcControl.getId().split("-"):E.oSource.getId().split("-");l=l.slice(0,3);o.sFocusId=l.join("-")+"-cli";if(!!n&&(n.length>0)){var S=s.split("-").pop();o.aItems[S]._status=f._displayStatus;var O=o.aItems[S].getProperty("fileName");var h=f.prototype._splitFilename(O);if(h.name!==n){if(!o.getSameFilenameAllowed()){var i=sap.ui.getCore().byId(s+"-ta_editFileName");if(sap.m.UploadCollection.prototype._checkDoubleFileName(n+h.extension,o.aItems)){var j=o.aItems[S].errorState;var k=o.aItems[S].changedFileName;i.setProperty("valueState","Error",true);o.aItems[S]._status="Edit";o.aItems[S].errorState="Error";o.aItems[S].changedFileName=n;o.sErrorState="Error";T=false;if(j!=="Error"||k!==n){o.invalidate();}}else{i.setValueState="";o.aItems[S].errorState=null;o.aItems[S].changedFileName=null;o.sErrorState=null;o.editModeItem=null;if(t){o.invalidate();}}}if(T){o._oItemForRename=o.aItems[S];o._onEditItemOk.bind(o)(n+h.extension);}}else{o.sErrorState=null;o.aItems[S].errorState=null;o.editModeItem=null;if(t){o.invalidate();}}}else if(g!==null){o.aItems[S]._status="Edit";o.aItems[S].errorState="Error";o.aItems[S].changedFileName=n;o.sErrorState="Error";if(j!=="Error"||k!==n){o.aItems[S].invalidate();}}};f.prototype._onEditItemOk=function(n){if(this._oItemForRename){this._oItemForRename.setFileName(n);this.fireFileRenamed({documentId:this._oItemForRename.getProperty("documentId"),fileName:n,item:this._oItemForRename});}delete this._oItemForRename;};f.prototype._handleCancel=function(E,o,s){var S=s.split("-").pop();o.aItems[S]._status=f._displayStatus;o.aItems[S].errorState=null;o.aItems[S].changedFileName=sap.ui.getCore().byId(s+"-ta_editFileName").getProperty("value");o.sFocusId=o.editModeItem+"-cli";o.sErrorState=null;o.editModeItem=null;o.invalidate();};f.prototype._onChange=function(E){if(E){var t=this;var r,g,i,s;this._cAddItems=0;if(sap.ui.Device.browser.msie&&sap.ui.Device.browser.version<=9){var n=E.getParameter("newValue");if(!n){return;}s=n.split(/\" "/)[0];if(s.length===0){return;}}else{g=E.getParameter("files").length;if(g===0){return;}this._oFileUploader.removeAllAggregation("headerParameters",true);this.removeAllAggregation("headerParameters",true);}this._oFileUploader.removeAllAggregation("parameters",true);this.removeAllAggregation("parameters",true);if(sap.ui.Device.browser.msie&&sap.ui.Device.browser.version<=9){var o={name:E.getParameter("newValue")};var p={files:[o]};this.fireChange({getParameter:function(j){if(j==="files"){return[o];}},getParameters:function(){return p;},mParameters:p,files:[o]});}else{this.fireChange({getParameter:function(j){if(j){return E.getParameter(j);}},getParameters:function(){return E.getParameters();},mParameters:E.getParameters(),files:E.getParameter("files")});}var P=this.getAggregation("parameters");if(P){q.each(P,function(j,k){var l=new sap.ui.unified.FileUploaderParameter({name:k.getProperty("name"),value:k.getProperty("value")});t._oFileUploader.addParameter(l);});}var I;if(sap.ui.Device.browser.msie&&sap.ui.Device.browser.version<=9){I=new sap.m.UploadCollectionItem();I.setProperty("contributor",null);I.setDocumentId(null);I.setEnableDelete(true);I.setFileName(s);I.setMimeType(null);I._status=f._uploadingStatus;I._percentUploaded=0;I.setThumbnailUrl(null);I.setUploadedDate(null);I.setUrl(null);this.aItems.unshift(I);this.insertItem(I);this._cAddItems++;}else{this._requestIdValue=this._requestIdValue+1;r=this._requestIdValue.toString();var h=this.getAggregation("headerParameters");for(i=0;i<g;i++){I=new sap.m.UploadCollectionItem();I.setProperty("contributor",null);I.setDocumentId(null);I.setEnableDelete(true);I.setFileName(E.getParameter("files")[i].name);I.setMimeType(null);I._status=f._uploadingStatus;I._percentUploaded=0;I.setThumbnailUrl(null);I.setUploadedDate(null);I.setUrl(null);I._requestIdName=r;I.fileSize=E.getParameter("files")[i].size;this.aItems.unshift(I);this.insertItem(I);this._cAddItems++;}if(h){q.each(h,function(j,k){t._oFileUploader.addHeaderParameter(new sap.ui.unified.FileUploaderParameter({name:k.getProperty("name"),value:k.getProperty("value")}));});}t._oFileUploader.addHeaderParameter(new sap.ui.unified.FileUploaderParameter({name:this._requestIdName,value:r}));}}};f.prototype._onFilenameLengthExceed=function(E){var o={name:E.getParameter("fileName")};var g=[o];this.fireFilenameLengthExceed({getParameter:function(p){if(p){return E.getParameter(p);}},getParameters:function(){return E.getParameters();},mParameters:E.getParameters(),files:g});};f.prototype._onFileSizeExceed=function(E){if(sap.ui.Device.browser.msie&&sap.ui.Device.browser.version<=9){var s=E.getParameter("newValue");var o={name:s};var p={newValue:s,files:[o]};this.fireFileSizeExceed({getParameter:function(P){if(P==="files"){return[o];}else if(P==="newValue"){return s;}},getParameters:function(){return p;},mParameters:p,files:[o]});}else{var o={name:E.getParameter("fileName"),fileSize:E.getParameter("fileSize")};this.fireFileSizeExceed({getParameter:function(P){if(P){return E.getParameter(P);}},getParameters:function(){return E.getParameters();},mParameters:E.getParameters(),files:[o]});}};f.prototype._onTypeMissmatch=function(E){var o={name:E.getParameter("fileName"),fileType:E.getParameter("fileType"),mimeType:E.getParameter("mimeType")};var g=[o];this.fireTypeMissmatch({getParameter:function(p){if(p){return E.getParameter(p);}},getParameters:function(){return E.getParameters();},mParameters:E.getParameters(),files:g});};f.prototype._onUploadTerminated=function(E){if(E){var i;var r=this._getRequestId(E);var s=E.getParameter("fileName");var g=this.aItems.length;for(i=0;i<g;i++){if(this.aItems[i]===s&&this.aItems[i]._requestIdName===r&&this.aItems[i]._status===f._uploadingStatus){this.aItems.splice(i,1);this.removeItem(i);break;}}this.fireUploadTerminated();}};f.prototype._onUploadComplete=function(E){if(E){var i,r,u,g,h=k();r=this._getRequestId(E);u=E.getParameter("fileName");if(!u){var j=(E.getSource().getProperty("value")).split(/\" "/);u=j[0];}g=this.aItems.length;for(i=0;i<g;i++){if(!r){if(this.aItems[i].getProperty("fileName")===u&&this.aItems[i]._status===f._uploadingStatus&&h){this.aItems[i]._percentUploaded=100;this.aItems[i]._status=f._displayStatus;break;}else if(this.aItems[i].getProperty("fileName")===u&&this.aItems[i]._status===f._uploadingStatus){this.aItems.splice(i,1);break;}}else if(this.aItems[i].getProperty("fileName")===u&&this.aItems[i]._requestIdName===r&&this.aItems[i]._status===f._uploadingStatus&&h){this.aItems[i]._percentUploaded=100;this.aItems[i]._status=f._displayStatus;break;}else if(this.aItems[i].getProperty("fileName")===u&&this.aItems[i]._requestIdName===r&&this.aItems[i]._status===f._uploadingStatus){this.aItems.splice(i,1);break;}}this.fireUploadComplete({getParameter:E.getParameter,getParameters:E.getParameters,mParameters:E.getParameters(),files:[{fileName:E.getParameter("fileName"),responseRaw:E.getParameter("responseRaw"),reponse:E.getParameter("response"),status:E.getParameter("status"),headers:E.getParameter("headers")}]});}function k(){var R=E.getParameter("status").toString()||"200";if(R[0]==="2"||R[0]==="3"){return true;}else{return false;}}};f.prototype._onUploadProgress=function(E){if(E){var i,u,p,P,r,g;u=E.getParameter("fileName");r=this._getRequestId(E);P=Math.round(E.getParameter("loaded")/E.getParameter("total")*100);if(P===100){P=P-1;}p=this._oRb.getText("UPLOADCOLLECTION_UPLOADING",[P]);g=this.aItems.length;for(i=0;i<g;i++){if(this.aItems[i].getProperty("fileName")===u&&this.aItems[i]._requestIdName==r&&this.aItems[i]._status===f._uploadingStatus){sap.ui.getCore().byId(this.aItems[i].getId()+"-ta_progress").setText(p);this.aItems[i]._percentUploaded=P;break;}}}};f.prototype._getRequestId=function(E){var h;h=E.getParameter("requestHeaders");if(!h){return null;}for(var j=0;j<h.length;j++){if(h[j].name==this._requestIdName){return h[j].value;}}};f.prototype._getFileUploader=function(){var t=this;if(!this._oFileUploader){var s=(sap.ui.Device.browser.msie&&sap.ui.Device.browser.version<=9)?false:true;this._oFileUploader=new sap.ui.unified.FileUploader(this.getId()+"-uploader",{buttonOnly:true,buttonText:" ",enabled:this.getUploadEnabled(),fileType:this.getFileType(),icon:"sap-icon://add",iconFirst:false,maximumFilenameLength:this.getMaximumFilenameLength(),maximumFileSize:this.getMaximumFileSize(),mimeType:this.getMimeType(),multiple:this.getMultiple(),name:"uploadCollection",uploadOnChange:true,sameFilenameAllowed:true,uploadUrl:this.getUploadUrl(),useMultipart:false,sendXHR:s,change:function(E){t._onChange(E);},filenameLengthExceed:function(E){t._onFilenameLengthExceed(E);},fileSizeExceed:function(E){t._onFileSizeExceed(E);},typeMissmatch:function(E){t._onTypeMissmatch(E);},uploadAborted:function(E){t._onUploadTerminated(E);},uploadComplete:function(E){t._onUploadComplete(E);},uploadProgress:function(E){t._onUploadProgress(E);}});var T=this._oFileUploader.getTooltip();if(!T&&!sap.ui.Device.browser.msie){this._oFileUploader.setTooltip(" ");}}return this._oFileUploader;};f.prototype._getIconFromFilename=function(s){var g=this._splitFilename(s).extension;if(q.type(g)==="string"){g=g.toLowerCase();}switch(g){case'.bmp':case'.jpg':case'.jpeg':case'.png':return'sap-icon://attachment-photo';case'.csv':case'.xls':case'.xlsx':return'sap-icon://excel-attachment';case'.doc':case'.docx':case'.odt':return'sap-icon://doc-attachment';case'.pdf':return'sap-icon://pdf-attachment';case'.ppt':case'.pptx':return'sap-icon://ppt-attachment';case'.txt':return'sap-icon://document-text';default:return'sap-icon://document';}};f.prototype._getThumbnail=function(t,s){if(t){return t;}else{return this._getIconFromFilename(s);}};f.prototype._triggerLink=function(E,o){var l=null;var i;if(o.editModeItem){sap.m.UploadCollection.prototype._handleOk(E,o,o.editModeItem,true);if(o.sErrorState==="Error"){return this;}o.sFocusId=E.getParameter("id");}i=E.oSource.getId().split("-");l=i[i.length-2];sap.m.URLHelper.redirect(o.aItems[l].getProperty("url"),true);};f.prototype.onkeydown=function(E){switch(E.keyCode){case q.sap.KeyCodes.F2:sap.m.UploadCollection.prototype._handleF2(E,this);break;case q.sap.KeyCodes.ESCAPE:sap.m.UploadCollection.prototype._handleESC(E,this);break;case q.sap.KeyCodes.DELETE:sap.m.UploadCollection.prototype._handleDEL(E,this);break;case q.sap.KeyCodes.ENTER:sap.m.UploadCollection.prototype._handleENTER(E,this);break;default:return;}E.setMarked();};f.prototype._setFocusAfterDeletion=function(g,o){if(!g){return;}var l=o.aItems.length;var s=null;if(l===0){var h=q.sap.byId(o._oFileUploader.sId);var i=h.find(":button");q.sap.focus(i);}else{var j=g.split("-").pop();if((l-1)>=j){s=g+"-cli";}else{s=o.aItems.pop().sId+"-cli";}sap.m.UploadCollection.prototype._setFocus2LineItem(s);}};f.prototype._setFocus2LineItem=function(s){if(!s){return;}var $=q.sap.byId(s);q.sap.focus($);};f.prototype._handleENTER=function(E,o){var t;var l;var g;if(o.editModeItem){t=E.target.id.split(o.editModeItem).pop();}else{t=E.target.id.split("-").pop();}switch(t){case"-ta_editFileName-inner":case"-okButton":sap.m.UploadCollection.prototype._handleOk(E,o,o.editModeItem,true);break;case"-cancelButton":E.preventDefault();sap.m.UploadCollection.prototype._handleCancel(E,o,o.editModeItem);break;case"-ia_iconHL":case"-ia_imageHL":var i=o.editModeItem.split("-").pop();sap.m.URLHelper.redirect(o.aItems[i].getProperty("url"),true);break;case"ia_iconHL":case"ia_imageHL":case"cli":l=E.target.id.split(t)[0]+"ta_filenameHL";g=sap.ui.getCore().byId(l);if(g.getEnabled()){var i=E.target.id.split("-")[2];sap.m.URLHelper.redirect(o.aItems[i].getProperty("url"),true);}break;default:return;}};f.prototype._handleDEL=function(E,o){if(!o.editModeItem){var g=q.sap.byId(E.target.id);var h=g.find("[id$='-deleteButton']");var i=sap.ui.getCore().byId(h[0].id);i.firePress();}};f.prototype._handleESC=function(E,o){if(o.editModeItem){o.sFocusId=o.editModeItem+"-cli";o.aItems[o.editModeItem.split("-").pop()]._status=f._displayStatus;sap.m.UploadCollection.prototype._handleCancel(E,o,o.editModeItem);}};f.prototype._handleF2=function(E,o){var O=sap.ui.getCore().byId(E.target.id);var g=q.sap.byId(E.target.id);if(O!==undefined){if(O._status==f._displayStatus){g=q.sap.byId(E.target.id);var h=g.find("[id$='-editButton']");var i=sap.ui.getCore().byId(h[0].id);if(i.getEnabled()){if(o.editModeItem){sap.m.UploadCollection.prototype._handleClick(E,o,o.editModeItem);}if(o.sErrorState!=="Error"){i.firePress();}}}else{sap.m.UploadCollection.prototype._handleClick(E,o,o.editModeItem);}}else{if(E.target.id.search(o.editModeItem)===0){sap.m.UploadCollection.prototype._handleOk(E,o,o.editModeItem,true);}}};f.prototype._splitString2Array=function(s,o){if(o.getMultiple()===true&&!(sap.ui.Device.browser.msie&&sap.ui.Device.browser.version<=9)){s=s.substring(1,s.length-2);}return s.split(/\" "/);};f.prototype._checkDoubleFileName=function(s,I){if(I.length===0||!s){return false;}var l=I.length;s=s.replace(/^\s+/,"");for(var i=0;i<l;i++){if(s==I[i].getProperty("fileName")){return true;}}return false;};f.prototype._splitFilename=function(s){var r={};var n=s.split(".");if(n.length==1){r.extension="";r.name=n.pop();return r;}r.extension="."+n.pop();r.name=n.join(".");return r;};return f;},true);

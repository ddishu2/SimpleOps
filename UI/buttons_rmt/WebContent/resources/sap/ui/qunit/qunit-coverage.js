/*!
 * UI development toolkit for HTML5 (OpenUI5)
 * (c) Copyright 2009-2015 SAP SE or an SAP affiliate company.
 * Licensed under the Apache License, Version 2.0 - see LICENSE.txt.
 */
(function(){if(typeof QUnit!=="undefined"&&typeof document.addEventListener!=="undefined"){window["sap-ui-qunit-coverage"]="client";var d=document.location.href.replace(/\?.*|#.*/g,""),s=document.getElementsByTagName("script"),b=null,f=null;for(var i=0;i<s.length;i++){var S=s[i].getAttribute("src");if(S){var B=S.match(/(.*)qunit\/qunit-coverage\.js$/i);if(B&&B.length>1){b=B[1];break;}}}if(b===null){if(jQuery&&jQuery.sap&&jQuery.sap.getModulePath){f=jQuery.sap.getModulePath("sap.ui.thirdparty.blanket",".js");}else{throw new Error("qunit-coverage.js: The script tag seems to be malformed!");}}else{f=b+"thirdparty/blanket.js";}if(QUnit.urlParams.coverage){var r=new window.XMLHttpRequest();r.open('GET',f,false);r.onreadystatechange=function(){if(r.readyState==4){var a=r.responseText;if(typeof window.URI!=="undefined"){a+="\n//# sourceURL="+URI(f).absoluteTo(d);}window.eval(a);QUnit.config.autostart=true;window.blanket.options("existingRequireJS",true);if(jQuery&&jQuery.sap){jQuery.sap.require._hook=function(a,m){if(a.indexOf("window['sap-ui-qunit-coverage'] = 'server';")!==0){window.blanket.instrument({inputFile:a,inputFileName:m,instrumentCache:false},function(I){a=I;});}return a;};}else{throw new Error("qunit-coverage.js: jQuery.sap.global is not loaded - require hook cannot be set!");}}};r.send(null);}else{QUnit.config.urlConfig.push({id:"coverage",label:"Enable coverage",tooltip:"Enable code coverage."});}}else{if(document.addEventListener!=="undefined"){throw new Error("qunit-coverage.js: your browser cannot be used for client-side coverage!");}else{throw new Error("qunit-coverage.js: QUnit is not loaded yet!");}}})();

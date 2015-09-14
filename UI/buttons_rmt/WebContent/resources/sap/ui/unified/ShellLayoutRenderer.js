/*!
 * UI development toolkit for HTML5 (OpenUI5)
 * (c) Copyright 2009-2015 SAP SE or an SAP affiliate company.
 * Licensed under the Apache License, Version 2.0 - see LICENSE.txt.
 */
sap.ui.define(['jquery.sap.global'],function(q){"use strict";var S={};S.render=function(r,s){var i=s.getId();r.write("<div");r.writeControlData(s);r.addClass("sapUiUfdShell");if(s._animation){r.addClass("sapUiUfdShellAnim");}if(!s.getHeaderVisible()){r.addClass("sapUiUfdShellNoHead");}r.addClass("sapUiUfdShellHead"+(s._showHeader?"Visible":"Hidden"));if(s.getShowCurtain()){r.addClass("sapUiUfdShellCurtainVisible");}else{r.addClass("sapUiUfdShellCurtainHidden");r.addClass("sapUiUfdShellCurtainClosed");}r.writeClasses();r.write(">");r.write("<hr id='",i,"-brand' class='sapUiUfdShellBrand'/>");r.write("<header id='",i,"-hdr'  class='sapUiUfdShellHead'");if(sap.ui.getCore().getConfiguration().getAccessibility()){r.writeAttribute("role","banner");}r.write("><div><div id='",i,"-hdrcntnt' class='sapUiUfdShellCntnt'>");if(s.getHeader()){r.renderControl(s.getHeader());}r.write("</div>","</div>","</header>");r.write("<section id='",i,"-curt' class='sapUiUfdShellCntnt sapUiUfdShellCurtain'>");r.write("<div id='",i,"-curtcntnt' class='sapUiUfdShellCntnt'>");r.renderControl(s._curtCont);r.write("</div>");r.write("<span id='",i,"-curt-focusDummyOut' tabindex='0'></span>");r.write("</section>");r.write("<div id='",i,"-cntnt' class='sapUiUfdShellCntnt sapUiUfdShellCanvas sapUiUfdShellBackground'>");r.write("<div id='",i,"-strgbg' class='sapUiUfdShellBG"+(s._useStrongBG?" sapMGlobalBackgroundColorStrong":"")+"'></div>");r.write("<div class='sapMGlobalBackgroundImage sapUiUfdShellBG'></div>");r.renderControl(s._cont);r.write("</div>");r.write("<span id='",i,"-main-focusDummyOut' tabindex='"+(s.getShowCurtain()?0:-1)+"'></span>");r.write("</div>");};return S;},true);

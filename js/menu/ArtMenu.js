// setups spans for the first level of vmenu and sets an active element
function ArtMenu_SpansSetup(container) {
    jQuery(container).find("ul:first").children("li:not(.separator)").each(function() {
        jQuery(this).children("a").each(function() {
            // isIncluded() declared in CCS Functions.js, it compares hrefs
            if (isIncluded(jQuery(this).get(0).href, window.location.href))
                jQuery(this).addClass("active");
            jQuery(this).html("<span class=\"l\"></span><span class=\"r\"></span><span class=\"t\">" + jQuery(this).html() + "</span>");
        });
    });
}

// overrides CCS load_ADxMenu to call specific functions for Artisteer or CCS menus
function load_ADxMenu(sender) {
    if (sender.id && sender.id != "") {
        jQuery("#" + sender.id + " ul:first").each(function () {
            if (jQuery(this).hasClass("art-hmenu")) { /* Artisteer Menu */
                if (typeof sender.spansExtended == "undefined") {
                    ArtMenu_SpansSetup(sender);
                    sender.spansExtended = true;
                }
            } else { /* CCS Menu */
	            CCSMenu_SpansSetup(sender.id);
	            if (jQuery.browser.msie && jQuery.browser.version.substr(0, 1) == "6") 
	                ADxMenu_IESetup(sender.id);
        	    CCSMenu_TreeMenuSetup(sender.id);
        	    menuMarkActLink(sender.id);
            }
        });
	}
}

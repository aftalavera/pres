var winOnLoad = window.onload;
window.onload = null;



artLoadEvent.add(function() { artButtonsSetupJsHover("Button"); });

artLoadEvent.add(function() {
    if (typeof window.AjaxPanelEvents == "undefined") window.AjaxPanelEvents = [];
    window.AjaxPanelEvents.push({
        eventName: "afterUpdate",
        func: function(updatePanel) {
            artButtonsSetupJsHover("Button", updatePanel);
        }
    });
});

artLoadEvent.add(function() {
    // select all tables with table class
    var tables = document.getElementsByTagName('table');
    var formTables = [];
    for (var i = 0; i < tables.length; i++) {
        var table = tables[i];
        if (-1 != table.className.indexOf(' table') || -1 != table.className.indexOf(' art-article'))
            formTables[formTables.length] = table;
    }
});

if (winOnLoad) artLoadEvent.add(winOnLoad);

// menu

artLoadEvent.add(function() {
    var uls = document.getElementsByTagName('ul');
    for (var i = 0; i < uls.length; i++) {
        var ul = uls[i];
        if (-1 != ul.className.indexOf('art-menu')) {
            if (typeof ul.spansExtended == "undefined") {
                ArtMenu_SpansSetup(ul);
                ul.spansExtended = true;
            }
        }
    }
});

function ArtMenu_GetElement(e, name) {
    name = name.toLowerCase();
    for (var n = e.firstChild; null != n; n = n.nextSibling)
        if (1 == n.nodeType && name == n.nodeName.toLowerCase())
        return n;
    return null;
}

function ArtMenu_GetElements(e, name) {
    name = name.toLowerCase();
    var elements = [];
    for (var n = e.firstChild; null != n; n = n.nextSibling)
        if (1 == n.nodeType && name == n.nodeName.toLowerCase())
        elements[elements.length] = n;
    return elements;
}

function ArtMenu_SpansSetup(menuUL) {
    var menuULLI = ArtMenu_GetElements(menuUL, 'li');
    for (var i = 0; i < menuULLI.length; i++) {
        var li = menuULLI[i];
        if ('separator' == li.className) continue;
        var a = ArtMenu_GetElement(li, 'a');
        if (null == a) continue;
        if (isIncluded(a.href, window.location.href)) {
            a.className = 'active';
        }
        var spant = document.createElement('span');
        spant.className = 't';
        while (a.firstChild)
            spant.appendChild(a.firstChild);
        a.appendChild(document.createElement('span')).className = 'l';
        a.appendChild(document.createElement('span')).className = 'r';
        a.appendChild(spant);
    }
}

// isIncluded the same as in Functions.js if this script is used independently
function isIncluded(href1, href2) {
    if (href1 == null || href2 == null)
        return href1 == href2;
    if (href1.indexOf("?") == -1 || href1.split("?")[1] == "")
        return href1.split("?")[0] == href2.split("?")[0];
    if (href2.indexOf("?") == -1 || href2.split("?")[1] == "")
        return href1.replace("?", "") == href2.replace("?", "");
    if (href1.split("?")[0] != href2.split("?")[0])
        return false;
    var params = href1.split("?")[1];
    params = params.split("&");
    var i, par1, par2, nv;
    par1 = new Array();
    for (i in params) {
        if (typeof (params[i]) == "function")
            continue;
        nv = params[i].split("=");
        if (nv[0] != "FormFilter")
            par1[nv[0]] = nv[1];
    }
    params = href2.split("?")[1];
    params = params.split("&");
    par2 = new Array();
    for (i in params) {
        if (typeof (params[i]) == "function")
            continue;
        nv = params[i].split("=");
        if (nv[0] != "FormFilter")
            par2[nv[0]] = nv[1];
    }
    /*if (par1.length != par2.length)
    return false;*/
    for (i in par1)
        if (par1[i] != par2[i])
        return false;
    return true;
}

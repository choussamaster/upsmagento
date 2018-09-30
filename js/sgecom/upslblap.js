var upsaccesspointOptions = {}, upsapSessionAddressAP = '', upsapStreetStartSymbol = 1;
document.addEventListener("DOMContentLoaded", function () {
        runXmlHttpRequest('click', "upsap_close_sunrise", function () {
            popupCloseMap();
        });
        xmlHttpRequestAjax(upsapBaseUrl + "upsap/index/getSessionAddressAP", "", function (o) {
            o = JSON.parse(o);
            if (!o.error) {
                setAccessPointAddress(o);
            }
            xmlHttpRequestAjax(upsapBaseUrl + "upsap/index/getShippingMethods", "", function (obj) {
                o = JSON.parse(obj);
                addEventListenerId('click', 's_method_', startAction);
            });
        });
        if (!document.getElementById("billing-address-select")) {
            var billingAddressDiv = document.createElement("DIV");
            billingAddressDiv.style.position = 'absolute';
            billingAddressDiv.style.top = '0';
            billingAddressDiv.style.left = '0';
            billingAddressDiv.style.width = '100%';
            billingAddressDiv.style.height = '100%';
            billingAddressDiv.style.backgroundColor = '#fff';
            billingAddressDiv.style.opacity = '.5';
            billingAddressDiv.id = 'upsap_address_div_id';
            if (document.getElementById("shipping-block-methods")) {
                document.getElementById("shipping-block-methods").parentNode.style.position = 'relative';
                document.getElementById("shipping-block-methods").parentNode.appendChild(billingAddressDiv);
            }
        }
        addEventListenerClass('change', 'required-entry', function () {
            if (!document.getElementById("billing-address-select") || (document.getElementById("billing-address-select") && document.getElementById("billing-address-select").length > 0 && document.getElementById("billing-address-select").value == "")) {
                var AddressType = "billing";
                if (document.getElementById("billing:use_for_shipping_yes") && document.getElementById("billing:use_for_shipping_yes").getAttribute("checked") != "checked" && document.getElementById("shipping:postcode").value != "") {
                    AddressType = "shipping";
                }
                if (document.getElementById(AddressType + ":street0")) {
                    upsapStreetStartSymbol = 0;
                }
                if (document.getElementById(AddressType + ":street" + upsapStreetStartSymbol).value.length > 0
                    && document.getElementById(AddressType + ":city").value.length > 0
                    && document.getElementById(AddressType + ":postcode").value.length > 0
                    && document.getElementById(AddressType + ":country_id").value.length > 0
                ) {
                    if (document.getElementById("upsap_address_div_id")) {
                        document.getElementById("upsap_address_div_id").parentNode.removeChild(document.getElementById("upsap_address_div_id"));
                    }
                }
            }
        });

        var elemMethod = document.querySelectorAll('input[name="shipping_method"][value*="upsap"]'),
            methodLength = elemMethod.length;
        for (var i = 0; i < methodLength; i++) {
            elemMethod[i].checked = false;
        }

        var mutation = new MutationObserverCheckout();
        mutation.init();

        addEventListenerQuery('change', 'input[name="shipping_method"]:checked', function (el, event) {
            clearAdress();
        });
    }
);

function clearAdress() {
    var el2 = document.getElementById("onepage-checkout-shipping-method-additional-load");
    if (el2) {
        editTextElement(el2, '');
    }
    else {
        if (document.getElementById("upsap_beforeload_sunrise")) {
            document.getElementById("upsap_beforeload_sunrise").parentNode.removeChild(document.getElementById("upsap_beforeload_sunrise"));
        }
    }
}

function startAction(el) {
    clearAdress();
    if (el.target.getAttribute('id').indexOf('s_method_upsap_') !== -1) {
        xmlHttpRequestAjax(upsapBaseUrl + "upsap/index/customerAddress", "", function (o) {
            o = JSON.parse(o);
            if (!o.error) {
                var address = {};
                if (o["street"]) {
                    o.street1 = o["street"];
                }
                if ((o.city && o.region) || o.postcode) {
                    address.street = o.street1;
                    address.street2 = o.street2;
                    address.city = o.city;
                    address.region = o.region;
                    address.postcode = o.postcode;
                    address.country_id = o.country_id;
                    start3Action(address);
                }
                else if (!document.getElementById("billing-address-select") || (document.getElementById("billing-address-select") && document.getElementById("billing-address-select").length > 0 && document.getElementById("billing-address-select").value == "")) {
                    var AddressType = "billing";
                    if (document.getElementById("billing:use_for_shipping_yes") && document.getElementById("billing:use_for_shipping_yes").getAttribute("checked") != "checked" && document.getElementById("shipping:postcode").value != "") {
                        AddressType = "shipping";
                    }
                    if (document.querySelector("*[name='" + AddressType + "[street0]']") || document.querySelector("*[name='" + AddressType + "[street][0]']")) {
                        upsapStreetStartSymbol = 0;
                    }
                    var address = {};
                    if (document.querySelector("*[name='" + AddressType + "[street" + upsapStreetStartSymbol + "]']")) {
                        address.street = document.querySelector("*[name='" + AddressType + "[street" + upsapStreetStartSymbol + "]']").value;
                        address.street2 = document.querySelector("*[name='" + AddressType + "[street" + (upsapStreetStartSymbol + 1) + "]']") ? document.querySelector("*[name='" + AddressType + "[street" + (upsapStreetStartSymbol + 1) + "]']").value : '';
                    } else if (document.querySelector("*[name='" + AddressType + "[street][" + upsapStreetStartSymbol + "]']")) {
                        address.street = document.querySelector("*[name='" + AddressType + "[street][" + upsapStreetStartSymbol + "]']").value;
                        address.street2 = document.querySelector("*[name='" + AddressType + "[street][" + (upsapStreetStartSymbol + 1) + "]']") ? document.querySelector("*[name='" + AddressType + "[street][" + (upsapStreetStartSymbol + 1) + "]']").value : '';
                    }
                    address.city = document.querySelector("*[name='" + AddressType + "[city]']").value;
                    if (document.querySelector("*[name='" + AddressType + "[region]']")) {
                        address.region = document.querySelector("*[name='" + AddressType + "[region]']").value;
                    }
                    address.postcode = document.querySelector("*[name='" + AddressType + "[postcode]']").value;
                    address.country_id = document.querySelector("*[name='" + AddressType + "[country_id]']").value;
                    start3Action(address);
                }
            }
        });
    }
}

function start3Action(address) {
    if (address.country_id) {
        var str = "oId=asdf12432423432423&viewPortResponsive=true&cburl=" + encodeURIComponent(upsapBaseUrl + "upsap/index/callback?")
            + "&target=_self" + getLocale()+"&edit="+upsapFancyboxModify;
        if (upsapFancyboxStyle.length > 0) {
            str += "&css=" + upsapFancyboxStyle;
        }
        if ((address.city && address.region) || address.postcode) {
            if (address.street) {
                str += "&add1=" + encodeURIComponent(address.street);
            }
            if (address.street2) {
                str += "&add2=" + encodeURIComponent(address.street2);
            }
            if (address.city) {
                str += "&city=" + encodeURIComponent(address.city);
            }
            if (address.region) {
                str += "&state=" + encodeURIComponent(address.region);
            }
            if (address.postcode) {
                str += "&postal=" + encodeURIComponent(address.postcode);
            }
            str += "&country=" + encodeURIComponent(address.country_id);
            var url = isHttps + "://www.ups.com/lsw/invoke?";
            var urlMy = url + str;

            var billingAddressDiv = document.createElement("DIV");
            billingAddressDiv.setAttribute("id", "upsap_substrate_sunrise");
            billingAddressDiv.style.position = "fixed";
            billingAddressDiv.style.top = "0";
            billingAddressDiv.style.left = "0";
            billingAddressDiv.style.width = getBodyWidthHeight().width + "px";
            billingAddressDiv.style.height = getBodyWidthHeight().height + "px";
            billingAddressDiv.style.opacity = 0.5;
            billingAddressDiv.style.backgroundColor = "#000000";
            billingAddressDiv.style.zIndex = 99990;


            var divchik = document.createElement("DIV");
            divchik.setAttribute("id", "sgecom_upsap_sunrise");
            divchik.setAttribute("width", 1080);
            divchik.setAttribute("height", 750);
            if (getWindowWidthHeight().width < 1080) {
                divchik.setAttribute("width", getWindowWidthHeight().width * 0.9);
            }
            if (getWindowWidthHeight().height < 750) {
                divchik.setAttribute("height", getWindowWidthHeight().height * 0.9);
            }
            var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || (typeof safari !== 'undefined' && safari.pushNotification));
            /*if(isSafari){*/
                divchik.style.position = "absolute";
            /*} else {
                divchik.style.position = "fixed";
            }*/

            divchik.style.top = ((getWindowWidthHeight().height / 2) - divchik.getAttribute('height') / 2) + "px";
            divchik.style.left = ((getWindowWidthHeight().width / 2) - divchik.getAttribute('width') / 2) + "px";
            divchik.style.backgroundColor = "#ffffff";
            divchik.style.zIndex = 99999;

            var divchikClose = document.createElement("DIV");
            divchikClose.setAttribute("id", "upsap_close_sunrise");
            editTextElement(divchikClose, "x");

            var iframe = document.createElement("IFRAME");
            iframe.setAttribute("name", "dialog_upsap_access_points");
            iframe.setAttribute("src", urlMy);
            iframe.setAttribute("width", 1080);
            iframe.setAttribute("height", 750);
            iframe.setAttribute("style", "-webkit-user-drag: none;-webkit-overflow-scrolling: touch !important;overflow-y: auto;");
            if (getWindowWidthHeight().width < 1080) {
                iframe.setAttribute("width", getWindowWidthHeight().width * 0.9);
            }
            if (getWindowWidthHeight().height < 750) {
                iframe.setAttribute("height", getWindowWidthHeight().height * 0.9);
            }
            iframe.setAttribute("frameborder", 0);
            window.scrollTo(0,0);
            document.body.appendChild(billingAddressDiv);
            document.body.appendChild(divchik);
            document.getElementById("sgecom_upsap_sunrise").appendChild(divchikClose);
            document.getElementById("sgecom_upsap_sunrise").appendChild(iframe);

        }
    }
}

function setAccessPointToCheckout(o) {
    setAccessPointAddress(o);
    xmlHttpRequestAjax(upsapBaseUrl + "upsap/index/setSessionAddressAP", getAccPointAjaxUrl(o), function (o2) {
    });
    popupCloseMap(o);
}

function getAccPointAjaxUrl(o) {
    return "upsap_addLine1=" + encodeURIComponent(o.addLine1)
        + "&upsap_addLine2=" + encodeURIComponent(o.addLine2)
        + "&upsap_addLine3=" + encodeURIComponent(o.addLine3)
        + "&upsap_city=" + encodeURIComponent(o.city)
        + "&upsap_country=" + encodeURIComponent(o.country)
        + "&upsap_fax=" + encodeURIComponent(o.fax)
        + "&upsap_state=" + encodeURIComponent(o.state)
        + "&upsap_postal=" + encodeURIComponent(o.postal)
        + "&upsap_telephone=" + encodeURIComponent(o.telephone)
        + "&upsap_appuId=" + encodeURIComponent(o.appuId)
        + "&upsap_name=" + encodeURIComponent(o.name);
}

function setAccessPointAddress(o) {
    addInputHiddenElement("addLine1", o);
    addInputHiddenElement("addLine2", o);
    addInputHiddenElement("addLine3", o);
    addInputHiddenElement("city", o);
    addInputHiddenElement("country", o);
    addInputHiddenElement("fax", o);
    addInputHiddenElement("state", o);
    addInputHiddenElement("postal", o);
    addInputHiddenElement("telephone", o);
    addInputHiddenElement("appuId", o);
    addInputHiddenElement("name", o);
    if (!o.addLine2) {
        o.addLine2 = '';
    }
    if (!o.addLine3) {
        o.addLine3 = '';
    }
    var el = document.getElementById("onepage-checkout-shipping-method-additional-load");
    if (el) {
        upsapSessionAddressAP = o;
        editTextElement(el, 'UPS Access Point: ' + o.addLine1 + " " + o.addLine2 + " " + o.addLine3 + ', ' + o.city + ', ' + o.postal);
    }
    else {
        if (document.getElementById("upsap_beforeload_sunrise")) {
            document.getElementById("upsap_beforeload_sunrise").parentNode.removeChild(document.getElementById("upsap_beforeload_sunrise"));
        }
        var divchikPreload = document.createElement("DIV");
        divchikPreload.setAttribute("id", "upsap_beforeload_sunrise");
        upsapSessionAddressAP = o;
        editTextElement(divchikPreload, 'UPS Access Point: ' + o.addLine1 + " " + o.addLine2 + " " + o.addLine3 + ', ' + o.city + ', ' + o.postal);
        el = document.querySelectorAll(".onestepcheckout-shipping-method-block");
        if (el && el.length > 0) {
            el[0].appendChild(divchikPreload);
        }
        else if (el = document.querySelectorAll(".onestepcheckout-shipping-method-section") && el.length > 0) {
            el[0].appendChild(divchikPreload);
        } else {
            el = document.querySelectorAll("#aw-onestepcheckout-shipping-method-wrapper");
            if (el && el.length > 0) {
                el[0].appendChild(divchikPreload);
            }
        }
    }
}

function addInputHiddenElement(name, o) {

    if (o[name]) {
        var elemByName = document.getElementsByName("upsap_" + name);
        if (elemByName.length == 0) {
            var el = document.createElement("INPUT");
            el.setAttribute("type", "hidden");
            el.setAttribute("name", "upsap_" + name);
            el.value = o[name];
            if (document.getElementById("co-shipping-method-form")) {
                document.getElementById("co-shipping-method-form").appendChild(el);
            }
            else if (document.getElementById("shipping-method")) {
                document.getElementById("shipping-method").appendChild(el);
            }
        }
        else {
            elemByName[0].value = o[name];
        }
    }
}

function popupCloseMap(o) {
    removepopapUPSAccessPoint("sgecom_upsap_sunrise");
    removepopapUPSAccessPoint("upsap_substrate_sunrise");
    if (!o) {
        document.querySelector('input[name="shipping_method"]:checked').checked = false;
    }
}

function runXmlHttpRequest(eventType, elementId, cb) {
    document.addEventListener(eventType, function (event) {
        var el = event.target
            , found;
        while (el && !(found = el.id === elementId)) {
            el = el.parentElement;
        }

        if (found) {
            cb.call(el, event);
        }
    });
}

function addEventListenerId(eventType, elementId, cb) {
    document.addEventListener(eventType, function (event) {
        var el = event.target
            , found;
        while (el && !(found = (el.getAttribute('id') && el.getAttribute('id').indexOf(elementId) !== -1))) {
            el = el.parentElement;
        }
        if (found) {
            cb.call(el, event);
        }
    });
}

function addEventListenerClass(eventType, elementClassName, cb) {
    document.addEventListener(eventType, function (event) {
        var el = event.target
            , found;
        while (el && !(found = (el.getAttribute('class') && el.getAttribute('class').indexOf(elementClassName) !== -1))) {
            el = el.parentElement;
        }

        if (found) {
            cb.call(el, event);
        }
    });
}

function addEventListenerQuery(eventType, elementSelectors, cb) {
    document.addEventListener(eventType, function (event) {
        var el = event.target
            , found;
        while (el && !(found = (el.querySelectorAll(elementSelectors).length > 0))) {
            el = el.parentElement;
        }
        if (found) {
            cb.call(el, event);
        }
    });
}

function editTextElement(elem, changeVal) {
    if (elem.textContent !== null) {
        elem.textContent = changeVal;
    } else {
        elem.innerText = changeVal;
    }
}

function removepopapUPSAccessPoint(id) {
    var element = document.getElementById(id);
    if(element) {
        element.parentNode.removeChild(element);
    }
}

function getWindowWidthHeight() {
    return {
        width: (window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth),
        height: (window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight)
    };
}

function getBodyWidthHeight() {
    var body = document.body,
        html = document.documentElement;

    var height = Math.max(body.scrollHeight, body.offsetHeight,
        html.clientHeight, html.scrollHeight, html.offsetHeight);
    var width = Math.max(body.scrollWidth, body.offsetWidth,
        html.clientWidth, html.scrollWidth, html.offsetWidth);
    return {height: height, width: width};
}

var xmlHttpRequestUPSAP;

function createRequest() {
    if (window.XMLHttpRequest) xmlHttpRequestUPSAP = new XMLHttpRequest();
    else if (window.ActiveXObject) {
        try {
            xmlHttpRequestUPSAP = new ActiveXObject('Msxml2.XMLHTTP');
        } catch (e) {
        }
        try {
            xmlHttpRequestUPSAP = new ActiveXObject('Microsoft.XMLHTTP');
        } catch (e) {
        }
    }
    return xmlHttpRequestUPSAP;
}

function xmlHttpRequestAjax(handlerPath, parameters, callbackk) {
    xmlHttpRequestUPSAP = createRequest();
    if (xmlHttpRequestUPSAP) {
        xmlHttpRequestUPSAP.open("POST", handlerPath, true);
        xmlHttpRequestUPSAP.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlHttpRequestUPSAP.onreadystatechange = function () {
            if (xmlHttpRequestUPSAP.readyState == 4) {
                if (xmlHttpRequestUPSAP.status == 200) {
                    callbackk(xmlHttpRequestUPSAP.responseText);
                }
            }
        }
        xmlHttpRequestUPSAP.send(parameters);
    }
}

function getLocale() {
    var lang = "en_US";
    if (navigator) {
        if (navigator.language) {
            lang = navigator.language;
        }
        else if (navigator.browserLanguage) {
            lang = navigator.browserLanguage;
        }
        else if (navigator.systemLanguage) {
            lang = navigator.systemLanguage;
        }
        else if (navigator.userLanguage) {
            lang = navigator.userLanguage;
        }
        if (lang.indexOf("-") == -1 && lang.indexOf("_") == -1) {
            lang = lang + "_" + lang.toUpperCase();
        }
        lang = lang.replace("-", "_");
    }

    if ("en_AT,de_AT,nl_BE,fr_BE,en_BE,en_CA,fr_CA,da_DK,en_DK,fr_FR,en_FR,de_DE,en_DE,it_IT,en_IT,es_MX,en_MX,nl_NL,en_NL,pl_PL,en_PL,es_PR,en_PR,es_ES,en_ES,sv_SE,en_SE,de_CH,fr_CH,en_CH".indexOf(lang) == -1) {
        return "";
    }

    return "&loc=" + lang;
}

function MutationObserverCheckout() {

    'use strict';

    var handlers = {
        mutationObserverCallback: function (mutations) {
            var mutationCount = mutations.length,
                i,
                currentMutation, isClearAddress = false;
            for (i = 0; i < mutationCount; i++) {
                currentMutation = mutations[i];
                if (currentMutation.type == 'childList') {
                    if(currentMutation.removedNodes.length == 0) {
                        currentMutation.addedNodes.forEach(function (noda) {
                            if (noda['innerHTML']) {
                                var elemMethod = noda.querySelectorAll('input[name="shipping_method"]'),
                                    methodLength = elemMethod.length;
                                if (methodLength > 0) {
                                    for (var i = 0; i < methodLength; i++) {
                                        elemMethod[i].checked = false;
                                        isClearAddress = true;
                                    }
                                }
                            }
                        });
                    }
                }
                if (isClearAddress === true) {
                    clearAdress();
                }
            }
        }
    }

    function initMutationObserver() {
        var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver,
            observer;

        observer = new MutationObserver(handlers.mutationObserverCallback);
        var els = document.querySelectorAll('body form div[class*="shipping-method"], ' +
            'body form div[id*="shipping-method"], ' +
            'body form div[class*="shipping_method"], ' +
            'body form div[id*="shipping_method"], ' +
            'body form li[class*="shipping-method"], ' +
            'body form li[id*="shipping-method"], ' +
            'body form li[class*="shipping_method"], ' +
            'body form li[id*="shipping_method"]'
            ),
            i;
        if (els && els.length > 0) {
            for (i = 0; i < els.length; i++) {
                observer.observe(els[i],
                    {
                        childList: true,
                        attributes: false,
                        characterData: false,
                        subtree: true,
                        attributeOldValue: false,
                        characterDataOldValue: false
                    }
                );
            }
        }
    }

    this.init = function () {
        initMutationObserver();
    }

    return true;

}
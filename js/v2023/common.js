// JavaScript Document

function selectText(containerid) {
    if (document.selection) {
        var range = document.body.createTextRange();
        range.moveToElementText(document.getElementById(containerid));
        range.select();
    } else if (window.getSelection) {
        var range = document.createRange();
        range.selectNode(document.getElementById(containerid));
        window.getSelection().addRange(range);
    }
}

function getAjax(url, params, eID, method, dataType, showLoading, onSuccess, onError, onComplete) {
    showLoading = (typeof(showLoading) === 'undefined' || showLoading === '') ? true : showLoading;
    method = (typeof(method) == 'undefined' || method == '' || (method.toUpperCase() != 'POST' && method.toUpperCase() != 'GET')) ? 'GET' : method.toUpperCase();
    dataType = (typeof(dataType) == 'undefined' || dataType == '') ? 'html' : dataType;

    if (typeof(onSuccess) == 'undefined' || onSuccess == '') {
        var _onSucess = function(data) {
            if (dataType.toLocaleLowerCase() == 'json') {
                $(eID).html(data.form);
                $('#loading-overlay').remove();
            } else {
                $(eID).html(data);
            }
        };
    } else {
        var _onSucess = onSuccess;
        //$('#loading-overlay').remove();
    }

    if (typeof(onError) == 'undefined' || onError == '') {
        var _onError = function(jqXHR, textStatus, errorThrown) {
            try {
                $(eID).html("Sorry. There was an error.");
            } catch (e) {
                alert("Sorry. There was an error.");
            }
        };
    } else {
        var _onError = onError;
    }

    if (typeof(onComplete) == 'undefined' || onComplete == '') {
        var _onComplete = function(jqXHR, textStatus) {
            if (showLoading) {
                disableAjaxLoadingPopup('#popupLoadingImg', '#popupLoadingBg');
            }
        };
    } else {
        var _onComplete = function(jqXHR, textStatus) {
            onComplete(jqXHR, textStatus);
            if (showLoading) {
                disableAjaxLoadingPopup('#popupLoadingImg', '#popupLoadingBg');
            }
        };
    }

    if (showLoading) {
        loadAjaxLoadingPopup('#popupLoadingImg', '#popupLoadingBg');
    }

    var aj = $.ajax({
        type: method,
        url: url,
        dataType: dataType,
        data: params,
        success: _onSucess,
        error: _onError,
        complete: _onComplete,
        cache: false
    });
    return aj;
}

function getQueryParam(paramName) {
    var strQuery = window.location.search.substring(1);
    var arrParam = strQuery.split("&");
    for (i = 0; i < arrParam.length; i++) {
        var paramItem = arrParam[i].split("=");
        if (paramItem[0] == paramName) {
            return paramItem[1];
        }
    }
    return '';
}


function getAllUrlQueryParam(url) {
    /*
    get all url string query param. return an object
    */
    var queryString = '';
    if (url.indexOf('?') != -1) {
        queryString = url.substring(url.indexOf('?') + 1);
    }
    var params = {},
        queries, temp, i, l;

    if (queryString != '') {
        // Split into key/value pairs
        queries = queryString.split("&");
        // Convert the array of strings into an object
        for (i = 0, l = queries.length; i < l; i++) {
            temp = queries[i].split('=');
            var tmp_len = temp.length;
            if (tmp_len == 1) {
                params[temp[0]] = '';
            } else if (tmp_len == 2) {
                params[temp[0]] = temp[1];
            } else {
                params[temp[0]] = temp[1];
                for (var m = 2; m < tmp_len; m++) {
                    params[temp[0]] += '=' + temp[m];
                }
            }
        }
    }
    return params;
}


function isIntNumber(sText) {
    var ValidChars = "0123456789";
    var IsNumber = true;
    var Char;
    for (i = 0; i < sText.length; i++) {
        Char = sText.charAt(i);
        if (ValidChars.indexOf(Char) == -1 || sText.charAt(0) == "0") {
            IsNumber = false;
            break;
        }
    }
    return IsNumber;
}

function strIsNumber(sText) {
    var ValidChars = "0123456789";
    var IsNumber = true;
    var Char;
    for (i = 0; i < sText.length; i++) {
        Char = sText.charAt(i);
        if (ValidChars.indexOf(Char) == -1) {
            IsNumber = false;
            break;
        }
    }
    return IsNumber;
}

function getNumberFromStr(sText) {
    var ValidChars = "0123456789";
    var relNumber = '';
    var Char;
    for (i = 0; i < sText.length; i++) {
        Char = sText.charAt(i);
        if (ValidChars.indexOf(Char) != -1) {
            relNumber += Char;
        }
    }
    return relNumber;
}

// arrAllowType:  array('.jpg', '.gif', '.png')
function uploadValidExtension(fileName, arrAllowType) {
    if (fileName == "") {
        return false;
    }
    fileName = fileName.toLowerCase();
    var extension = fileName.substr(fileName.lastIndexOf('.'), fileName.length);
    var check = false;
    for (var i in arrAllowType) {
        if (arrAllowType[i] == extension) {
            check = true;
            break;
        }
    }
    return check;
} // JavaScript Document

function isUrl(urlStr) {
    /*
    var pattern = new RegExp('^(http(s)?:\\/\\/)'+ // protocol
    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
    '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
    '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
    '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
    return !!pattern.test(urlStr);
    */

    if (urlStr == '' || urlStr == null) {
        return false;
    }

	// check white space in domain
	if(urlStr.indexOf('?') != -1)
	{
		var tmpArrDomain = urlStr.split('?');
		var tmpDomain = tmpArrDomain[0].toLowerCase();
		if (tmpDomain.indexOf(' ') != -1)
		{
	       return false;
	    }
	}

   	var RegexUrl=/(https|http):\/\/([a-z0-9\-._~%!$&'()*+,;=]+@)?([a-z0-9\-._~%]+|\[[a-f0-9:.]+\]|\[v[a-f0-9][a-z0-9\-._~%!$&'()*+,;=:]+\])(:[0-9]+)?(.*)/i;

  	var chk = false;
    if(RegexUrl.test(urlStr)){
		chk = true;
    }else{
		chk = false;
    }

    if(chk)
    {

	    var rex = /(https|http):\/\/w{1,}\./i;
		if(rex.test(urlStr))
		{
			var RegexUrl2 =/(https|http):\/\/(w{3,3})\./i;
		    if(RegexUrl2.test(urlStr))
			{
				var reg3 = /(https|http):\/\/(www\.){1}/i;
				if(reg3.test(urlStr))
				{
					chk = true;
				}
				else
				{
					chk = false;
				}
		    }
			else
			{
		        chk = false;
		    }
		}
		// check dot charachter
		if(urlStr.lastIndexOf('.') == -1)
		{
			chk = false;
		}

    }
    return chk;
}

var Encoder = {

    // When encoding do we convert characters into html or numerical entities
    EncodeType: "entity", // entity OR numerical

    isEmpty: function(val) {
        if (val) {
            return ((val === null) || val.length == 0 || /^\s+$/.test(val));
        } else {
            return true;
        }
    },
    arr1: new Array('&nbsp;', '&iexcl;', '&cent;', '&pound;', '&curren;', '&yen;', '&brvbar;', '&sect;', '&uml;', '&copy;', '&ordf;', '&laquo;', '&not;', '&shy;', '&reg;', '&macr;', '&deg;', '&plusmn;', '&sup2;', '&sup3;', '&acute;', '&micro;', '&para;', '&middot;', '&cedil;', '&sup1;', '&ordm;', '&raquo;', '&frac14;', '&frac12;', '&frac34;', '&iquest;', '&Agrave;', '&Aacute;', '&Acirc;', '&Atilde;', '&Auml;', '&Aring;', '&Aelig;', '&Ccedil;', '&Egrave;', '&Eacute;', '&Ecirc;', '&Euml;', '&Igrave;', '&Iacute;', '&Icirc;', '&Iuml;', '&ETH;', '&Ntilde;', '&Ograve;', '&Oacute;', '&Ocirc;', '&Otilde;', '&Ouml;', '&times;', '&Oslash;', '&Ugrave;', '&Uacute;', '&Ucirc;', '&Uuml;', '&Yacute;', '&THORN;', '&szlig;', '&agrave;', '&aacute;', '&acirc;', '&atilde;', '&auml;', '&aring;', '&aelig;', '&ccedil;', '&egrave;', '&eacute;', '&ecirc;', '&euml;', '&igrave;', '&iacute;', '&icirc;', '&iuml;', '&eth;', '&ntilde;', '&ograve;', '&oacute;', '&ocirc;', '&otilde;', '&ouml;', '&divide;', '&Oslash;', '&ugrave;', '&uacute;', '&ucirc;', '&uuml;', '&yacute;', '&thorn;', '&yuml;', '&quot;', '&amp;', '&lt;', '&gt;', '&oelig;', '&oelig;', '&scaron;', '&scaron;', '&yuml;', '&circ;', '&tilde;', '&ensp;', '&emsp;', '&thinsp;', '&zwnj;', '&zwj;', '&lrm;', '&rlm;', '&ndash;', '&mdash;', '&lsquo;', '&rsquo;', '&sbquo;', '&ldquo;', '&rdquo;', '&bdquo;', '&dagger;', '&dagger;', '&permil;', '&lsaquo;', '&rsaquo;', '&euro;', '&fnof;', '&alpha;', '&beta;', '&gamma;', '&delta;', '&epsilon;', '&zeta;', '&eta;', '&theta;', '&iota;', '&kappa;', '&lambda;', '&mu;', '&nu;', '&xi;', '&omicron;', '&pi;', '&rho;', '&sigma;', '&tau;', '&upsilon;', '&phi;', '&chi;', '&psi;', '&omega;', '&alpha;', '&beta;', '&gamma;', '&delta;', '&epsilon;', '&zeta;', '&eta;', '&theta;', '&iota;', '&kappa;', '&lambda;', '&mu;', '&nu;', '&xi;', '&omicron;', '&pi;', '&rho;', '&sigmaf;', '&sigma;', '&tau;', '&upsilon;', '&phi;', '&chi;', '&psi;', '&omega;', '&thetasym;', '&upsih;', '&piv;', '&bull;', '&hellip;', '&prime;', '&prime;', '&oline;', '&frasl;', '&weierp;', '&image;', '&real;', '&trade;', '&alefsym;', '&larr;', '&uarr;', '&rarr;', '&darr;', '&harr;', '&crarr;', '&larr;', '&uarr;', '&rarr;', '&darr;', '&harr;', '&forall;', '&part;', '&exist;', '&empty;', '&nabla;', '&isin;', '&notin;', '&ni;', '&prod;', '&sum;', '&minus;', '&lowast;', '&radic;', '&prop;', '&infin;', '&ang;', '&and;', '&or;', '&cap;', '&cup;', '&int;', '&there4;', '&sim;', '&cong;', '&asymp;', '&ne;', '&equiv;', '&le;', '&ge;', '&sub;', '&sup;', '&nsub;', '&sube;', '&supe;', '&oplus;', '&otimes;', '&perp;', '&sdot;', '&lceil;', '&rceil;', '&lfloor;', '&rfloor;', '&lang;', '&rang;', '&loz;', '&spades;', '&clubs;', '&hearts;', '&diams;'),
    arr2: new Array('&#160;', '&#161;', '&#162;', '&#163;', '&#164;', '&#165;', '&#166;', '&#167;', '&#168;', '&#169;', '&#170;', '&#171;', '&#172;', '&#173;', '&#174;', '&#175;', '&#176;', '&#177;', '&#178;', '&#179;', '&#180;', '&#181;', '&#182;', '&#183;', '&#184;', '&#185;', '&#186;', '&#187;', '&#188;', '&#189;', '&#190;', '&#191;', '&#192;', '&#193;', '&#194;', '&#195;', '&#196;', '&#197;', '&#198;', '&#199;', '&#200;', '&#201;', '&#202;', '&#203;', '&#204;', '&#205;', '&#206;', '&#207;', '&#208;', '&#209;', '&#210;', '&#211;', '&#212;', '&#213;', '&#214;', '&#215;', '&#216;', '&#217;', '&#218;', '&#219;', '&#220;', '&#221;', '&#222;', '&#223;', '&#224;', '&#225;', '&#226;', '&#227;', '&#228;', '&#229;', '&#230;', '&#231;', '&#232;', '&#233;', '&#234;', '&#235;', '&#236;', '&#237;', '&#238;', '&#239;', '&#240;', '&#241;', '&#242;', '&#243;', '&#244;', '&#245;', '&#246;', '&#247;', '&#248;', '&#249;', '&#250;', '&#251;', '&#252;', '&#253;', '&#254;', '&#255;', '&#34;', '&#38;', '&#60;', '&#62;', '&#338;', '&#339;', '&#352;', '&#353;', '&#376;', '&#710;', '&#732;', '&#8194;', '&#8195;', '&#8201;', '&#8204;', '&#8205;', '&#8206;', '&#8207;', '&#8211;', '&#8212;', '&#8216;', '&#8217;', '&#8218;', '&#8220;', '&#8221;', '&#8222;', '&#8224;', '&#8225;', '&#8240;', '&#8249;', '&#8250;', '&#8364;', '&#402;', '&#913;', '&#914;', '&#915;', '&#916;', '&#917;', '&#918;', '&#919;', '&#920;', '&#921;', '&#922;', '&#923;', '&#924;', '&#925;', '&#926;', '&#927;', '&#928;', '&#929;', '&#931;', '&#932;', '&#933;', '&#934;', '&#935;', '&#936;', '&#937;', '&#945;', '&#946;', '&#947;', '&#948;', '&#949;', '&#950;', '&#951;', '&#952;', '&#953;', '&#954;', '&#955;', '&#956;', '&#957;', '&#958;', '&#959;', '&#960;', '&#961;', '&#962;', '&#963;', '&#964;', '&#965;', '&#966;', '&#967;', '&#968;', '&#969;', '&#977;', '&#978;', '&#982;', '&#8226;', '&#8230;', '&#8242;', '&#8243;', '&#8254;', '&#8260;', '&#8472;', '&#8465;', '&#8476;', '&#8482;', '&#8501;', '&#8592;', '&#8593;', '&#8594;', '&#8595;', '&#8596;', '&#8629;', '&#8656;', '&#8657;', '&#8658;', '&#8659;', '&#8660;', '&#8704;', '&#8706;', '&#8707;', '&#8709;', '&#8711;', '&#8712;', '&#8713;', '&#8715;', '&#8719;', '&#8721;', '&#8722;', '&#8727;', '&#8730;', '&#8733;', '&#8734;', '&#8736;', '&#8743;', '&#8744;', '&#8745;', '&#8746;', '&#8747;', '&#8756;', '&#8764;', '&#8773;', '&#8776;', '&#8800;', '&#8801;', '&#8804;', '&#8805;', '&#8834;', '&#8835;', '&#8836;', '&#8838;', '&#8839;', '&#8853;', '&#8855;', '&#8869;', '&#8901;', '&#8968;', '&#8969;', '&#8970;', '&#8971;', '&#9001;', '&#9002;', '&#9674;', '&#9824;', '&#9827;', '&#9829;', '&#9830;'),

    // Convert HTML entities into numerical entities
    HTML2Numerical: function(s) {
        return this.swapArrayVals(s, this.arr1, this.arr2);
    },

    // Convert Numerical entities into HTML entities
    NumericalToHTML: function(s) {
        return this.swapArrayVals(s, this.arr2, this.arr1);
    },


    // Numerically encodes all unicode characters
    numEncode: function(s) {

        if (this.isEmpty(s)) return "";

        var e = "";
        for (var i = 0; i < s.length; i++) {
            var c = s.charAt(i);
            if (c < " " || c > "~") {
                c = "&#" + c.charCodeAt() + ";";
            }
            e += c;
        }
        return e;
    },

    // HTML Decode numerical and HTML entities back to original values
    htmlDecode: function(s) {

        var c, m, d = s;

        if (this.isEmpty(d)) return "";

        // convert HTML entites back to numerical entites first
        d = this.HTML2Numerical(d);

        // look for numerical entities &#34;
        arr = d.match(/&#[0-9]{1,5};/g);

        // if no matches found in string then skip
        if (arr != null) {
            for (var x = 0; x < arr.length; x++) {
                m = arr[x];
                c = m.substring(2, m.length - 1); //get numeric part which is refernce to unicode character
                // if its a valid number we can decode
                if (c >= -32768 && c <= 65535) {
                    // decode every single match within string
                    d = d.replace(m, String.fromCharCode(c));
                } else {
                    d = d.replace(m, ""); //invalid so replace with nada
                }
            }
        }

        return d;
    },

    // encode an input string into either numerical or HTML entities
    htmlEncode: function(s, dbl) {

        if (this.isEmpty(s)) return "";

        // do we allow double encoding? E.g will &amp; be turned into &amp;amp;
        dbl = dbl || false; //default to prevent double encoding

        // if allowing double encoding we do ampersands first
        if (dbl) {
            if (this.EncodeType == "numerical") {
                s = s.replace(/&/g, "&#38;");
            } else {
                s = s.replace(/&/g, "&amp;");
            }
        }

        // convert the xss chars to numerical entities ' " < >
        s = this.XSSEncode(s, false);

        if (this.EncodeType == "numerical" || !dbl) {
            // Now call function that will convert any HTML entities to numerical codes
            s = this.HTML2Numerical(s);
        }

        // Now encode all chars above 127 e.g unicode
        s = this.numEncode(s);

        // now we know anything that needs to be encoded has been converted to numerical entities we
        // can encode any ampersands & that are not part of encoded entities
        // to handle the fact that I need to do a negative check and handle multiple ampersands &&&
        // I am going to use a placeholder

        // if we don't want double encoded entities we ignore the & in existing entities
        if (!dbl) {
            s = s.replace(/&#/g, "##AMPHASH##");

            if (this.EncodeType == "numerical") {
                s = s.replace(/&/g, "&#38;");
            } else {
                s = s.replace(/&/g, "&amp;");
            }

            s = s.replace(/##AMPHASH##/g, "&#");
        }

        // replace any malformed entities
        s = s.replace(/&#\d*([^\d;]|$)/g, "$1");

        if (!dbl) {
            // safety check to correct any double encoded &amp;
            s = this.correctEncoding(s);
        }

        // now do we need to convert our numerical encoded string into entities
        if (this.EncodeType == "entity") {
            s = this.NumericalToHTML(s);
        }

        return s;
    },

    // Encodes the basic 4 characters used to malform HTML in XSS hacks
    XSSEncode: function(s, en) {
        if (!this.isEmpty(s)) {
            en = en || true;
            // do we convert to numerical or html entity?
            if (en) {
                s = s.replace(/\'/g, "&#39;"); //no HTML equivalent as &apos is not cross browser supported
                s = s.replace(/\"/g, "&quot;");
                s = s.replace(/</g, "&lt;");
                s = s.replace(/>/g, "&gt;");
            } else {
                s = s.replace(/\'/g, "&#39;"); //no HTML equivalent as &apos is not cross browser supported
                s = s.replace(/\"/g, "&#34;");
                s = s.replace(/</g, "&#60;");
                s = s.replace(/>/g, "&#62;");
            }
            return s;
        } else {
            return "";
        }
    },

    // returns true if a string contains html or numerical encoded entities
    hasEncoded: function(s) {
        if (/&#[0-9]{1,5};/g.test(s)) {
            return true;
        } else if (/&[A-Z]{2,6};/gi.test(s)) {
            return true;
        } else {
            return false;
        }
    },

    // will remove any unicode characters
    stripUnicode: function(s) {
        return s.replace(/[^\x20-\x7E]/g, "");

    },

    // corrects any double encoded &amp; entities e.g &amp;amp;
    correctEncoding: function(s) {
        return s.replace(/(&amp;)(amp;)+/, "$1");
    },


    // Function to loop through an array swaping each item with the value from another array e.g swap HTML entities with Numericals
    swapArrayVals: function(s, arr1, arr2) {
        if (this.isEmpty(s)) return "";
        var re;
        if (arr1 && arr2) {
            //ShowDebug("in swapArrayVals arr1.length = " + arr1.length + " arr2.length = " + arr2.length)
            // array lengths must match
            if (arr1.length == arr2.length) {
                for (var x = 0, i = arr1.length; x < i; x++) {
                    re = new RegExp(arr1[x], 'g');
                    s = s.replace(re, arr2[x]); //swap arr1 item with matching item from arr2
                }
            }
        }
        return s;
    },

    inArray: function(item, arr) {
        for (var i = 0, x = arr.length; i < x; i++) {
            if (arr[i] === item) {
                return i;
            }
        }
        return -1;
    }

};

var UrlEncode = {

    // public method for url encoding
    encode: function(string) {
        return escape(this._utf8_encode(string));
    },

    // public method for url decoding
    decode: function(string) {
        return this._utf8_decode(unescape(string));
    },

    // private method for UTF-8 encoding
    _utf8_encode: function(string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            } else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            } else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    // private method for UTF-8 decoding
    _utf8_decode: function(utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while (i < utftext.length) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            } else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            } else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }
        return string;
    }
};

var Utf8 = {

    // public method for url encoding
    encode: function(string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            } else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            } else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    // public method for url decoding
    decode: function(utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while (i < utftext.length) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            } else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            } else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }

}

function toUnicode(theString) {
    var unicodeString = '';
    for (var i = 0; i < theString.length; i++) {
        var theUnicode = theString.charCodeAt(i).toString(16).toUpperCase();
        while (theUnicode.length < 4) {
            theUnicode = '0' + theUnicode;
        }
        theUnicode = '\\u' + theUnicode;
        unicodeString += theUnicode;
    }
    return unicodeString;
}

var UTF8 = {
    encode: function(s) {
        for (var c, i = -1, l = (s = s.split("")).length, o = String.fromCharCode; ++i < l; s[i] = (c = s[i].charCodeAt(0)) >= 127 ? o(0xc0 | (c >>> 6)) + o(0x80 | (c & 0x3f)) : s[i]);
        return s.join("");
    },
    decode: function(s) {
        for (var a, b, i = -1, l = (s = s.split("")).length, o = String.fromCharCode, c = "charCodeAt"; ++i < l;
            ((a = s[i][c](0)) & 0x80) &&
            (s[i] = (a & 0xfc) == 0xc0 && ((b = s[i + 1][c](0)) & 0xc0) == 0x80 ?
                o(((a & 0x03) << 6) + (b & 0x3f)) : o(128), s[++i] = "")
        );
        return s.join("");
    }
};

function stripHtmlTags(str) {
    return str.replace(/<\/?[^>]+>/gi, '');
}

function validHtmlTags(v) {
    return (v.match(/(<+[^>]*?>)/g));
}

function chkHtmlTags(str) {
    var check = false;
    if (str.match(/<\/?[^>]+>/gi)) {
        check = true;
    }
    return check;
}

function getSelText() {
    var txt = '';
    if (window.getSelection) {
        txt = window.getSelection();
    } else if (document.getSelection) {
        txt = document.getSelection();
    } else if (document.selection) {
        txt = document.selection.createRange().text;
    }
    return txt;
}

function getDomainFromUrl(strUrl) {
    if (strUrl == '') return '';
    try {
        var temp = strUrl.split('?', 1);
        var domain = temp[0];
        domain = domain.match(/:\/\/(.[^/]+)/)[1];
        domain = domain.replace(/www./i, '');
        return domain;
    } catch (err) {
        return '';
    }
}

function validateUsername(uname) {
    let rel = true;
    uname = uname.toLowerCase();
    //var illegalChars = /\W/; // allow letters, numbers, and underscores
    let rexFilter = /^([a-z])([a-z0-9_])*/; // allow letters, numbers, and underscores and start by one letter

    if (uname == "") {
        rel = false;
    }
    //else if ((uname.length < 4) || (uname.length > 64)) {
    else if (uname.length < 4) {
        rel = false;
    } else if (!rexFilter.test(uname)) {
        console.log('fuck');
        rel = false;
    }
    return rel;
}

function validatePassword(pws) {
    let rel = true;
    let regLowerAlphabe = /([a-z]){1}/; // co it nhat 1 ky tu chu thuong
    let regUpperAlphabe = /([A-Z]){1}/; // co it nhat 1 ky tu chu hoa
    let regNumber = /([0-9]){1}/; // co it nhat 1 ky tu chu so

    if (pws == "") {
        rel = false;
    } else if (pws.length < 9) {
        rel = false;
    } else if (regLowerAlphabe.test(pws) && regUpperAlphabe.test(pws) && regNumber.test(pws)) {
        rel = true;
    }

    return rel;
}

function isEmail(email) {
    var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/;
    var illegalChars = /[\(\)\<\>\,\;\:\\\"\[\]]/;
    if (email == "") {
        return false;
    } else if (!emailFilter.test(email)) { //test email for illegal characters
        return false;
    } else if (email.match(illegalChars)) {
        return false;
    }
    return true;
}

// number format
function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function formatNumb(numb,addCommas = ',') {
    numb += '';
    let x = numb.split('.');
    let x1 = x[0];
    let x2 = x.length > 1 ? '.' + x[1] : '';
    let rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + addCommas + '$2');
    }
    return x1 + x2;
}

function roundNumber(num, dec) {
    return Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec);
}
// end number format

JSON.stringify = JSON.stringify || function(obj) {
    var t = typeof(obj);
    if (t != "object" || obj === null) {
        // simple data type
        if (t == "string") obj = '"' + obj + '"';
        return String(obj);
    } else {
        // recurse array or object
        var n, v, json = [],
            arr = (obj && obj.constructor == Array);
        for (n in obj) {
            v = obj[n];
            t = typeof(v);
            if (t == "string") v = '"' + v + '"';
            else if (t == "object" && v !== null) v = JSON.stringify(v);
            json.push((arr ? "" : '"' + n + '":') + String(v));
        }
        return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
    }
};

var ajaxLoadingPopupStatus = 0;
var ajaxLoadingPopupStatusv2 = 0;
var setupStylePopup;
var timeCount = 0;

//loading popup with jQuery magic!
function loadAjaxLoadingPopup(imgId, divBgId, isScroll) {
    isScroll = (typeof(isScroll) === 'undefined' || isScroll === '') ? true : isScroll;
    //loads popup only if it is disabled
    if (ajaxLoadingPopupStatus == 0) {
        centerAjaxLoadingPopup(imgId, divBgId, isScroll);
        $(divBgId).css({
            "opacity": "0.5"
        });
        $(divBgId).fadeIn("fast");
        $(imgId).show();
        ajaxLoadingPopupStatus = 1;

    }
}

function loadAjaxLoadingPopupv2(divBgId) {
    //loads popup only if it is disabled
    if (ajaxLoadingPopupStatusv2 == 0) {
        setupStylePopup = setInterval(function() {
            $(divBgId).css({
                opacity: "0",
            });
            $(divBgId).fadeIn("fast");
            ajaxLoadingPopupStatusv2 = 1;

            jQuery(divBgId).animate({
                backgroundColor: "red",
                opacity: "0.85",
            }, 300);
            timeCount++;

            if (timeCount == 3) {
                disableAjaxLoadingPopupv2(divBgId);
            }
        }, 1000);

    }
}

function disableAjaxLoadingPopupv2(divBgId) {
    if (ajaxLoadingPopupStatusv2 == 1) {
        $(divBgId).fadeOut();
        $(divBgId).hide();
        ajaxLoadingPopupStatusv2 = 0;
    }
    clearInterval(setupStylePopup);
}
//disabling popup with jQuery magic!
function disableAjaxLoadingPopup(imgId, divBgId) {
    //disables popup only if it is enabled
    if (ajaxLoadingPopupStatus == 1) {
        $(divBgId).fadeOut();
        $(divBgId).hide();

        ajaxLoadingPopupStatus = 0;
    }
    $(imgId).hide();
}

//centering popup
function centerAjaxLoadingPopup(imgId, divBgId, isScroll) {
    isScroll = (typeof(isScroll) === 'undefined' || isScroll === '') ? true : isScroll;
    //request data for centering
    var windowWidth = document.documentElement.clientWidth;
    var windowHeight = document.documentElement.clientHeight;
    var bodywidth = $('body').innerWidth();
    var bodyheight = $('body').innerHeight();
    var popupHeight = $(imgId).height();
    var popupWidth = $(imgId).width();

    var wpos = (bodywidth > windowWidth) ? bodywidth : windowWidth;
    var hpos = (bodyheight > windowHeight) ? bodyheight : windowHeight;
    var scrollWindow = $(window).scrollTop();
    var top = windowHeight / 2 - ((popupHeight / 3) * 2) + scrollWindow;
    var left = windowWidth / 2 - popupWidth / 2;

    //centering
    $(imgId).css({
        "position": "absolute",
        "top": top,
        "left": left
    });
    //only need force for IE6
    $(divBgId).css({
        "height": hpos,
        "width": wpos
    });
    if (isScroll) {
        $(window).scroll(function() {
            if ($(imgId).css('display') != 'none') {
                $(imgId).stop();
                var scroll = $(window).scrollTop();
                var scrollPos = windowHeight / 2 - ((popupHeight / 3) * 2) + scroll;
                $(imgId).animate({ top: scrollPos }, 'slow');
            }
        });
    }
}

function htmlspecialchars_decode(string, quote_style) {
    // http://kevin.vanzonneveld.net
    // +   original by: Mirek Slugen
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Mateusz "loonquawl" Zalega
    // +      input by: ReverseSyntax
    // +      input by: Slawomir Kaniecki
    // +      input by: Scott Cariss
    // +      input by: Francois
    // +   bugfixed by: Onno Marsman
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Ratheous
    // +      input by: Mailfaker (http://www.weedem.fr/)
    // +      reimplemented by: Brett Zamir (http://brett-zamir.me)
    // +    bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: htmlspecialchars_decode("<p>this -&gt; &quot;</p>", 'ENT_NOQUOTES');
    // *     returns 1: '<p>this -> &quot;</p>'
    // *     example 2: htmlspecialchars_decode("&amp;quot;");
    // *     returns 2: '&quot;'
    var optTemp = 0,
        i = 0,
        noquotes = false;
    if (typeof quote_style === 'undefined') {
        quote_style = 2;
    }
    string = string.toString().replace(/&lt;/g, '<').replace(/&gt;/g, '>');
    var OPTS = {
        'ENT_NOQUOTES': 0,
        'ENT_HTML_QUOTE_SINGLE': 1,
        'ENT_HTML_QUOTE_DOUBLE': 2,
        'ENT_COMPAT': 2,
        'ENT_QUOTES': 3,
        'ENT_IGNORE': 4
    };
    if (quote_style === 0) {
        noquotes = true;
    }
    if (typeof quote_style !== 'number') { // Allow for a single string or an array of string flags
        quote_style = [].concat(quote_style);
        for (i = 0; i < quote_style.length; i++) {
            // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
            if (OPTS[quote_style[i]] === 0) {
                noquotes = true;
            } else if (OPTS[quote_style[i]]) {
                optTemp = optTemp | OPTS[quote_style[i]];
            }
        }
        quote_style = optTemp;
    }
    if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
        string = string.replace(/&#0*39;/g, "'"); // PHP doesn't currently escape if more than one 0, but it should
        // string = string.replace(/&apos;|&#x0*27;/g, "'"); // This would also be useful here, but not a part of PHP
    }
    if (!noquotes) {
        string = string.replace(/&quot;/g, '"');
    }
    // Put this in last place to avoid escape being double-decoded
    string = string.replace(/&amp;/g, '&');

    return string;
}

function httooltip(e, strTipBody, width, xOffset, yOffset, posShow, pos) {
    width = (typeof(width) === 'undefined' || width === '') ? 200 : width;
    xOffset = (typeof(xOffset) === 'undefined' || xOffset === '') ? 40 : xOffset;
    yOffset = (typeof(yOffset) === 'undefined' || yOffset === '') ? 0 : yOffset;
    posShow = (typeof(posShow) === 'undefined' || posShow === '') ? 'down' : posShow;
    posShow = posShow.toLowerCase();
    pos = (typeof(pos) === 'undefined' || pos === '') ? '' : pos;
    var chk = false;

    if (pos == '') {
        chk = true;
    } else {
        var cookieChk = getCookie('capu');
        if (cookieChk == '') {
            setCookie('capu', '0', 36500, 0);
            cookieChk = 0;
        }
        cookieChk = parseInt(cookieChk, 10);
        pos = parseInt(pos, 10);
        chk = (cookieChk & pos) ? false : true;
    }
    if (chk) {
        setCookie('capu', (pos | cookieChk), 36500, 1);
        if (strTipBody != '') {
            if (posShow == 'down') {
                $('#ht-tooltip').width(width);
                $("#ht-tooltip-inner").html(strTipBody);

                var ttipW = $('#ht-tooltip').width();
                var ttipH = $('#ht-tooltip').height();

                var pos = $(e).offset();
                var eWidth = $(e).outerWidth();
                var eHeight = $(e).outerHeight();
                ttipW = eWidth;
                $('#ht-tooltip').width(ttipW);

                $("#ht-tooltip").css('z-index', '-1000001001');
                $("#ht-tooltip").show();

                var ttop = pos.top - yOffset - ttipH;
                var tleft = pos.left - xOffset;

                $("#ht-tooltip").css("top", ttop + "px");
                $("#ht-tooltip").css("left", tleft + "px");
                $("#ht-tooltip").css('z-index', '1000001001');
            } else if (posShow == 'right') {
                $('#ht-r-tooltip').width(width);
                $("#ht-r-tooltip-inner").html(strTipBody);

                var ttipW = $('#ht-r-tooltip').width();
                var ttipH = $('#ht-r-tooltip').height();

                if ((ttipW / ttipH) < 3) {
                    ttipW = ttipH * 3;
                    ttipW = (ttipW > 450) ? 450 : ttipW;
                    $('#ht-r-tooltip').width(ttipW);
                }
                var pos = $(e).offset();
                var eWidth = $(e).outerWidth();
                var eHeight = $(e).outerHeight();

                $("#ht-r-tooltip").css('z-index', '-1000001001');
                $("#ht-r-tooltip").show();

                var ttop = pos.top + yOffset;
                var tleft = pos.left + xOffset + eWidth;
                $("#ht-r-tooltip").css("top", ttop + "px");
                $("#ht-r-tooltip").css("left", tleft + "px");
                $("#ht-r-tooltip").css('z-index', '1000001001');
            }
        }
    }
}

function removeHt_Tooltip(posShow) {
    posShow = (typeof(posShow) === 'undefined' || posShow === '') ? 'down' : posShow;
    posShow = posShow.toLowerCase();
    if (posShow == 'down') {
        $("#ht-tooltip").css('display', 'none');
        $("#ht-tooltip-inner").html('');
    } else if (posShow == 'right') {
        $("#ht-r-tooltip").css('display', 'none');
        $("#ht-r-tooltip-inner").html('');
    }
}

function removeFocusToolTipImage(posShow) {
    posShow = (typeof(posShow) === 'undefined' || posShow === '') ? 'down' : posShow;
    posShow = posShow.toLowerCase();
    setTimeout('removeHt_Tooltip(\'' + posShow + '\')', 0);
}

function setCookie(c_name, value, expiredays, reset) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + expiredays);
    if (reset == 1) {
        document.cookie = c_name + "=" + escape(value) + ((expiredays == null) ? "" : ";expires=" + exdate.toUTCString()) + ";path=/";
    } else {
        var curCook = this.getCookie('cpcSelfServ');
        if (curCook.search(value) < 0 || curCook == '' || curCook == null) {
            document.cookie = c_name + "=" + escape(curCook + value) + ((expiredays == null) ? "" : ";expires=" + exdate.toUTCString()) + ";path=/";
        }
    }
}

function getCookie(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) c_end = document.cookie.length;
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return "";
}

function makeSiteUrl(strUri, fixNoSlash) {
    //fixNoSlash = (typeof(fixNoSlash) == 'undefined' || fixNoSlash == '') ? false : fixNoSlash;
    if (strUri != '') {
        var tmp = strUri.substr(0, 1);
        if (tmp == '/') {
            strUri = strUri.substr(1);
        }
    }
    var url = '';
    if (typeof(langcode) == 'undefined' || langcode == '') {
        url = strUri;
        if (typeof(uri_path) != 'undefined' && uri_path != '') {
            url = uri_path + '/' + url;
        }
    } else {
        url = langcode + '/' + strUri;
        if (typeof(uri_path) != 'undefined' && uri_path != '') {
            url = uri_path + '/' + url;
        }
    }
    return url;
}

function getSysDate() {
    var tmpCurrDate = new Date();
    var tmpSysHour = tmpCurrDate.getHours();
    if (tmpSysHour < 7) {
        tmpCurrDate.setDate(tmpCurrDate.getDate() - 1);
    }
    return new Date(tmpCurrDate.getFullYear(), tmpCurrDate.getMonth(), tmpCurrDate.getDate());
}

function isFlashEnabled() {
    var hasFlash = false;
    try {
        var fo = new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
        if (fo) hasFlash = true;
    } catch (e) {
        if (navigator.mimeTypes["application/x-shockwave-flash"] != undefined) hasFlash = true;
    }
    return hasFlash;
}




function showViewStrength(strength, strength_View) {
    var style =
        "<style type='text/css'>" +
        ".strength{ border: 1px solid gray; width: 40px; height: 10px; float: left; margin-right: 5px;}" +
        ".lv1{background-color: rgba(244, 204, 68, 0.68);}" +
        ".lv2{background-color: #f4cc44;}" +
        ".lv3{background-color: #EEBD40;}" +
        ".lv4{background-color: #ED9208;}" +
        ".lv5{background-color: #FF6600;}" +
        ".lvdf{background-color: whitesmoke;}" +
        ".color1{font-weight: bold; color: rgba(244, 204, 68, 0.68) }" +
        ".color2{font-weight: bold; color: #f4cc44 }" +
        ".color3{font-weight: bold; color: #EEBD40 }" +
        ".color4{font-weight: bold; color: #ED9208 }" +
        ".color5{font-weight: bold; color: #FF6600 }" +
        "</style>";

    $(strength_View).html('');
    var lv1 = 'df',
        lv2 = 'df',
        lv3 = 'df',
        lv4 = 'df',
        lv5 = 'df';
    var color = 1;
    var showText;
    var html = '';

    if (strength == 1) {
        showText = 'Rất Yếu';
        color = 1;
        lv1 = 1;
    } else if (strength == 2) {
        showText = 'Yếu';
        color = 2;
        lv1 = 1;
        lv2 = 2;
    } else if (strength == 3) {
        showText = 'Trung bình';
        color = 3;
        lv1 = 1;
        lv2 = 2;
        lv3 = 3;
    } else if (strength == 4) {
        showText = 'Mạnh';
        color = 4;
        lv1 = 1;
        lv2 = 2;
        lv3 = 3;
        lv4 = 4;
    } else if (strength == 5) {
        showText = 'Rất mạnh';
        color = 5;
        lv1 = 1;
        lv2 = 2;
        lv3 = 3;
        lv4 = 4;
        lv5 = 5;
    }
    html += "<div class='strength lv" + lv1 + "'></div>";
    html += "<div class='strength lv" + lv2 + "'></div>";
    html += "<div class='strength lv" + lv3 + "'></div>";
    html += "<div class='strength lv" + lv4 + "'></div>";
    html += "<div class='strength lv" + lv5 + "'></div>";

    html += '<div class="color' + color + '">' + showText + '</div>';
    html += '<div style="clear: both"></div>';

    $(strength_View).html(style + html);
}

function str_valid_phone(phone) {
    var regexp = /^[0-9]*$/g;
    phone = phone.trim();
    if ((
            (phone.length == 10 && phone.substring(0, 2) == '09') ||
            (phone.length == 11 && phone.substring(0, 2) == '01') ||
            (phone.length == 10 && phone.substring(0, 3) == '088') ||
            (phone.length == 10 && phone.substring(0, 3) == '086') ||
            (phone.length == 10 && phone.substring(0, 3) == '061') ||
            (phone.length == 10 && phone.substring(0, 3) == '089') ||
            (phone.length == 10 && phone.substring(0, 3) == '032') ||
            (phone.length == 10 && phone.substring(0, 3) == '033') ||
            (phone.length == 10 && phone.substring(0, 3) == '034') ||
            (phone.length == 10 && phone.substring(0, 3) == '035') ||
            (phone.length == 10 && phone.substring(0, 3) == '036') ||
            (phone.length == 10 && phone.substring(0, 3) == '037') ||
            (phone.length == 10 && phone.substring(0, 3) == '038') ||
            (phone.length == 10 && phone.substring(0, 3) == '039')

            ||
            (phone.length == 10 && phone.substring(0, 3) == '070') ||
            (phone.length == 10 && phone.substring(0, 3) == '076') ||
            (phone.length == 10 && phone.substring(0, 3) == '077') ||
            (phone.length == 10 && phone.substring(0, 3) == '078') ||
            (phone.length == 10 && phone.substring(0, 3) == '079')

            ||
            (phone.length == 10 && phone.substring(0, 3) == '081') ||
            (phone.length == 10 && phone.substring(0, 3) == '082') ||
            (phone.length == 10 && phone.substring(0, 3) == '083') ||
            (phone.length == 10 && phone.substring(0, 3) == '084') ||
            (phone.length == 10 && phone.substring(0, 3) == '085')

            ||
            (phone.length == 10 && phone.substring(0, 3) == '056') ||
            (phone.length == 10 && phone.substring(0, 3) == '058')

            ||
            (phone.length == 10 && phone.substring(0, 3) == '059')
            ||
            (phone.length == 11 && phone.substring(0, 2) == '84')
        ) && regexp.test(phone)) {
        return true;
    }
    return false;
}

function getCtr(click, view) {

    // check click
    if(typeof click == 'string'){
        click = click.replace(/,/gi, '');
        click = parseInt(click);
    }

    if(isNaN(click) || typeof click != "number") {
        click = 0;
    }
    
    // check view
    if (typeof view == 'string') {
        view = view.replace(/,/gi, '');
        view = parseInt(view);
    }

    if(isNaN(view) || typeof view != "number") {
        view = 0;
    }
    
    // tinh ctr
    var rel = '';
    if (view == 0) {
        rel = 'N/A';
    } else {
        rel = Math.round((click * 100) / view * 1000) / 1000;
        rel = addCommas(rel);
    }
    return rel;
}

function priceAvg(bidtype, click, view, money) {
    var rel = 'N/A';
    if(typeof click == 'string') {
        click = click.replace(/,/gi, '');
        click = parseInt(click);
    }
    click = click == 'N/A' ? 0 : click;

    if(typeof view == 'string') {
        view = view.replace(/,/gi, '');
        view = parseInt(view);
    }
    view = view == 'N/A' ? 0 : view;
    
    if(typeof money == 'string') {
        money = money.replace(/,/gi, '');
        money = parseInt(money);
    }   
    money = money == 'N/A' ? 0 : money; 
    
    if (bidtype == 1) // bid CPC
    {
        rel = click > 0 ? addCommas(Math.round(money / click)) : 'N/A';
    } else if (bidtype == 2) {
        rel = view > 0 ? addCommas(Math.round(money * 1000 / view)) : 'N/A';
    }

    return rel;
}

// lib for image lazy load
var tImageLazy = (function() {
    var cfOptions = {
        boxId: '', // string outer box id
        imgClassQuerySelector: '', // string class query selector
        customSrcAttr: '', // custom image attribute store src data
        useLoadingLazyAttr: false, // use loading lazy attribute or not
        useAjax: false // if user lib for ajax then set true
    };
    var arrImage = [];
    var isBrowserSupportLazy = false;
    var lazyloadThrottleTimeout;
    var init = function(customOpt) {
        _initOptions(customOpt);
        if (cfOptions.imgClassQuerySelector != '') {
            if ('querySelectorAll' in document) {
                if (cfOptions.boxId != '' && document.getElementById(cfOptions.boxId)) {
                    arrImage = document.getElementById(cfOptions.boxId).querySelectorAll('img.' + cfOptions.imgClassQuerySelector);
                } else {
                    arrImage = document.querySelectorAll('img.' + cfOptions.imgClassQuerySelector);
                }
            }
            // for ie browser
            else {
                var tmp = null;
                if (cfOptions.boxId != '' && document.getElementById(cfOptions.boxId)) {
                    tmp = document.getElementById(cfOptions.boxId).getElementsByTagName('img');
                    var numImg = tmp.length;
                    for (var i = 0; i < numImg; i++) {
                        if (tmp[i].getAttribute('className').indexOf(cfOptions.imgClassQuerySelector) != -1) {
                            arrImage.push(tmp[i]);
                        }
                    }
                    delete numImg;
                } else {
                    tmp = document.getElementsByTagName('img');
                    var numImg = tmp.length;
                    for (var i = 0; i < numImg; i++) {
                        if (tmp[i].getAttribute('className').indexOf(cfOptions.imgClassQuerySelector) != -1) {
                            arrImage.push(tmp[i]);
                        }
                    }
                    delete numImg;
                }
                delete tmp;
            }

            if (cfOptions.useLoadingLazyAttr) {
                isBrowserSupportLazy = _browserSupportLazy();
            }

            if (isBrowserSupportLazy) {
                if ('addEventListener' in document) {
                    if (cfOptions.useAjax) {
                        var numImg = arrImage.length;
                        for (var i = 0; i < numImg; i++) {
                            arrImage[i].setAttribute('loading', 'lazy');
                            arrImage[i].setAttribute('src', arrImage[i].getAttribute(cfOptions.customSrcAttr));
                        }
                    } else {
                        document.addEventListener("DOMContentLoaded", function() {
                            var numImg = arrImage.length;
                            for (var i = 0; i < numImg; i++) {
                                arrImage[i].setAttribute('loading', 'lazy');
                                arrImage[i].setAttribute('src', arrImage[i].getAttribute(cfOptions.customSrcAttr));
                            }
                        });
                    }
                } else {
                    if (document.readyState == 'interactive') {
                        var numImg = arrImage.length;
                        for (var i = 0; i < numImg; i++) {
                            arrImage[i].setAttribute('loading', 'lazy');
                            arrImage[i].setAttribute('src', arrImage[i].getAttribute(cfOptions.customSrcAttr));
                        }
                    }
                }

            } else {
                if ('addEventListener' in document) {
                    if (cfOptions.useAjax) {
                        _tlazyload();
                        _addHandlerEvent(window, "scroll", _tlazyload);
                        _addHandlerEvent(window, "resize", _tlazyload);
                        _addHandlerEvent(window, "orientationChange", _tlazyload);
                    } else {
                        document.addEventListener("DOMContentLoaded", function() {
                            _tlazyload();
                            _addHandlerEvent(window, "scroll", _tlazyload);
                            _addHandlerEvent(window, "resize", _tlazyload);
                            _addHandlerEvent(window, "orientationChange", _tlazyload);
                        });
                    }
                } else {
                    if (cfOptions.useAjax) {
                        _tlazyload();
                        _addHandlerEvent(window, "scroll", _tlazyload);
                        _addHandlerEvent(window, "resize", _tlazyload);
                        _addHandlerEvent(window, "orientationChange", _tlazyload);
                    } else {
                        if (document.readyState == 'interactive') {
                            _tlazyload();
                            _addHandlerEvent(window, "scroll", _tlazyload);
                            _addHandlerEvent(window, "resize", _tlazyload);
                            _addHandlerEvent(window, "orientationChange", _tlazyload);
                        }
                    }
                }

            }
        }
    };

    // private func
    var _addHandlerEvent = function(element, type, handler) {
        if (element.addEventListener) {
            element.addEventListener(type, handler, false);
        } else if (element.attachEvent) {
            element.attachEvent("on" + type, handler);
        } else {
            element["on" + type] = handler;
        }
    };

    var _removeHandlerEvent = function(element, type, handler) {
        if (element.removeEventListener) {
            element.removeEventListener(type, handler, false);
        } else if (element.detachEvent) {
            element.detachEvent("on" + type, handler);
        } else {
            element["on" + type] = null;
        }
    };

    // private func
    var _initOptions = function(customConfigOpt) {
        if (typeof Object.assign != 'function') {
            for (k in cfOptions) {
                if (k in customConfigOpt) {
                    cfOptions[k] = customConfigOpt[k];
                }
            }
            for (k in customConfigOpt) {
                if (!(k in cfOptions)) {
                    cfOptions[k] = customConfigOpt[k];
                }
            }
        } else {
            Object.assign(cfOptions, customConfigOpt);
        }
    };

    // custom lazy load for browser not support loading lazy properties
    var _tlazyload = function() {
        if (arrImage.length > 0) {
            if ("IntersectionObserver" in window) {
                var imageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            var image = entry.target;
                            image.setAttribute('src', image.getAttribute(cfOptions.customSrcAttr));
                            imageObserver.unobserve(image);
                        }
                    });
                });

                arrImage.forEach(function(imgItem) {
                    imageObserver.observe(imgItem);
                });
            } else {
                var scollOffset = 'pageYOffset' in window ? window.pageYOffset : document.documentElement.scrollTop;
                var WinHeight = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
                var numImg = arrImage.length;
                var newArr = [];
                for (var i = 0; i < numImg; i++) {
                    if (arrImage[i].getBoundingClientRect().top < (scollOffset + WinHeight)) {
                        arrImage[i].setAttribute('src', arrImage[i].getAttribute(cfOptions.customSrcAttr));
                    } else {
                        newArr.push(arrImage[i]);
                    }
                }

                if (newArr.length == 0) {
                    _removeHandlerEvent(window, "scroll", _tlazyload);
                    _removeHandlerEvent(window, "resize", _tlazyload);
                    _removeHandlerEvent(window, "orientationChange", _tlazyload);
                } else {
                    arrImage = newArr;
                    delete newArr;
                }

            }
        } else {
            _removeHandlerEvent(window, "scroll", _tlazyload);
            _removeHandlerEvent(window, "resize", _tlazyload);
            _removeHandlerEvent(window, "orientationChange", _tlazyload);
        }
    }

    // detect browser is support lazy loading hay khong
    // private func
    var _browserSupportLazy = function() {
        return (('loading' in arrImage[0]) ? true : false);
    };

    return {
        init: init
    };

})();

function show_loading(msg) {
    msg = (typeof(msg) === 'undefined' || msg === '') ? 'Xin vui lòng chờ trong giây lát...' : msg;
    if ($('body').has('div.blockUI').length == 0) {
        $.blockUI({
            css: {
                border: 'none',
                padding: '0',
                backgroundColor: '#fff',
                '-webkit-border-radius': '5px',
                '-moz-border-radius': '5px',
                'border-radius': '5px',
                'font-size': '110%',
                opacity: .65,
                color: '#000',
                'min-width': '305px',
                'max-width': '405px',
                'z-index': '1000000',
                'left': '50%',
                'top': '50%',
                'transform': 'translate(-50%, -50%)'
            },
            // styles for the overlay
            overlayCSS: {
                'z-index': '999999',
                backgroundColor: '#000',
                opacity: 0.65,
                cursor: 'wait'
            },
            message: '<div style="padding: 12px;"><i class="la la-refresh spinner"></i> ' + msg + '</div>'
        });
    }
}

function hide_loading() {
    $.unblockUI();
}

// remove all html tag from string
// require load xss.js
function removeAllHtmlTag(str)
{
	return filterXSS(str, {
		whiteList: {}, // empty, means filter out all tags
		stripIgnoreTag: true, // filter out all HTML not in the whitelist
		stripIgnoreTagBody: ["script"], // the script tag is a special case, we need
		// to filter out its content
	});
}

function getOffset(element) {
    const rect = element.getBoundingClientRect();
    return {
        left: rect.left + window.scrollX,
        top: rect.top + window.scrollY,
        height: rect.height,
    };
}

function toggleHiddenPopup(select_element) {
    select_element.classList.toggle("hidden-it-config");
}

function setSelectTitlePopup(e, label_select_element, option_select_hidden) {
    const label_element = document.querySelector(`label[for="${e.target.id}"]`).innerText;
    label_select_element.innerText = label_element;
    toggleHiddenPopup(option_select_hidden);
};

//Fix tieng viet cho Hieu
function get_banner_status_from_numb(numb_status) {
    let _status = {
        name_status: '',
        name_image: ''
    }
    switch (parseInt(numb_status)) {
        case -1:
            _status.name_status = "Hoàn thành";
            _status.name_image = 'icon-status-completed.png';
            break;

        case 0:
            _status.name_status = "Lưu trữ";
            _status.name_image = '';
            break;

        case 1:
            _status.name_status = "Đang chạy";
            _status.name_image = 'icon-status-run.png';
            break;

        case 2:
            _status.name_status = "Tạm dừng";
            _status.name_image = 'icon-status-pause.png';
            break;

        case 3:
            _status.name_status = 'Chờ duyệt';
            _status.name_image = 'icon-status-wait-review.png';
            break;

        case 4:
            _status.name_status = 'Có lỗi';
            _status.name_image = 'icon-status-error.png';
            break;

        case 5:
            _status.name_status = 'Hết ngân sách ngày';
            _status.name_image = 'icon-status-out-of-money-day.png';
            break;

        case 6:
            _status.name_status = 'Hết ngân sách';
            _status.name_image = 'icon-status-out-of-money.png';
            break;

        case 7:
            _status.name_status = 'Đã hạ deal';
            _status.name_image = '';
            break;

        case 8:
            _status.name_status = 'Chờ chạy';
            _status.name_image = 'icon-status-wait-to-run.png';
            break;
    }

    return _status;
}


/**
 * Dung de lay image tuong ung voi status2 (status2 la trang thai phu cua banner)
 * 
 */
 function get_banner_status2_from_numb(numb_status) {
    let status = {
        'name_status_en': '',
        'name_status_vn': '',
        'name_image': ''
    };

    switch (numb_status) 
    {
        case 1:
            status['name_status_vn'] = 'CTR thấp';
            status['name_status_en']  = '';
            status['name_image']  = 'icon-status-low-ctr.png';
            break;

        case 2:
            status['name_status_vn'] = 'Không đủ tiền';
            status['name_status_en']  = '';
            status['name_image']  = 'ison-status-khongdutien.png';
            break;
        case 3:
            status['name_status_vn'] = 'Tạo mới quảng cáo từ Api muachung copy ads';
            status['name_status_en']  = '';
            status['name_image']  = 'icon-status-api.png';
            break;
        case 4:
            status['name_status_vn'] = 'Api muachung update status';
            status['name_status_en']  = '';
            status['name_image']  = 'icon-status-api.png';
            break;
        case 5:
            status['name_status_vn'] = 'Api muachung copy ads pause banner';
            status['name_status_en']  = '';
            status['name_image']  = 'icon-status-api.png';
            break;
        case 6:
            status['name_status_vn'] = 'Api update deal box mua chung';
            status['name_status_en']  = '';
            status['name_image']  = 'icon-status-api.png';
            break;
        case 7:
            status['name_status_vn'] = 'Dừng quảng cáo do CTR thấp từ weekly cronjob';
            status['name_status_en']  = '';
            status['name_image']  = 'icon-status-api.png';
            break;
        case 8:
            status['name_status_vn'] = 'Api update deal qua service';
            status['name_status_en']  = '';
            status['name_image']  = 'icon-status-api.png';
            break;
        case 9:
            status['name_status_vn'] = 'CTR thấp';
            status['name_status_en']  = '';
            status['name_image']  = 'icon-status-api.png';
            break;
        case 10:
            status['name_status_vn'] = 'Api update deal qua service';
            status['name_status_en']  = '';
            status['name_image']  = 'icon-status-api.png';
            break;
    }

    return  status;
}



function von_suser(url, uname)
{
    var u = $.trim(uname);
    if(u == '')
    {
        //alert('switch_user_err');
        $.showNotification({
        body: "Bạn hãy nhập tên tài khoản muốn chuyển đổi.", 
        type: "danger",
        isConfirm: false
        });
        return;
    }

    show_loading();

    $.ajax({
        url : url,
        type : 'POST',
        data : {u:u},
        success : function(res){
            var objData = jQuery.parseJSON(res);
            if(objData.r == '1')
            {
                adxAutoRefreshSet('1');// set refresh tab
                window.location = objData.url;
                return;
            }
            else
            {
                if(objData.r == '0')
                {
                    adxAutoRefreshSet('1');// set refresh tab
                    window.location.reload(true);
                    return;
                }
                if(objData.r == '-3')
                {
                    // alert('switch_user_not_found');
                    $.showNotification({
                        body: "Tài khoản không tồn tại hoặc bị khóa hoặc chưa kích hoạt.", 
                        type: "danger",
                        isConfirm: false
                    });
                    hide_loading();
                    return;
                }
                else if(objData.r == '-1' || objData.r == '-2')
                {
                   // alert('switch_user_not_right');
                    $.showNotification({
                    body: "Bạn không có quyền chuyển đổi sang tài khoản này.", 
                    type: "danger",
                    isConfirm: false
                });
                    hide_loading();
                    return;
                }
            }
        },
        error: function(){
            hide_loading();
        }
    });
}


function suser(url, uname)
{
    var u = $.trim(uname);
    if(u == '')
    {
        // alert('switch_user_err');
        $.showNotification({
        body: "Bạn hãy nhập tên tài khoản muốn chuyển đổi.", 
        type: "danger",
        isConfirm: false
        });
        return;
    }
    show_loading();
    $.ajax({
        url : url,
        type : 'POST',
        data : {u:u},
        success : function(res){
            var objData = jQuery.parseJSON(res);
            if(objData.r == '1')
            {
                adxAutoRefreshSet('1');// set refresh tab
                window.location = objData.url;
                return;
            }
            else
            {
                if(objData.r == '0')
                {
                    adxAutoRefreshSet('1');// set refresh tab
                    window.location.reload(true);
                    return;
                }
                if(objData.r == '-3')
                {
                    //alert('switch_user_not_found');
                    $.showNotification({
                    body: "Tài khoản không tồn tại hoặc bị khóa hoặc chưa kích hoạt.", 
                    type: "danger",
                    isConfirm: false
                    });
                    hide_loading();
                    return;
                }
                else if(objData.r == '-1' || objData.r == '-2')
                {
                    //alert('switch_user_not_right');
                    $.showNotification({
                    body: "Bạn không có quyền chuyển đổi sang tài khoản này.", 
                    type: "danger",
                    isConfirm: false
                    });
                    hide_loading();
                    return;
                }
            }
        },
        error: function(){
            hide_loading();
        }
    });
}

function removeVietnameseTones(str) {
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
    str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
    str = str.replace(/đ/g, "d");
    str = str.replace(/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/g, "A");
    str = str.replace(/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/g, "E");
    str = str.replace(/Ì|Í|Ị|Ỉ|Ĩ/g, "I");
    str = str.replace(/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/g, "O");
    str = str.replace(/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/g, "U");
    str = str.replace(/Ỳ|Ý|Ỵ|Ỷ|Ỹ/g, "Y");
    str = str.replace(/Đ/g, "D");
    // Some system encode vietnamese combining accent as individual utf-8 characters
    str = str.replace(/\u0300|\u0301|\u0303|\u0309|\u0323/g, ""); // ̀ ́ ̃ ̉ ̣  huyen, sac, nga, hoi, nang
    str = str.replace(/\u02C6|\u0306|\u031B/g, ""); // ˆ ̆ ̛  Â, Ê, Ă, Ơ, Ư
    // Remove extra spaces
    str = str.replace(/ + /g, " ");
    str = str.trim();
    // Remove punctuations
    str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'|\"|\&|\#|\[|\]|~|\$|_|`|-|{|}|\||\\/g, " ");
    return str;
}

function isEmpty(obj) {
    if (obj == null) return true;
    if (obj.length > 0) return false;
    if (obj.length === 0) return true;
    if (typeof obj !== "object") return true;
    for (var key in obj) {
        if (hasOwnProperty.call(obj, key)) return false;
    }
    return true;
}

function formatDate(date, format = 'y-m-d') {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2)
        month = '0' + month;
    if (day.length < 2)
        day = '0' + day;

    if(format == 'y-m-d'){
        return [year, month, day].join('-');
    }else{
        return [day, month, year].join('-');
    }
}


function adxAutoRefreshSet(val)
{
	if (typeof(Storage) !== "undefined") 
	{
		window.localStorage.setItem('adxf5', val);
    }
}

function is_url_banner(urlStr) {
    if (urlStr == '' || urlStr == null) {
        return false;
    }

    // check white space in domain
    if(urlStr.indexOf('?') != -1)
    {
        var tmpArrDomain = urlStr.split('?');
        var tmpDomain = tmpArrDomain[0].toLowerCase();
        if (tmpDomain.indexOf(' ') != -1)
        {
        return false;
        }
    }

    var RegexUrl=/(https|http):\/\/([a-z0-9\-._~%!$&'()*+,;=]+@)?([a-z0-9\-._~%]+|\[[a-f0-9:.]+\]|\[v[a-f0-9][a-z0-9\-._~%!$&'()*+,;=:]+\])(:[0-9]+)?(.*)/i;

    var chk = false;
    if(RegexUrl.test(urlStr)){
        chk = true;
    }else{
        chk = false;
    }

    if(chk)
    {

        var rex = /(https|http):\/\/w{1,}\./i;
        if(rex.test(urlStr))
        {
            var RegexUrl2 =/(https|http):\/\/(w{3,3})\./i;
            if(RegexUrl2.test(urlStr))
            {
                var reg3 = /(https|http):\/\/(www\.){1}/i;
                if(reg3.test(urlStr))
                {
                    chk = true;
                }
                else
                {
                    chk = false;
                }
            }
            else
            {
                chk = false;
            }
        }
        // check dot charachter
        if(urlStr.lastIndexOf('.') == -1)
        {
            chk = false;
        }

    }
    return chk;
}

function notice_error(title){
    $.alert({
        theme: 'bootstrap',
        closeIcon: true,
        title: 'Thông báo lỗi',
        icon: 'fa fa-warning',
        type: 'red',
        animation: 'none',
        content: title,
        buttons: {
            Đóng: {
                btnClass: 'btn-danger btn-confirm'
            }        
        }
    });
}

function notice_success(title, isReload){
    isReload = (typeof(isReload) == 'undefined' || isReload == "") ? false : isReload;
    $.alert({
        theme: 'bootstrap',
        closeIcon: true,
        title: 'Thông báo thành công',
        icon: 'fa fa-check-circle',
        type: 'green',
        animation: 'none',
        content: title,
        buttons: {
            Đóng: {
                btnClass: 'btn-success btn-confirm',
                action: function () {
                    if(isReload){
                        window.location.reload(true);
                    }
                }
            }


        }
    });
}
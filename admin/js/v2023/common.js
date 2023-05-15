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
    if (urlStr == '' || urlStr == null) {
        return false;
    }

    // check white space in domain
    if (urlStr.indexOf('?') != -1) {
        var tmpArrDomain = urlStr.split('?');
        var tmpDomain = tmpArrDomain[0].toLowerCase();
        if (tmpDomain.indexOf(' ') != -1) {
            return false;
        }
    }

    var RegexUrl = /(https|http):\/\/([a-z0-9\-._~%!$&'()*+,;=]+@)?([a-z0-9\-._~%]+|\[[a-f0-9:.]+\]|\[v[a-f0-9][a-z0-9\-._~%!$&'()*+,;=:]+\])(:[0-9]+)?(.*)/i;

    var chk = false;
    if (RegexUrl.test(urlStr)) {
        chk = true;
    } else {
        chk = false;
    }

    if (chk) {

        var rex = /(https|http):\/\/w{1,}\./i;
        if (rex.test(urlStr)) {
            var RegexUrl2 = /(https|http):\/\/(w{3,3})\./i;
            if (RegexUrl2.test(urlStr)) {
                var reg3 = /(https|http):\/\/(www\.){1}/i;
                if (reg3.test(urlStr)) {
                    chk = true;
                }
                else {
                    chk = false;
                }
            }
            else {
                chk = false;
            }
        }
        // check dot charachter
        if (urlStr.lastIndexOf('.') == -1) {
            chk = false;
        }

    }
    return chk;
}

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

function formatNumb(numb, addCommas = ',') {
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

// remove all html tag from string
// require load xss.js
function removeAllHtmlTag(str) {
    return filterXSS(str, {
        whiteList: {}, // empty, means filter out all tags
        stripIgnoreTag: true, // filter out all HTML not in the whitelist
        stripIgnoreTagBody: ["script"], // the script tag is a special case, we need
        // to filter out its content
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
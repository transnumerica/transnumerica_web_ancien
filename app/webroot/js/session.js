//Script facebook
/*
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.3&appId=510628122390474";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
*/

var filterFloat = function (value) {

    try {
        value = value.replaceAll(" ", "");
    } catch (e) {
    }

    //console.log(parseInt(value));
    if (/^(\-|\+)?([0-9]+(\.[0-9]+)?|Infinity)$/
      .test(value)){
      return Number(value);
    }
    if (parseFloat(value)){
      return parseFloat(value);
    }

  return undefined;
}

function formatMoney(amount, decimalCount = 2, thousands = ",", decimal = ".") {
  try {
    decimalCount = Math.abs(decimalCount);
    decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

    const negativeSign = amount < 0 ? "-" : "";

    let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
    let j = (i.length > 3) ? i.length % 3 : 0;

    return negativeSign +
      (j ? i.substr(0, j) + thousands : '') +
      i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) +
      (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
  } catch (e) {
    console.log(e)
  }
};



function md5cycle(x, k) {
var a = x[0], b = x[1], c = x[2], d = x[3];

a = ff(a, b, c, d, k[0], 7, -680876936);
d = ff(d, a, b, c, k[1], 12, -389564586);
c = ff(c, d, a, b, k[2], 17,  606105819);
b = ff(b, c, d, a, k[3], 22, -1044525330);
a = ff(a, b, c, d, k[4], 7, -176418897);
d = ff(d, a, b, c, k[5], 12,  1200080426);
c = ff(c, d, a, b, k[6], 17, -1473231341);
b = ff(b, c, d, a, k[7], 22, -45705983);
a = ff(a, b, c, d, k[8], 7,  1770035416);
d = ff(d, a, b, c, k[9], 12, -1958414417);
c = ff(c, d, a, b, k[10], 17, -42063);
b = ff(b, c, d, a, k[11], 22, -1990404162);
a = ff(a, b, c, d, k[12], 7,  1804603682);
d = ff(d, a, b, c, k[13], 12, -40341101);
c = ff(c, d, a, b, k[14], 17, -1502002290);
b = ff(b, c, d, a, k[15], 22,  1236535329);

a = gg(a, b, c, d, k[1], 5, -165796510);
d = gg(d, a, b, c, k[6], 9, -1069501632);
c = gg(c, d, a, b, k[11], 14,  643717713);
b = gg(b, c, d, a, k[0], 20, -373897302);
a = gg(a, b, c, d, k[5], 5, -701558691);
d = gg(d, a, b, c, k[10], 9,  38016083);
c = gg(c, d, a, b, k[15], 14, -660478335);
b = gg(b, c, d, a, k[4], 20, -405537848);
a = gg(a, b, c, d, k[9], 5,  568446438);
d = gg(d, a, b, c, k[14], 9, -1019803690);
c = gg(c, d, a, b, k[3], 14, -187363961);
b = gg(b, c, d, a, k[8], 20,  1163531501);
a = gg(a, b, c, d, k[13], 5, -1444681467);
d = gg(d, a, b, c, k[2], 9, -51403784);
c = gg(c, d, a, b, k[7], 14,  1735328473);
b = gg(b, c, d, a, k[12], 20, -1926607734);

a = hh(a, b, c, d, k[5], 4, -378558);
d = hh(d, a, b, c, k[8], 11, -2022574463);
c = hh(c, d, a, b, k[11], 16,  1839030562);
b = hh(b, c, d, a, k[14], 23, -35309556);
a = hh(a, b, c, d, k[1], 4, -1530992060);
d = hh(d, a, b, c, k[4], 11,  1272893353);
c = hh(c, d, a, b, k[7], 16, -155497632);
b = hh(b, c, d, a, k[10], 23, -1094730640);
a = hh(a, b, c, d, k[13], 4,  681279174);
d = hh(d, a, b, c, k[0], 11, -358537222);
c = hh(c, d, a, b, k[3], 16, -722521979);
b = hh(b, c, d, a, k[6], 23,  76029189);
a = hh(a, b, c, d, k[9], 4, -640364487);
d = hh(d, a, b, c, k[12], 11, -421815835);
c = hh(c, d, a, b, k[15], 16,  530742520);
b = hh(b, c, d, a, k[2], 23, -995338651);

a = ii(a, b, c, d, k[0], 6, -198630844);
d = ii(d, a, b, c, k[7], 10,  1126891415);
c = ii(c, d, a, b, k[14], 15, -1416354905);
b = ii(b, c, d, a, k[5], 21, -57434055);
a = ii(a, b, c, d, k[12], 6,  1700485571);
d = ii(d, a, b, c, k[3], 10, -1894986606);
c = ii(c, d, a, b, k[10], 15, -1051523);
b = ii(b, c, d, a, k[1], 21, -2054922799);
a = ii(a, b, c, d, k[8], 6,  1873313359);
d = ii(d, a, b, c, k[15], 10, -30611744);
c = ii(c, d, a, b, k[6], 15, -1560198380);
b = ii(b, c, d, a, k[13], 21,  1309151649);
a = ii(a, b, c, d, k[4], 6, -145523070);
d = ii(d, a, b, c, k[11], 10, -1120210379);
c = ii(c, d, a, b, k[2], 15,  718787259);
b = ii(b, c, d, a, k[9], 21, -343485551);

x[0] = add32(a, x[0]);
x[1] = add32(b, x[1]);
x[2] = add32(c, x[2]);
x[3] = add32(d, x[3]);

}

function cmn(q, a, b, x, s, t) {
a = add32(add32(a, q), add32(x, t));
return add32((a << s) | (a >>> (32 - s)), b);
}

function ff(a, b, c, d, x, s, t) {
return cmn((b & c) | ((~b) & d), a, b, x, s, t);
}

function gg(a, b, c, d, x, s, t) {
return cmn((b & d) | (c & (~d)), a, b, x, s, t);
}

function hh(a, b, c, d, x, s, t) {
return cmn(b ^ c ^ d, a, b, x, s, t);
}

function ii(a, b, c, d, x, s, t) {
return cmn(c ^ (b | (~d)), a, b, x, s, t);
}

function md51(s) {
txt = '';
var n = s.length,
state = [1732584193, -271733879, -1732584194, 271733878], i;
for (i=64; i<=s.length; i+=64) {
md5cycle(state, md5blk(s.substring(i-64, i)));
}
s = s.substring(i-64);
var tail = [0,0,0,0, 0,0,0,0, 0,0,0,0, 0,0,0,0];
for (i=0; i<s.length; i++)
tail[i>>2] |= s.charCodeAt(i) << ((i%4) << 3);
tail[i>>2] |= 0x80 << ((i%4) << 3);
if (i > 55) {
md5cycle(state, tail);
for (i=0; i<16; i++) tail[i] = 0;
}
tail[14] = n*8;
md5cycle(state, tail);
return state;
}

/* there needs to be support for Unicode here,
 * unless we pretend that we can redefine the MD-5
 * algorithm for multi-byte characters (perhaps
 * by adding every four 16-bit characters and
 * shortening the sum to 32 bits). Otherwise
 * I suggest performing MD-5 as if every character
 * was two bytes--e.g., 0040 0025 = @%--but then
 * how will an ordinary MD-5 sum be matched?
 * There is no way to standardize text to something
 * like UTF-8 before transformation; speed cost is
 * utterly prohibitive. The JavaScript standard
 * itself needs to look at this: it should start
 * providing access to strings as preformed UTF-8
 * 8-bit unsigned value arrays.
 */
function md5blk(s) { /* I figured global was faster.   */
var md5blks = [], i; /* Andy King said do it this way. */
for (i=0; i<64; i+=4) {
md5blks[i>>2] = s.charCodeAt(i)
+ (s.charCodeAt(i+1) << 8)
+ (s.charCodeAt(i+2) << 16)
+ (s.charCodeAt(i+3) << 24);
}
return md5blks;
}

var hex_chr = '0123456789abcdef'.split('');

function rhex(n)
{
var s='', j=0;
for(; j<4; j++)
s += hex_chr[(n >> (j * 8 + 4)) & 0x0F]
+ hex_chr[(n >> (j * 8)) & 0x0F];
return s;
}

function hex(x) {
for (var i=0; i<x.length; i++)
x[i] = rhex(x[i]);
return x.join('');
}

function md5(s) {
return hex(md51(s));
}

/* this function is much faster,
so if possible we use it. Some IEs
are the only ones I know of that
need the idiotic second function,
generated by an if clause.  */

function add32(a, b) {
return (a + b) & 0xFFFFFFFF;
}

if (md5('hello') != '5d41402abc4b2a76b9719d911017c592') {
function add32(x, y) {
var lsw = (x & 0xFFFF) + (y & 0xFFFF),
msw = (x >> 16) + (y >> 16) + (lsw >> 16);
return (msw << 16) | (lsw & 0xFFFF);
}
}








var ValidationIsSubmit = false;
var lastFormThis;
var lastButtonSubmitThis;
var lastButtonSubmitTextThis;

var SuButtonConfig = new Array();

$("button").live('click', function (e) {

    if(this.hasAttribute("type") && this.getAttribute("type") == 'submit'){

        lastButtonSubmitThis = this;
        lastButtonSubmitTextThis = this.innerHTML;

        desprice = this;
        destext = this.innerHTML;

        //e.preventDefault();

        setTimeout(function(){

            if (ValidationIsSubmit) {
                desprice.innerHTML = desprice.innerHTML + ' <i class="fas fa-circle-notch fa-spin"></i>';
                desprice.disabled = true;
                $(desprice.parentNode).children('button').attr('disabled', 'disabled');
            }

            ValidationIsSubmit = false;

        }, 1);



        setTimeout(function(){
            desprice.innerHTML = destext;
            desprice.disabled = false;
            $(desprice.parentNode).children('button').removeAttr('disabled');


        }, 600000);

        return true;

    }

});

//$("form").submit(function(e){
$("form").live('submit', function (e) {
    lastFormThis = this;
    ValidationIsSubmit = true;
});



  $(document).ready(function(){
    $(document).ajaxStart(function(evt){
        //var id = evt.target.activeElement.id.toString();
        //console.log(id);
    });
    $(document).ajaxSend(function(evt, request, settings) {

        if (settings.data) {

            setTimeout(function(){

                md5B = md5(settings.data);

                if(SuButtonConfig[md5B] == undefined) {
                    SuButtonConfig[md5B] = new Array();
                }

                SuButtonConfig[md5B].lastFormThis = lastFormThis;
                SuButtonConfig[md5B].lastButtonSubmitThis = lastButtonSubmitThis;
                SuButtonConfig[md5B].lastButtonSubmitTextThis = lastButtonSubmitTextThis;

            }, 1);

        }

    });
    $(document).ajaxComplete(function(evt, request, settings){

        if (settings.data) {

            md5B = md5(settings.data);

            if(SuButtonConfig[md5B]) {

                SuButtonConfig[md5B].lastButtonSubmitThis.innerHTML = SuButtonConfig[md5B].lastButtonSubmitTextThis;
                SuButtonConfig[md5B].lastButtonSubmitThis.disabled = false;
                $(SuButtonConfig[md5B].lastButtonSubmitThis.parentNode).children('button').removeAttr('disabled');

                SuButtonConfig[md5B] = undefined;

            }
        //console.log(evt);
        }

    });
    $(document).click(function(){
        //alert('ste');
    });
  });



















// Url de base
function webase() {
    var url = window.location.href
    var arr = url.split("/");
    if (arr[2] == 'localhost') {
    var result = arr[0] + "//" + arr[2]  + "/"  + arr[3];
    }else{
    var result = arr[0] + "//" + arr[2];
    }

    return result;
}

// Url de base

function getBaseURL() {
    var url = location.href;  // entire url including querystring - also: window.location.href;
    var baseURL = url.substring(0, url.indexOf('/', 14));


    if (baseURL.indexOf('http://localhost') != -1) {
        // Base Url for localhost
        var url = location.href;  // window.location.href;
        var pathname = location.pathname;  // window.location.pathname;
        var index1 = url.indexOf(pathname);
        var index2 = url.indexOf("/", index1 + 1);
        var baseLocalUrl = url.substr(0, index2);

        return baseLocalUrl + "/";
    }
    else {
        // Root Url for domain name
        return baseURL + "/";
    }

}



// On demare notre script principal
$(start);
$(document).ajaxComplete(start);

function start() {

    $("#flash-notif").animate({opacity:0.95},1500).slideUp(3200);

    // On charge les attribue "Chosen" des selects
    if (document.getElementsByClassName("chosen-select").length !== 0) {
        $('.chosen-select').chosen();
    }

    // On scroll l'ecran directement vers l'affichage des erreurs generalement après un POST
    if ($('.error-message').length > 0) {
        $('.error-message').ready(function(){
            var $container = $("html,body");
            var $scrollTo = $('.error, .error-message');

            $container.animate({scrollTop: $scrollTo.offset().top - $container.offset().top - $('#header').height()-200, scrollLeft: 0},300); 
        });
    }




    /*    
    $('a[href^="#"]').click(function(){
        var the_id = $(this).attr("href");

        if(the_id != "#") {
            $('html, body').animate({
                scrollTop:$(the_id).offset().top
            }, 'slow');
            return false;
        }
    });
    */
    
}

// Sumission formulaire
var loaded = false;
var timeoutCount = 0;
function formsubmit(id,contenu) {

    if(loaded) return;

    $.ajax({
        async:true,
        //cache: false,
        beforeSend:function (XMLHttpRequest) {loaded = true; $("#chargement").fadeIn();},
        complete:function (XMLHttpRequest, textStatus) {loaded = false; $("#chargement").fadeOut();},
        timeout:32000,
        url: location,
        method: 'POST',
        data : $(id).serialize(),
        success: function(data) {
            loaded = false;
            $(contenu).html(data);
            $("#chargement").fadeOut();
        },
        error: function(data, textStatus) {
            $("#chargement").fadeOut();
            loaded = false;
            if (textStatus != 'timeout' || timeoutCount == 2) {
                timeoutCount == 0;
                alert("Le formulaire n'a pas pu etre soumis, veuillez réessayer");
            }
            if (textStatus == 'timeout' && timeoutCount <2) {
                timeoutCount = timeoutCount +1;  formsubmit(id,contenu);
            }
        }
    });

    return false;

}

//Afficher & Masquer
function show(bouton, id) { // On déclare la fonction toggle_div qui prend en param le bouton et un id
    var div = document.getElementById(id); // On récupère le div ciblé grâce à l'id
    var bouton = $(bouton);

    if(!isRendered(div)) { // Si le div est masqué...
        div.style.display = "block"; // ... on l'affiche...
        bouton.html("-"); // ... et on change le contenu du bouton.
    } else { // S'il est visible...
        div.style.display = "none"; // ... on le masque...
        bouton.html("+"); // ... et on change le contenu du bouton.
    }
}

$(document).ready(function(){
    $(".delete").bind("click",function(event){
        var _confirm=confirm("Etes-vous sûr de vouloir supprimer?");
        if(!_confirm){
            return false;
        }

    })
});

function isRendered(domObj) {
    if ((domObj.nodeType != 1) || (domObj == document.body)) {
        return true;
    }
    if (domObj.currentStyle && domObj.currentStyle["display"] != "none" && domObj.currentStyle["visibility"] != "hidden") {
        return isRendered(domObj.parentNode);
    } else if (window.getComputedStyle) {
        var cs = document.defaultView.getComputedStyle(domObj, null);
        if (cs.getPropertyValue("display") != "none" && cs.getPropertyValue("visibility") != "hidden") {
            return isRendered(domObj.parentNode);
        }
    }
    return false;
}

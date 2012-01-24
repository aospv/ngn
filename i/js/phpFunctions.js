/**
 * http://javascript.ru/php
 */

function sprintf( ) { // Return a formatted string
  // 
  // +   original by: Ash Searle (http://hexmen.com/blog/)
  // + namespaced by: Michael White (http://crestidg.com)

  var regex = /%%|%(\d+\$)?([-+#0 ]*)(\*\d+\$|\*|\d+)?(\.(\*\d+\$|\*|\d+))?([scboxXuidfegEG])/g;
  var a = arguments, i = 0, format = a[i++];

  // pad()
  var pad = function(str, len, chr, leftJustify) {
    var padding = (str.length >= len) ? '' : Array(1 + len - str.length >>> 0).join(chr);
    return leftJustify ? str + padding : padding + str;
  };

  // justify()
  var justify = function(value, prefix, leftJustify, minWidth, zeroPad) {
  var diff = minWidth - value.length;
  if (diff > 0) {
    if (leftJustify || !zeroPad) {
      value = pad(value, minWidth, ' ', leftJustify);
    } else {
      value = value.slice(0, prefix.length) + pad('', diff, '0', true) + value.slice(prefix.length);
    }
  }
  return value;
  };

  // formatBaseX()
  var formatBaseX = function(value, base, prefix, leftJustify, minWidth, precision, zeroPad) {
    // Note: casts negative numbers to positive ones
    var number = value >>> 0;
    prefix = prefix && number && {'2': '0b', '8': '0', '16': '0x'}[base] || '';
    value = prefix + pad(number.toString(base), precision || 0, '0', false);
    return justify(value, prefix, leftJustify, minWidth, zeroPad);
  };

  // formatString()
  var formatString = function(value, leftJustify, minWidth, precision, zeroPad) {
    if (precision != null) {
      value = value.slice(0, precision);
    }
    return justify(value, '', leftJustify, minWidth, zeroPad);
  };

  // finalFormat()
  var doFormat = function(substring, valueIndex, flags, minWidth, _, precision, type) {
  if (substring == '%%') return '%';

  // parse flags
  var leftJustify = false, positivePrefix = '', zeroPad = false, prefixBaseX = false;
  for (var j = 0; flags && j < flags.length; j++) switch (flags.charAt(j)) {
    case ' ': positivePrefix = ' '; break;
    case '+': positivePrefix = '+'; break;
    case '-': leftJustify = true; break;
    case '0': zeroPad = true; break;
    case '#': prefixBaseX = true; break;
  }

  // parameters may be null, undefined, empty-string or real valued
  // we want to ignore null, undefined and empty-string values
  if (!minWidth) {
    minWidth = 0;
  } else if (minWidth == '*') {
    minWidth = +a[i++];
  } else if (minWidth.charAt(0) == '*') {
    minWidth = +a[minWidth.slice(1, -1)];
  } else {
    minWidth = +minWidth;
  }

  // Note: undocumented perl feature:
  if (minWidth < 0) {
    minWidth = -minWidth;
    leftJustify = true;
  }

  if (!isFinite(minWidth)) {
    throw new Error('sprintf: (minimum-)width must be finite');
  }

  if (!precision) {
    precision = 'fFeE'.indexOf(type) > -1 ? 6 : (type == 'd') ? 0 : void(0);
  } else if (precision == '*') {
    precision = +a[i++];
  } else if (precision.charAt(0) == '*') {
    precision = +a[precision.slice(1, -1)];
  } else {
    precision = +precision;
  }

  // grab value using valueIndex if required?
  var value = valueIndex ? a[valueIndex.slice(0, -1)] : a[i++];

  switch (type) {
    case 's': return formatString(String(value), leftJustify, minWidth, precision, zeroPad);
    case 'c': return formatString(String.fromCharCode(+value), leftJustify, minWidth, precision, zeroPad);
    case 'b': return formatBaseX(value, 2, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
    case 'o': return formatBaseX(value, 8, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
    case 'x': return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
    case 'X': return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad).toUpperCase();
    case 'u': return formatBaseX(value, 10, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
    case 'i':
    case 'd': {
      var number = parseInt(+value);
      var prefix = number < 0 ? '-' : positivePrefix;
      value = prefix + pad(String(Math.abs(number)), precision, '0', false);
      return justify(value, prefix, leftJustify, minWidth, zeroPad);
      }
    case 'e':
    case 'E':
    case 'f':
    case 'F':
    case 'g':
    case 'G':
      {
      var number = +value;
      var prefix = number < 0 ? '-' : positivePrefix;
      var method = ['toExponential', 'toFixed', 'toPrecision']['efg'.indexOf(type.toLowerCase())];
      var textTransform = ['toString', 'toUpperCase']['eEfFgG'.indexOf(type) % 2];
      value = prefix + Math.abs(number)[method](precision);
      return justify(value, prefix, leftJustify, minWidth, zeroPad)[textTransform]();
      }
    default: return substring;
  }
  };

  return format.replace(regex, doFormat);
}

function htmlentities(s){   // Convert all applicable characters to HTML entities
   // 
   // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
 
   var div = document.createElement('div');
   var text = document.createTextNode(s);
   div.appendChild(text);
   return div.innerHTML;
}

function http_build_query( formdata, numeric_prefix, arg_separator ) {  // Generate URL-encoded query string
  // 
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Legaev Andrey
  // +   improved by: Michael White (http://crestidg.com)
 
  var key, use_val, use_key, i = 0, tmp_arr = [];
 
  if(!arg_separator){
    arg_separator = '&';
  }
 
  for(key in formdata){
    use_key = escape(key);
    use_val = escape((formdata[key].toString()));
    use_val = use_val.replace(/%20/g, '+');
 
    if(numeric_prefix && !isNaN(key)){
      use_key = numeric_prefix + i;
    }
    tmp_arr[i] = use_key + '=' + use_val;
    i++;
  }
 
  return tmp_arr.join(arg_separator);
}

/*
function mktime() { // Get Unix timestamp for a date
  // 
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: baris ozdil
 
  var i = 0, d = new Date(), argv = arguments, argc = argv.length;
 
  var dateManip = {
    0: function(tt){ return d.setHours(tt); },
    1: function(tt){ return d.setMinutes(tt); },
    2: function(tt){ return d.setSeconds(tt); },
    3: function(tt){ return d.setMonth(parseInt(tt)-1); },
    4: function(tt){ return d.setDate(tt); },
    5: function(tt){ return d.setYear(tt); }
  };
 
  for( i = 0; i < argc; i++ ){
    if(argv[i] && isNaN(argv[i])){
      return false;
    } else if(argv[i]){
      // arg is number, let's manipulate date object
      if(!dateManip[i](argv[i])){
      // failed
      return false;
      }
    }
  }
 
  return Math.floor(d.getTime()/1000);
}

function date ( format, timestamp ) {   // Format a local time/date
  // 
  // +   original by: Carlos R. L. Rodrigues
  // +    parts by: Peter-Paul Koch (http://www.quirksmode.org/js/beat.html)
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: MeEtc (http://yass.meetcweb.com)
  // +   improved by: Brad Touesnard
 
  var a, jsdate = new Date(timestamp ? timestamp * 1000 : null);
  var pad = function(n, c){
    if( (n = n + "").length < c ) {
      return new Array(++c - n.length).join("0") + n;
    } else {
      return n;
    }
  };
  var txt_weekdays = ["Sunday","Monday","Tuesday","Wednesday",
    "Thursday","Friday","Saturday"];
  var txt_ordin = {1:"st",2:"nd",3:"rd",21:"st",22:"nd",23:"rd",31:"st"};
  var txt_months =  ["", "January", "February", "March", "April",
    "May", "June", "July", "August", "September", "October", "November",
    "December"];
 
  var f = {
    // Day
    d: function(){
      return pad(f.j(), 2);
    },
    D: function(){
      t = f.l(); return t.substr(0,3);
    },
    j: function(){
      return jsdate.getDate();
    },
    l: function(){
      return txt_weekdays[f.w()];
    },
    N: function(){
      return f.w() + 1;
    },
    S: function(){
      return txt_ordin[f.j()] ? txt_ordin[f.j()] : 'th';
    },
    w: function(){
      return jsdate.getDay();
    },
    z: function(){
      return (jsdate - new Date(jsdate.getFullYear() + "/1/1")) / 864e5 >> 0;
    },
 
    // Week
    W: function(){
      var a = f.z(), b = 364 + f.L() - a;
      var nd2, nd = (new Date(jsdate.getFullYear() + "/1/1").getDay() || 7) - 1;
 
      if(b <= 2 && ((jsdate.getDay() || 7) - 1) <= 2 - b){
        return 1;
      } else{
 
        if(a <= 2 && nd >= 4 && a >= (6 - nd)){
          nd2 = new Date(jsdate.getFullYear() - 1 + "/12/31");
          return date("W", Math.round(nd2.getTime()/1000));
        } else{
          return (1 + (nd <= 3 ? ((a + nd) / 7) : (a - (7 - nd)) / 7) >> 0);
        }
      }
    },
 
    // Month
    F: function(){
      return txt_months[f.n()];
    },
    m: function(){
      return pad(f.n(), 2);
    },
    M: function(){
      t = f.F(); return t.substr(0,3);
    },
    n: function(){
      return jsdate.getMonth() + 1;
    },
    t: function(){
      var n;
      if( (n = jsdate.getMonth() + 1) == 2 ){
        return 28 + f.L();
      } else{
        if( n & 1 && n < 8 || !(n & 1) && n > 7 ){
          return 31;
        } else{
          return 30;
        }
      }
    },
 
    // Year
    L: function(){
      var y = f.Y();
      return (!(y & 3) && (y % 1e2 || !(y % 4e2))) ? 1 : 0;
    },
    //o not supported yet
    Y: function(){
      return jsdate.getFullYear();
    },
    y: function(){
      return (jsdate.getFullYear() + "").slice(2);
    },
 
    // Time
    a: function(){
      return jsdate.getHours() > 11 ? "pm" : "am";
    },
    A: function(){
      return f.a().toUpperCase();
    },
    B: function(){
      // peter paul koch:
      var off = (jsdate.getTimezoneOffset() + 60)*60;
      var theSeconds = (jsdate.getHours() * 3600) +
               (jsdate.getMinutes() * 60) +
                jsdate.getSeconds() + off;
      var beat = Math.floor(theSeconds/86.4);
      if (beat > 1000) beat -= 1000;
      if (beat < 0) beat += 1000;
      if ((String(beat)).length == 1) beat = "00"+beat;
      if ((String(beat)).length == 2) beat = "0"+beat;
      return beat;
    },
    g: function(){
      return jsdate.getHours() % 12 || 12;
    },
    G: function(){
      return jsdate.getHours();
    },
    h: function(){
      return pad(f.g(), 2);
    },
    H: function(){
      return pad(jsdate.getHours(), 2);
    },
    i: function(){
      return pad(jsdate.getMinutes(), 2);
    },
    s: function(){
      return pad(jsdate.getSeconds(), 2);
    },
    //u not supported yet
 
    // Timezone
    //e not supported yet
    //I not supported yet
    O: function(){
       var t = pad(Math.abs(jsdate.getTimezoneOffset()/60*100), 4);
       if (jsdate.getTimezoneOffset() > 0) t = "-" + t; else t = "+" + t;
       return t;
    },
    P: function(){
      var O = f.O();
      return (O.substr(0, 3) + ":" + O.substr(3, 2));
    },
    //T not supported yet
    //Z not supported yet
 
    // Full Date/Time
    c: function(){
      return f.Y() + "-" + f.m() + "-" + f.d() + "T" + f.h() + ":" + f.i() + ":" + f.s() + f.P();
    },
    //r not supported yet
    U: function(){
      return Math.round(jsdate.getTime()/1000);
    }
  };
 
  return format.replace(/[\\]?([a-zA-Z])/g, function(t, s) {
    if( t!=s ){
      // escaped
      ret = s;
    } else if( f[s] ){
      // a date function exists
      ret = f[s]();
    } else{
      // nothing special
      ret = s;
    }
    return ret;
  });
}

*/
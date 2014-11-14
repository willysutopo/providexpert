$(function(){
   $('[data-method]').append(function(){
        return "\n"+
        "<form action='"+$(this).attr('href')+"' method='POST' style='display:none'>\n"+
        "   <input type='hidden' name='_method' value='"+$(this).attr('data-method')+"'>\n"+
        "</form>\n"
   })
   .removeAttr('href')
   .attr('style','cursor:pointer;')
   .attr('onclick','$(this).find("form").submit();');
});

// function equivalent to stripslashes in PHP
function stripslashes(str) 
{
	return (str + '')
		.replace(/\\(.?)/g, function(s, n1) 
		{
			switch (n1) {
			case '\\':
			return '\\';
			case '0':
			return '\u0000';
			case '':
			return '';
			default:
			return n1;
		}
	});
}

// function equivalent to addslashes in PHP
function addslashes(str) 
{
	return (str + '')
		.replace(/[\\"']/g, '\\$&')
		.replace(/\u0000/g, '\\0');
}

// function equivalent to rawurlencode in PHP
function rawurlencode(str) 
{
	str = (str + '')
		.toString();

  // Tilde should be allowed unescaped in future versions of PHP (as reflected below), but if you want to reflect current
  // PHP behavior, you would need to add ".replace(/~/g, '%7E');" to the following.
  return encodeURIComponent(str)
    .replace(/!/g, '%21')
    .replace(/'/g, '%27')
    .replace(/\(/g, '%28')
    .
  replace(/\)/g, '%29')
    .replace(/\*/g, '%2A');
}

// function equivalent to rawurldecode in PHP
function rawurldecode(str) 
{  
	return decodeURIComponent((str + '')
		.replace(/%(?![\da-f]{2})/gi, function() {
		// PHP tolerates poorly formed escape sequences
		return '%25';
	}));
}

// function to replace all occurences of something within strings
function replaceAll( Source, stringToFind, stringToReplace )
{
	var temp = Source;
	var index = temp.indexOf( stringToFind );

	while( index != -1 )
	{
		temp = temp.replace( stringToFind, stringToReplace );
		index = temp.indexOf( stringToFind );
	}

	return temp;
}

// find the last occurence of character inside a string
function strrpos(haystack, needle, offset) 
{
  var i = -1;
  if (offset) 
  {
    i = (haystack + '')
      .slice(offset)
      .lastIndexOf(needle); // strrpos' offset indicates starting point of range till end,
    // while lastIndexOf's optional 2nd argument indicates ending point of range from the beginning
    if (i !== -1) 
    {
      i += offset;
    }
  } 
  else 
  {
    i = (haystack + '')
      .lastIndexOf(needle);
  }
  return i >= 0 ? i : false;
}

// get cursor position while typing in textarea
(function ($, undefined) {
    $.fn.getCursorPosition = function () {
        var el = $(this).get(0);
        var pos = 0;
        if ('selectionStart' in el) {
            pos = el.selectionStart;
        } else if ('selection' in document) {
            el.focus();
            var Sel = document.selection.createRange();
            var SelLength = document.selection.createRange().text.length;
            Sel.moveStart('character', -el.value.length);
            pos = Sel.text.length - SelLength;
        }
        return pos;
    }
})(jQuery);
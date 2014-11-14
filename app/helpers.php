<?php
/* collections of custom common functions */
/* -------------------------------------- */

// SETTINGS : function to change value into key
// for example : Indonesia becomes indonesia
// another : Kejora 2014 becomes kejora_2014
function change_into_setting_key( $value )
{
	// change to lower case first
    $key = strtolower( $value );

    // replace space with underscore
    $key = str_replace( ' ', '_', $key );

    // replace dash with underscore
    $key = str_replace( '-', '_', $key );

    // replace / with underscore
    $key = str_replace( '/', '_', $key );

    // replace quotation marks with nothing
    $key = str_replace( '"', '', $key );
    $key = str_replace( '\'', '', $key );

    return $key;
}

// get remoge image file size ( from url usually )
function remote_image_filesize( $url ) 
{
    static $regex = '/^Content-Length: *+\K\d++$/im';
    if (!$fp = @fopen($url, 'rb')) 
    {
        return false;
    }
    if ( isset($http_response_header) && preg_match($regex, implode("\n", $http_response_header), $matches ) ) 
    {
        return (int)$matches[0];
    }

    return strlen(stream_get_contents($fp));
}

function get_random_alphanumeric( $num )
{
    $arr_alphanum = array( "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9" );
    $random = "";
    for ( $i = 0 ; $i < $num ; $i++ )
    {
      $random .= $arr_alphanum[rand( 0, count( $arr_alphanum ) - 1 )];
    }
    return $random;
}
?>
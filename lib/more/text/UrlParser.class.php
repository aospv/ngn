<?php

/** 
* Class for manipulating URLs and anchor tags in text strings. 
* It is mainly used for parsing strings to activate URLS, or to 
* tag external links with an icon indicating that they are off site. 
* 
* This class was written for the KINKY application framework developed 
* at the University of the Western Cape by the AVOIR team 
* 
* See http://cvs.uwc.ac.za and http://avoir.uwc.ac.za for KINKY 
* 
* The following methods are provided: 
*   makeClickableLinks -> Turns plain text links into clickable links 
*   removeLinks -> Removes active links in the string 
*   isValidFormedUrl -> Tests if a URL is a validly formed URL 
*   tagExtLinks -> Provides a method to tag links that go off site with an icon 
* 
* @author Derek Keats 
* @version $Id: url_class_inc.php,v 1.10 2004/11/12 04:25:23 dkeats Exp $ 
* @copyright 2003 GPL 
* 
* If you are using this class in KINKY, use 
*    class url extends object { 
* in place of class url { 
* 
*/ 
class UrlParser { 
  
    /** 
     * Method to take a string and return it with URLS with http:// or ftp:// 
     * and email addresses as active links. I based this on code that I saw 
     * somewhere, but unfortunately, I cannot remember where I saw it. Feel free 
     * to let me know if you see any code here that is your idea, and I will credit 
     * you. 
     * 
     * @param string $str The string to be parsed 
     * @return the parsed string 
     */ 
    function makeClickableLinks($str) 
    { 
        // Exclude matched inside anchor tags 
        $not_anchor = '(?<!"|href=|href\s=\s|href=\s|href\s=)'; 
        // Match he protocol with ://, e.g. http:// 
        $protocol = '(http|ftp|https):\/\/'; 
        $domain = '[\w]+(.[\w]+)'; 
        $subdir = '([\w\-\.;,@?^=%&:\/~\+#\(\)]*[\w\-\@?^=%&\/~\+#])?'; 
        $test = '/' . $not_anchor . $protocol . $domain . $subdir . '/i'; 
        // Match and replace where there is a protocol and no anchor 
        $ret = preg_replace_callback($test, array($this, 'getMatchesHTTP'), $str);
        
        // Now match things beginning with www.
        
        /**
        
        $not_anchor = '(?<!"|href=|href\s=\s|href=\s|href\s=)';
        $not_http = '(?<!:\/\/)'; 
        $domain = 'www(.[\w]+)'; 
        $subdir = '([\w\-\.;,@?^=%&:\/~\+#]*[\w\-\@?^=%&\/~\+#])?'; 
        $test = '/' . $not_anchor . $not_http . $domain . $subdir . '/is'; 
        return preg_replace_callback($test, $this->getMatchesFuncWWW, $ret);
        
        */
        
        return $ret;
    }
    
    function getMatchesHTTP($matches) {
      $target = strstr($matches[1], 'http') ? ' target="_blank"' : '';
      return '<a href="'.$matches[0].'"'.$target.'>'.str_replace('http://', '', $matches[0]).'</a>';
    }
    
    function getMatchesWWW($matches) {
      if (strstr($matches[5], 'http://')) $matches[3] .= ' target="_blank"';
      return $matches[1].$matches[2].$matches[3].$matches[4].$matches[5].$matches[6];
    }

    /** 
     * Method to unlink URLs in text. The numbers in the comments refer 
     * to the backreferences for matches inside parentheses () 
     * 
     * @param string $str The string to be parsed 
     * @return the parsed string 
     */ 
    function removeLinks($str) 
    { 
        $test = "/(<a\s+href=)"    // 1: Match the start of the anchor tag followed by 
                                   //    any number of spaces followed by href followed 
              . "([\"\'])"         // 2: Match either of " or ' and remember it as \2 for a backreference 
              . "(.*[\"'])"        // 3: Match any characters up to the next " or ' 
              . "(.*>)"            // 4: Anything else followed by the closing > 
              . "(.+)"             // 5: Match any string of 1 or more characters 
              . "(<\/a>)"          // 6: Match the closing </a> tag 
              . "/isU";            // Make it case insensitive (i), treat across newlines (s) 
                                   // and make it Ungreedy (U) 
          
          //$link = preg_replace($test, "\$1\$2\$3\$4\$5\$6", $str); 
          
          return preg_replace_callback(
            $test,
            $this->parseLinkMatchesFunc,
            $str
          );
            
           
    }
    
    /** 
     * Checks if a URL is validly formed based on the code in 
     * PHP Classes by Lennart Groetzbach <lennartg_at_web_dot_de> 
     * Adapted to KINKY by Derek 
     * 
     * @param String $str The Url to validate 
     * @param boolean $strict Enforce strict checking? 
     * @return boolean TRUE|FALSE 
     */ 
    function isValidFormedUrl($str, $strict = false) 
    { 
        $test = ""; 
        if ($strict == true) { 
            $test .= "/^http:\\/\\/([A-Za-z-\\.]*)\\//"; 
        } else { 
            $test .= "/^http:\\/\\/([A-Za-z-\\.]*)/"; 
        } 
        return @preg_match($test, $str); 
    } 

    /** 
     * Method to add something to the end of an external link 
     * where external is defined as one that starts with http:// 
     * or ftp:// 
     * It uses a perl type regular expression 
     * Ir was made with help from participants in the 
     * Undernet IRC channel #phphelp 
     * @author Derek Keats 
     * @param String $str The string to be tagged 
     * @param String $activ Whether to make the $repl or icon active, default TRUE 
     * @param String $repl The string to add to the end of the link 
     *   Defaults to the external link icon 
     * @return String The string with external links tagged 
     */ 
    function tagExtLinks($str, $activ=true, $repl = null) 
    { 
        if ($repl == null) { 
            $exterIcon = 'external_link.gif'; 
            $repl = '<img src="' . $exterIcon . '" alt="Open in a new window" border="0">'; 
        } 
        if ($activ==null) { 
            $test = "/(<a\s+href=)" // 1: Match the opening of the anchor tag 
              ."(\"|\')" // 2: Match the first single or double quotes 
              ."(http|https|ftp)" // 3: match the protocol identifier (Http, ftp, https) 
              ."(:\/\/)" //4: Match the :// part of the URL 
              ."(?!" . $_SERVER['HTTP_HOST'] . ")"  // 5: Do not match if it is the local server 
              ."(.*)" // 6: Match any number of following characters up to the closing anchor tag 
              ."(<\/a>)/isU"; // 7: Match the closing anchor tag 
            return preg_replace($test, " \${0}" . $repl, $str); 
        } else { 
            $test = "/(<a\s+href=)" // 1: Match the opening of the anchor tag 
              ."(\"|\')" // 2: Match the first single or double quotes 
              ."(http|https|ftp)" // 3: match the protocol identifier (Http, ftp, https) 
              ."(:\/\/)" //4: Match the :// part of the URL 
              ."(?!" . $_SERVER['HTTP_HOST'] . ")" // 5: Match domain but exclude the local server 
              ."(.*)" // 6: Match any characters after the server 
              ."(\"|\')" //7: Match a closing single or double quote (helps pull out the URL) 
              ."(.*)" // 8: Match any further characters 
              ."<\/a>/isU"; // 9: Match the closing anchor 
            return preg_replace($test, 
              " \${0} <a href=\"\${3}\${4}\${5}\" 
              target=\"_BLANK\">" . $repl . "</a>", $str); 
        } 
         
    } 
     
    /** 
    * Method to activate image links. Note that this will have to be called 
    * before the tagExtLinks method, otherwise the images will appear as links 
    * not as the images themselves 
    * 
    * @param string $str The string to parse 
    * 
    */ 
    function activateImage($str) 
    { 
        // Exclude matched inside anchor tags 
        $test = "/(?<!\"|href=|href\s=\s|href=\s|href\s=|src=|src\s=\s|src=\s|src\s=)" //Make sure it is not inside an anchor or img 
          .  "(http|https)"         // Match the protocol (http or https) 
          . "(:\/\/)"                   // Match the :// part of http:// 
          . "(.*)"                      // Match any number of characters 
          . "(\.gif|\.jpg|\.png)"       // Match the file extension 
          . "/iU";                      // Make it case independent and ungreedy 
        return preg_replace($test, 
          "<img src=\"\${0}\">", $str); 

    } 
} 

/*

$testStr = "Now is the time for all good cookie monsters to eat at Google at http://www.google.com. In 
the merry month of May when the birds began to play, I took a walk quite early one fine morning at 
<a href=\"http://www.gnu.org\">the Free Software Foundation</a>. There I found that there was 
something http://www.gnu.org/graphics/gnu-head.jpg gnu to see. This image link ( 
<a href=\"http://www.gnu.org/graphics/gnu-head.jpg\">this one</a>) is not touched 
because it is inside an ANCHOR tag"; 

//Output the original string 
echo "<h3>Original string</h3>$testStr <hr>"; 

//Instantiate the class 
$oUrl = new UrlParser(); 
//Make links clickable and dump to output 
$outStr = $oUrl->makeClickableLinks($testStr); 
echo "<h3>With clickable links active</h3>$outStr <hr>"; 

//Tag external links and dump to output 
$outStr = $oUrl->tagExtLinks($testStr); 
echo "<h3>With external links tagged</h3>$outStr <hr>"; 

//Remove links and dump to output 
$outStr = $oUrl->removeLinks($testStr); 
echo "<h3>With active links removed</h3>$outStr <hr>"; 

//Replace image links with the actual image and dump to output 
$outStr = $oUrl->activateImage($testStr); 
echo "<h3>With image links turned into the image</h3>$outStr <hr>"; 

*/

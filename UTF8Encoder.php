<?php
//	define('_CHARSET',					'EUC-JP');
	define('_CHARSET',					'UTF-8');
//	$c = new ENC;
/*
	echo '
<form method="get" action="de.php">
<input type="text" name="query" value="<?=htmlspecialchars($query) ?>">
<input type="submit" value="search">
</form>
	';

echo $qj = trim($_GET['query']);

//echo "<br />";
//echo $qj = '日本語';
echo "<hr />";

if(_CHARSET != 'UTF-8') {
	$qj = $c->_restore_to_utf8($qj);
}else{
//	$qj = $c->_decode_entities($qj);
}

	echo     $qj2 = $c->_utf8_to_entities($qj);
echo "<br />";
echo urlencode($qj)." -> ".urlencode($qj2);
*/
	class UTF8Encoder {

		/**************************************************************************************/
		/* Internal helper functions for dealing with encodings and entities                  */
	
		var $entities_cp1251 = array (
			'&#128;' 		=> '&#8364;',
			'&#130;' 		=> '&#8218;',
			'&#131;' 		=> '&#402;',	
			'&#132;' 		=> '&#8222;',	
			'&#133;' 		=> '&#8230;',	
			'&#134;' 		=> '&#8224;',	
			'&#135;' 		=> '&#8225;',	
			'&#136;' 		=> '&#710;',	
			'&#137;' 		=> '&#8240;',	
			'&#138;' 		=> '&#352;',	
			'&#139;' 		=> '&#8249;',	
			'&#140;' 		=> '&#338;',	
			'&#142;' 		=> '&#381;',	
			'&#145;' 		=> '&#8216;',	
			'&#146;' 		=> '&#8217;',	
			'&#147;' 		=> '&#8220;',	
			'&#148;' 		=> '&#8221;',	
			'&#149;' 		=> '&#8226;',	
			'&#150;' 		=> '&#8211;',	
			'&#151;' 		=> '&#8212;',	
			'&#152;' 		=> '&#732;',	
			'&#153;' 		=> '&#8482;',	
			'&#154;' 		=> '&#353;',	
			'&#155;' 		=> '&#8250;',	
			'&#156;' 		=> '&#339;',	
			'&#158;' 		=> '&#382;',	
			'&#159;' 		=> '&#376;',	
		);
	
		var $entities_default = array (
			'&quot;'		=> '&#34;',		
			'&amp;'   		=> '&#38;',	  	
			'&apos;'  		=> '&#39;',		
			'&lt;'    		=> '&#60;',		
			'&gt;'    		=> '&#62;',		
		);
	
		var $entities_latin = array (
			'&nbsp;' 		=> '&#160;',	
			'&iexcl;'		=> '&#161;',	
			'&cent;' 		=> '&#162;',	
			'&pound;' 		=> '&#163;',	
			'&curren;'		=> '&#164;',	
			'&yen;' 		=> '&#165;',	
			'&brvbar;'		=> '&#166;', 	
			'&sect;' 		=> '&#167;',	
			'&uml;' 		=> '&#168;',	
			'&copy;' 		=> '&#169;',	
			'&ordf;' 		=> '&#170;',	
			'&laquo;' 		=> '&#171;',	
			'&not;' 		=> '&#172;',	
			'&shy;' 		=> '&#173;',	
			'&reg;' 		=> '&#174;',	
			'&macr;' 		=> '&#175;',	
			'&deg;' 		=> '&#176;',	
			'&plusmn;' 		=> '&#177;',	
			'&sup2;' 		=> '&#178;',	
			'&sup3;' 		=> '&#179;', 	
			'&acute;' 		=> '&#180;',	
			'&micro;' 		=> '&#181;', 	
			'&para;' 		=> '&#182;',	
			'&middot;' 		=> '&#183;',	
			'&cedil;' 		=> '&#184;', 	
			'&sup1;' 		=> '&#185;',	
			'&ordm;' 		=> '&#186;',	
			'&raquo;' 		=> '&#187;',	
			'&frac14;' 		=> '&#188;',	
			'&frac12;' 		=> '&#189;',	
			'&frac34;' 		=> '&#190;',	
			'&iquest;' 		=> '&#191;',	
			'&Agrave;' 		=> '&#192;',	
			'&Aacute;' 		=> '&#193;',	
			'&Acirc;' 		=> '&#194;',	
			'&Atilde;' 		=> '&#195;',	
			'&Auml;' 		=> '&#196;',	
			'&Aring;' 		=> '&#197;',	
			'&AElig;' 		=> '&#198;',	
			'&Ccedil;'		=> '&#199;', 	
			'&Egrave;' 		=> '&#200;',	
			'&Eacute;' 		=> '&#201;',	
			'&Ecirc;' 		=> '&#202;',	
			'&Euml;' 		=> '&#203;',	
			'&Igrave;' 		=> '&#204;',	
			'&Iacute;' 		=> '&#205;',	
			'&Icirc;' 		=> '&#206;',	
			'&Iuml;' 		=> '&#207;', 	
			'&ETH;' 		=> '&#208;',	
			'&Ntilde;' 		=> '&#209;',	
			'&Ograve;' 		=> '&#210;',	
			'&Oacute;'		=> '&#211;',	
			'&Ocirc;' 		=> '&#212;',	
			'&Otilde;' 		=> '&#213;',	
			'&Ouml;' 		=> '&#214;',	
			'&times;' 		=> '&#215;',	
			'&Oslash;' 		=> '&#216;',	
			'&Ugrave;' 		=> '&#217;',	
			'&Uacute;' 		=> '&#218;',	
			'&Ucirc;' 		=> '&#219;',	
			'&Uuml;' 		=> '&#220;',	
			'&Yacute;' 		=> '&#221;',	
			'&THORN;' 		=> '&#222;',	
			'&szlig;' 		=> '&#223;',	
			'&agrave;' 		=> '&#224;',	
			'&aacute;' 		=> '&#225;',	
			'&acirc;' 		=> '&#226;',	
			'&atilde;' 		=> '&#227;',	
			'&auml;' 		=> '&#228;',	
			'&aring;' 		=> '&#229;',	
			'&aelig;' 		=> '&#230;',	
			'&ccedil;' 		=> '&#231;',	
			'&egrave;' 		=> '&#232;',	
			'&eacute;' 		=> '&#233;',	
			'&ecirc;' 		=> '&#234;',	
			'&euml;' 		=> '&#235;',	
			'&igrave;' 		=> '&#236;',	
			'&iacute;' 		=> '&#237;',	
			'&icirc;' 		=> '&#238;',	
			'&iuml;' 		=> '&#239;',	
			'&eth;' 		=> '&#240;',	
			'&ntilde;' 		=> '&#241;',	
			'&ograve;' 		=> '&#242;',	
			'&oacute;' 		=> '&#243;',	
			'&ocirc;' 		=> '&#244;',	
			'&otilde;' 		=> '&#245;',	
			'&ouml;' 		=> '&#246;',	
			'&divide;' 		=> '&#247;',	
			'&oslash;' 		=> '&#248;',	
			'&ugrave;' 		=> '&#249;',	
			'&uacute;' 		=> '&#250;',	
			'&ucirc;' 		=> '&#251;',	
			'&uuml;' 		=> '&#252;',	
			'&yacute;' 		=> '&#253;',	
			'&thorn;' 		=> '&#254;',	
			'&yuml;' 		=> '&#255;',	
		);	
	
		var $entities_extended = array (
			'&OElig;'		=> '&#338;',	
			'&oelig;'		=> '&#229;',	
			'&Scaron;'		=> '&#352;',	
			'&scaron;'		=> '&#353;',	
			'&Yuml;'		=> '&#376;',	
			'&circ;'		=> '&#710;',	
			'&tilde;'		=> '&#732;', 	
			'&esnp;'		=> '&#8194;',	
			'&emsp;'		=> '&#8195;',	
			'&thinsp;'		=> '&#8201;',	
			'&zwnj;'		=> '&#8204;',	
			'&zwj;'			=> '&#8205;',	
			'&lrm;'			=> '&#8206;',	
			'&rlm;'			=> '&#8207;', 	
			'&ndash;'		=> '&#8211;', 	
			'&mdash;'		=> '&#8212;',	
			'&lsquo;'		=> '&#8216;',	
			'&rsquo;'		=> '&#8217;', 	
			'&sbquo;'		=> '&#8218;',	
			'&ldquo;'		=> '&#8220;', 	
			'&rdquo;'		=> '&#8221;',	
			'&bdquo;'		=> '&#8222;',	
			'&dagger;'		=> '&#8224;',	
			'&Dagger;'		=> '&#8225;',	
			'&permil;'		=> '&#8240;',	
			'&lsaquo;'		=> '&#8249;',
			'&rsaquo;'		=> '&#8250;',
			'&euro;'		=> '&#8364;',
			'&fnof;'		=> '&#402;',	
			'&Alpha;'		=> '&#913;',	
			'&Beta;'		=> '&#914;',	
			'&Gamma;'		=> '&#915;',	
			'&Delta;'		=> '&#916;',	
			'&Epsilon;'		=> '&#917;',	
			'&Zeta;'		=> '&#918;',	
			'&Eta;'			=> '&#919;',	
			'&Theta;'		=> '&#920;',	
			'&Iota;'		=> '&#921;',	
			'&Kappa;'		=> '&#922;',	
			'&Lambda;'		=> '&#923;',	
			'&Mu;'			=> '&#924;',	
			'&Nu;'			=> '&#925;',	
			'&Xi;'			=> '&#926;',	
			'&Omicron;'		=> '&#927;',	
			'&Pi;'			=> '&#928;',	
			'&Rho;'			=> '&#929;',	
			'&Sigma;'		=> '&#931;',	
			'&Tau;'			=> '&#932;',	
			'&Upsilon;'		=> '&#933;', 	
			'&Phi;'			=> '&#934;',	
			'&Chi;'			=> '&#935;',	
			'&Psi;'			=> '&#936;',	
			'&Omega;'		=> '&#937;',	
			'&alpha;'		=> '&#945;',	
			'&beta;'		=> '&#946;',	
			'&gamma;'		=> '&#947;',	
			'&delta;'		=> '&#948;',	
			'&epsilon;'		=> '&#949;',	
			'&zeta;'		=> '&#950;',	
			'&eta;'			=> '&#951;',	
			'&theta;'		=> '&#952;',	
			'&iota;'		=> '&#953;',	
			'&kappa;'		=> '&#954;',	
			'&lambda;'		=> '&#955;',	
			'&mu;'			=> '&#956;',	
			'&nu;'			=> '&#957;',	
			'&xi;'			=> '&#958;',	
			'&omicron;'		=> '&#959;',	
			'&pi;'			=> '&#960;',	
			'&rho;'			=> '&#961;',	
			'&sigmaf;'		=> '&#962;',	
			'&sigma;'		=> '&#963;',	
			'&tau;'			=> '&#964;',	
			'&upsilon;'		=> '&#965;', 	
			'&phi;'			=> '&#966;',	
			'&chi;'			=> '&#967;',	
			'&psi;'			=> '&#968;',	
			'&omega;'		=> '&#969;',	
			'&thetasym;'	=> '&#977;',	
			'&upsih;'		=> '&#978;',	
			'&piv;'			=> '&#982;',	
			'&bull;'		=> '&#8226;',	
			'&hellip;'		=> '&#8230;',	
			'&prime;'		=> '&#8242;',	
			'&Prime;'		=> '&#8243;',	
			'&oline;'		=> '&#8254;', 	
			'&frasl;'		=> '&#8260;',	
			'&weierp;'		=> '&#8472;', 	
			'&image;'		=> '&#8465;', 	
			'&real;'		=> '&#8476;',	
			'&trade;'		=> '&#8482;', 	
			'&alefsym;' 	=> '&#8501;', 	
			'&larr;'		=> '&#8592;', 	
			'&uarr;'		=> '&#8593;', 	
			'&rarr;'		=> '&#8594;',	
			'&darr;'		=> '&#8595;', 	
			'&harr;'		=> '&#8596;',	
			'&crarr;'		=> '&#8629;',	
			'&lArr;'		=> '&#8656;',	
			'&uArr;'		=> '&#8657;', 	
			'&rArr;'		=> '&#8658;', 	
			'&dArr;'		=> '&#8659;', 	
			'&hArr;'		=> '&#8660;', 	
			'&forall;'		=> '&#8704;', 	
			'&part;'		=> '&#8706;', 	
			'&exist;'		=> '&#8707;', 	
			'&empty;'		=> '&#8709;', 	
			'&nabla;'		=> '&#8711;', 	
			'&isin;'		=> '&#8712;', 	
			'&notin;'		=> '&#8713;', 	
			'&ni;'			=> '&#8715;', 	
			'&prod;'		=> '&#8719;', 	
			'&sum;'			=> '&#8721;', 	
			'&minus;'		=> '&#8722;', 	
			'&lowast;'		=> '&#8727;', 	
			'&radic;'		=> '&#8730;', 	
			'&prop;'		=> '&#8733;', 	
			'&infin;'		=> '&#8734;', 	
			'&ang;'			=> '&#8736;', 	
			'&and;'			=> '&#8743;', 	
			'&or;'			=> '&#8744;', 	
			'&cap;'			=> '&#8745;', 	
			'&cup;'			=> '&#8746;', 	
			'&int;'			=> '&#8747;', 	
			'&there4;'		=> '&#8756;', 	
			'&sim;'			=> '&#8764;', 	
			'&cong;'		=> '&#8773;', 	
			'&asymp;'		=> '&#8776;', 	
			'&ne;'			=> '&#8800;', 	
			'&equiv;'		=> '&#8801;', 	
			'&le;'			=> '&#8804;', 	
			'&ge;'			=> '&#8805;', 	
			'&sub;'			=> '&#8834;', 	
			'&sup;'			=> '&#8835;', 	
			'&nsub;'		=> '&#8836;', 	
			'&sube;'		=> '&#8838;', 	
			'&supe;'		=> '&#8839;', 	
			'&oplus;'		=> '&#8853;', 	
			'&otimes;'  	=> '&#8855;', 	
			'&perp;'		=> '&#8869;', 	
			'&sdot;'		=> '&#8901;', 	
			'&lceil;'		=> '&#8968;', 	
			'&rceil;'		=> '&#8969;', 	
			'&lfloor;'		=> '&#8970;', 	
			'&rfloor;'		=> '&#8971;', 	
			'&lang;'		=> '&#9001;', 	
			'&rang;'		=> '&#9002;', 	
			'&loz;'			=> '&#9674;', 	
			'&spades;'		=> '&#9824;', 	
			'&clubs;'		=> '&#9827;', 	
			'&hearts;'		=> '&#9829;', 	
			'&diams;'		=> '&#9830;', 	
		);
	
	
//modify start+++++++++
		function _restore_to_utf8($contents)
		{
			if (_CHARSET != 'UTF-8')
			{
				$contents = mb_convert_encoding($contents, 'UTF-8', _CHARSET);
			}
			$contents = $this->_decode_entities(strip_tags($contents));
			return $contents;
		}
//modify end+++++++++

		function _convert_to_utf8($contents, $encoding)
		{
			$done = false;
			
			if (!$done && function_exists('iconv'))  
			{
			
				$result = @iconv($encoding, 'UTF-8//IGNORE', $contents);
	
				if ($result) 
				{
					$contents = $result;
					$done = true;
				}
			}
			
			if(!$done && function_exists('mb_convert_encoding')) 
			{
				@mb_substitute_character('none');
				$result = @mb_convert_encoding($contents, 'UTF-8', $encoding );
	
				if ($result) 
				{
					$contents = $result;
				}
			}
		
			return $contents;
		}
		
		function _convert_to_utf8_auto($contents, $headers = '')
		{
			/* IN:  string in unknown encoding, headers received during transfer
			 * OUT: string in UTF-8 encoding
			 */
	
			$str = substr($contents, 0, 4096);
			$len = strlen($str);
			$pos = 0;
			$out = '';
			
			while ($pos < $len)
			{
				$ord = ord($str[$pos]);
				
				if ($ord > 32 && $ord < 128)
					$out .= $str[$pos];
					
				$pos++;
			}
	
			// Detection of encoding, check headers
			if (preg_match ("/;\s*charset=([^\n]+)/is", $headers, $regs))
				$encoding = strtoupper(trim($regs[1]));
	
			// Then check meta inside document
			if (preg_match ("/;\s*charset=([^\"']+)/is", $out, $regs))
				$encoding = strtoupper(trim($regs[1]));
				
			// Then check xml declaration
			if (preg_match("/<\?xml.+encoding\s*=\s*[\"|']([^\"']+)[\"|']\s*\?>/i", $out, $regs))
				$encoding = strtoupper(trim($regs[1]));		
	
			// Converts
			return $this->_convert_to_utf8($contents, $encoding);
		}
		
		function _decode_entities($string)
		{
			/* IN:  string in UTF-8 containing entities
			 * OUT: string in UTF-8 without entities
			 */
			 
			/// Convert all hexadecimal entities to decimal entities
			$string = preg_replace('/&#[Xx]([0-9A-Fa-f]+);/e', "'&#'.hexdec('\\1').';'", $string);		

			// Deal with invalid cp1251 numeric entities
			$string = strtr($string, $this->entities_cp1251);

			// Convert all named entities to numeric entities
			$string = strtr($string, $this->entities_default);
			$string = strtr($string, $this->entities_latin);
			$string = strtr($string, $this->entities_extended);

			// Convert all numeric entities to UTF-8
			$string = preg_replace('/&#([0-9]+);/e', "'&#x'.dechex('\\1').';'", $string);
			$string = preg_replace('/&#[Xx]([0-9A-Fa-f]+);/e', "ENC::_hex_to_utf8('\\1')", $string);		

			return $string;
		}
	
		function _hex_to_utf8($s)
		{
			/* IN:  string containing one hexadecimal Unicode character
			 * OUT: string containing one binary UTF-8 character
			 */
			 
			$c = hexdec($s);
		
			if ($c < 0x80) {
				$str = chr($c);
			}
			else if ($c < 0x800) {
				$str = chr(0xC0 | $c>>6) . chr(0x80 | $c & 0x3F);
			}
			else if ($c < 0x10000) {
				$str = chr(0xE0 | $c>>12) . chr(0x80 | $c>>6 & 0x3F) . chr(0x80 | $c & 0x3F);
			}
			else if ($c < 0x200000) {
				$str = chr(0xF0 | $c>>18) . chr(0x80 | $c>>12 & 0x3F) . chr(0x80 | $c>>6 & 0x3F) . chr(0x80 | $c & 0x3F);
			}
			
			return $str;
		} 		

		function _utf8_to_entities($string)
		{
			/* IN:  string in UTF-8 encoding
			 * OUT: string consisting of only characters ranging from 0x00 to 0x7f, 
			 *      using numeric entities to represent the other characters 
			 */
			 
			$len = strlen ($string);
			$pos = 0;
			$out = '';
				
			while ($pos < $len) 
			{
				$ascii = ord (substr ($string, $pos, 1));
				
				if ($ascii >= 0xF0) 
				{
					$byte[1] = ord(substr ($string, $pos, 1)) - 0xF0;
					$byte[2] = ord(substr ($string, $pos + 1, 1)) - 0x80;
					$byte[3] = ord(substr ($string, $pos + 2, 1)) - 0x80;
					$byte[4] = ord(substr ($string, $pos + 3, 1)) - 0x80;
	
					$char_code = ($byte[1] << 18) + ($byte[2] << 12) + ($byte[3] << 6) + $byte[4];
					$pos += 4;
				}
				elseif (($ascii >= 0xE0) && ($ascii < 0xF0)) 
				{
					$byte[1] = ord(substr ($string, $pos, 1)) - 0xE0;
					$byte[2] = ord(substr ($string, $pos + 1, 1)) - 0x80;
					$byte[3] = ord(substr ($string, $pos + 2, 1)) - 0x80;
	
					$char_code = ($byte[1] << 12) + ($byte[2] << 6) + $byte[3];
					$pos += 3;
				}
				elseif (($ascii >= 0xC0) && ($ascii < 0xE0)) 
				{
					$byte[1] = ord(substr ($string, $pos, 1)) - 0xC0;
					$byte[2] = ord(substr ($string, $pos + 1, 1)) - 0x80;
	
					$char_code = ($byte[1] << 6) + $byte[2];
					$pos += 2;
				}
				else 
				{
					$char_code = ord(substr ($string, $pos, 1));
					$pos += 1;
				}
	
				if ($char_code < 0x80)
					$out .= chr($char_code);
				else
					$out .=  '&#'. str_pad($char_code, 5, '0', STR_PAD_LEFT) . ';';
			}
	
			return $out;	
		}			

		function _utf8_to_javascript($string)
		{
			/* IN:  string in UTF-8 encoding
			 * OUT: string consisting of only characters ranging from 0x00 to 0x7f, 
			 *      using javascript escapes to represent the other characters 
			 */
			 
			$len = strlen ($string);
			$pos = 0;
			$out = '';
				
			while ($pos < $len) 
			{
				$ascii = ord (substr ($string, $pos, 1));
				
				if ($ascii >= 0xF0) 
				{
					$byte[1] = ord(substr ($string, $pos, 1)) - 0xF0;
					$byte[2] = ord(substr ($string, $pos + 1, 1)) - 0x80;
					$byte[3] = ord(substr ($string, $pos + 2, 1)) - 0x80;
					$byte[4] = ord(substr ($string, $pos + 3, 1)) - 0x80;
	
					$char_code = ($byte[1] << 18) + ($byte[2] << 12) + ($byte[3] << 6) + $byte[4];
					$pos += 4;
				}
				elseif (($ascii >= 0xE0) && ($ascii < 0xF0)) 
				{
					$byte[1] = ord(substr ($string, $pos, 1)) - 0xE0;
					$byte[2] = ord(substr ($string, $pos + 1, 1)) - 0x80;
					$byte[3] = ord(substr ($string, $pos + 2, 1)) - 0x80;
	
					$char_code = ($byte[1] << 12) + ($byte[2] << 6) + $byte[3];
					$pos += 3;
				}
				elseif (($ascii >= 0xC0) && ($ascii < 0xE0)) 
				{
					$byte[1] = ord(substr ($string, $pos, 1)) - 0xC0;
					$byte[2] = ord(substr ($string, $pos + 1, 1)) - 0x80;
	
					$char_code = ($byte[1] << 6) + $byte[2];
					$pos += 2;
				}
				else 
				{
					$char_code = ord(substr ($string, $pos, 1));
					$pos += 1;
				}
	
				if ($char_code < 0x80)
					$out .= chr($char_code);
				else
					$out .=  '\\u'. str_pad(dechex($char_code), 4, '0', STR_PAD_LEFT);
			}
	
			return $out;	
		}			
				
		function _cut_string($string, $dl = 0) {
		
			$defaultLength = $dl > 0 ? $dl : $this->getOption('defaultLength');
			
			if ($defaultLength < 1)
				return $string;
	
			$border    = 6;
			$count     = 0;
			$lastvalue = 0;
	
  			for ($i = 0; $i < strlen($string); $i++)
       		{
       			$value = ord($string[$i]);
	   
	   			if ($value > 127)
           		{
           			if ($value >= 192 && $value <= 223)
               			$i++;
           			elseif ($value >= 224 && $value <= 239)
               			$i = $i + 2;
           			elseif ($value >= 240 && $value <= 247)
               			$i = $i + 3;
					
					if ($lastvalue <= 223 && $value >= 223 && 
						$count >= $defaultLength - $border)
					{
						return substr($string, 0, $i) . '...';
					}

					// Chinese and Japanese characters are
					// wider than Latin characters
					if ($value >= 224)
						$count++;
					
           		}
				elseif ($string[$i] == '/' || $string[$i] == '?' ||
						$string[$i] == '-' || $string[$i] == ':' ||
						$string[$i] == ',' || $string[$i] == ';')
				{
					if ($count >= $defaultLength - $border)
						return substr($string, 0, $i) . '...';
				}
				elseif ($string[$i] == ' ')
				{
					if ($count >= $defaultLength - $border)
						return substr($string, 0, $i) . '...';
				}
				
				if ($count == $defaultLength)
					return substr($string, 0, $i + 1) . '...';
      
	  			$lastvalue = $value;
       			$count++;
       		}

			return $string;
		}
		

}

?>

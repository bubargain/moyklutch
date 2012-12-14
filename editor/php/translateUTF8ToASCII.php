<?php 

/*
 * Translate the utf-8 Russia character into extended ASCII string
 * Input: $char
 * Output: ASCII 
 * Date: 2012-11-18
 */

//print_r(splitRussiaChar("восемь зданийabc"));

function splitRussiaChar($str)
{
	$array=mbStrSplit($str);
	$result='';
	foreach($array as $char)
	{
		$result=$result.translateUTF8ToASCII($char);
	}
	return $result;
	
}


/*
 * split string into array by UTF-8 character size
 * $string : initial string
 * $len:  number of characters in each array unit
 */
function mbStrSplit ($string, $len=1) {
    $start = 0;
    $strlen = mb_strlen($string);
    while ($strlen) {
        $array[] = mb_substr($string,$start,$len,"utf8");
        $string = mb_substr($string, $len, $strlen,"utf8");
        $strlen = mb_strlen($string);
    }
    return $array;
}


function translateUTF8ToASCII($char)
{
	$AsciiCode;
	switch($char)
	{
		//large case
		
		case 'А':
			$AsciiCode="80"; break;
		case 'Б':
			$AsciiCode="81"; break;
		case 'В':
			$AsciiCode="82"; break;
		case 'Г':
			$AsciiCode="83"; break;	
		case 'Д':
			$AsciiCode="84"; break;
		case 'Е':
			$AsciiCode="85"; break;
		case 'Ж':
			$AsciiCode="86"; break;
		case 'З':
			$AsciiCode="87"; break;	
		case 'И':
			$AsciiCode="88"; break;
		case 'Й':
			$AsciiCode="89"; break;
		case 'К':
			$AsciiCode="8A"; break;
		case 'Л':
			$AsciiCode="8B"; break;	
		case 'М':
			$AsciiCode="8C"; break;	
		case 'Н':
			$AsciiCode="8D"; break;	
		case 'О':
			$AsciiCode="8E"; break;	
		case 'П':
			$AsciiCode="8F"; break;
		case 'Р':
			$AsciiCode="90"; break;
		case 'С':
			$AsciiCode="91"; break;	
		case 'Т':
			$AsciiCode="92"; break;	
		case 'У':
			$AsciiCode="93"; break;	
		case 'Ф':
			$AsciiCode="94"; break;	
		case 'Х':
			$AsciiCode="95"; break;
		case 'Ц':
			$AsciiCode="96"; break;	
		case 'Ч':
			$AsciiCode="97"; break;	
		case 'Ш':
			$AsciiCode="98"; break;	
		case 'Щ':
			$AsciiCode="99"; break;	
		case 'Ъ':
			$AsciiCode="9A"; break;
		case 'Ы':
			$AsciiCode="9B"; break;
		case 'Ь':
			$AsciiCode="9C"; break;	
		case 'Э':
			$AsciiCode="9D"; break;	
		case 'Ю':
			$AsciiCode="9E"; break;	
		case 'Я':
			$AsciiCode="9F"; break;	
		
		//low case	

		case 'а':
			$AsciiCode="A0"; break;
		case 'б':
			$AsciiCode="A1"; break;
		case 'в':
			$AsciiCode="A2"; break;
		case 'г':
			$AsciiCode="A3"; break;	
		case 'д':
			$AsciiCode="A4"; break;
		case 'е':
			$AsciiCode="A5"; break;
		case 'ж':
			$AsciiCode="A6"; break;
		case 'з':
			$AsciiCode="A7"; break;	
		case 'и':
			$AsciiCode="A8"; break;
		case 'й':
			$AsciiCode="A9"; break;
		case 'к':
			$AsciiCode="AA"; break;
		case 'л':
			$AsciiCode="AB"; break;	
		case 'м':
			$AsciiCode="AC"; break;	
		case 'н':
			$AsciiCode="AD"; break;	
		case 'о':
			$AsciiCode="AE"; break;	
		case 'п':
			$AsciiCode="AF"; break;
		case 'р':
			$AsciiCode="E0"; break;
		case 'с':
			$AsciiCode="E1"; break;	
		case 'т':
			$AsciiCode="E2"; break;	
		case 'у':
			$AsciiCode="E3"; break;	
		case 'ф':
			$AsciiCode="E4"; break;	
		case 'х':
			$AsciiCode="E5"; break;
		case 'ц':
			$AsciiCode="E6"; break;	
		case 'ч':
			$AsciiCode="E7"; break;	
		case 'ш':
			$AsciiCode="E8"; break;	
		case 'щ':
			$AsciiCode="E9"; break;	
		case 'ъ':
			$AsciiCode="EA"; break;
		case 'ы':
			$AsciiCode="EB"; break;
		case 'ь':
			$AsciiCode="EC"; break;	
		case 'э':
			$AsciiCode="ED"; break;	
		case 'ю':
			$AsciiCode="EE"; break;	
		case 'я':
			$AsciiCode="EF"; break;

			
		case 'Ё':
			$AsciiCode="F0"; break;	
		case 'ё':
			$AsciiCode="F1"; break;	
		
			
		//if not russia character, just ouput its ASCII number in regular way
		default:
			$AsciiCode=sprintf("%02x",ord($char));
			
	}
	
	return $AsciiCode;
	
}


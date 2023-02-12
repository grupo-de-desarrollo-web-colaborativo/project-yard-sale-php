<?php
/*> fileChar($text,$Char_replace,$spa): Cambia un texto por solo los caracteres
	 del abecedario (con opción de agregar la eñe), punto, números,
	 transformando a minúsculas, cualquier otro caracter lo convierte
	 por "-" (guión medio) dejando solo 1 caracter de reemplazo o separador.
	- Parametros
		[requerido] $text (string) El texto a modificar
		[opcional] $char_replace (string) {predet: "-"} El caracter (o caracteres) por el cual se cambiarán los caracteres fuera del abecedrio
		[opcional] $lowercase (bool) {predet: TRUE} Si el texto final se convierte o no a minúsculas
		[opcional] $spanish (bool) {predet: FALSE} Si son permitidas las eñe
	- Valores Devueltos
		Tipo: String
		El texto convertido
	- Ej:
	filechar("Una piña vale $6.000 COP","_",TRUE,FALSE) = Una_piña_vale_6.000_COP
	filechar("Una piña vale $6.000 COP") = una-pina-vale-6.000-cop
*/
function fileChar(string $text,string $char_replace = '-', bool $lowercase = true, bool $spanish = false){

	//Conversion en la funcion stripAccents de los caracteres parecidos a letras (ej: acentos)
	$text = stripAccents($text,$spanish);

	//Reemplazar cualquier caracter que no sea numero o letra por guion (como espacios o signos de puntuacion)
	if($spanish){
		//Si se coloca $spanish en TRUE no se reemplazarán las letras eñe
		$regex = '[^.ñÑA-Za-z0-9]';
	}else{
		$regex = '[^.A-Za-z0-9-]';
	}
	$text = mb_ereg_replace($regex,$char_replace,$text);

	//convierte en minúsculas
	if($lowercase){
		$text = mb_strtolower($text, 'UTF-8');
	}

	//elimina el exceso de dobles caracteres no válidos
	while(strpos($text,$char_replace.$char_replace)){
		$text = str_replace($char_replace.$char_replace,$char_replace, $text);
	}
	//Elimina los guiones sobrantes al inicio y final del string
	$text = trim($text,$char_replace);
	return $text;
}

/*> firstWord($text,$separator): devuelve la primera palabra de un texto
	- Parametros
		[requerido] $text (string) El texto del cual sacar la primera palabra
		[opcional] $separator (string) {predet : " "} Separador o limitador de la primera palabra
	- Valores Devueltos
		Tipo: String
		La primera palabra o coincidencia hasta el separador
	- Ej:
		firstWord("El-hijo de rana") = El-hijo
		firstWord("El-hijo de rana", "-") = El
*/
function firstWord($text, $separator = " "){
	$wordSplit = explode($separator,trim($text));
	return $wordSplit[0];
}

/*> hex2str($hex): Convierte una expresión hexadecmail en texto
	- Parametros
		[requerido] $hex (string) El texto a modificar
	- Valores Devueltos
		Tipo: String
		El texto decodificado desde hexadecimal
	- Ej:
		hex2str("55 6E 20 6E 6F 6D 62 72 65 20 72 6F 6A 6F 20 C7 BC") = Un nombre rojo Ǽ
*/
function hex2str(string $hex){
	$ascii='';
	$hex=str_replace(" ", "", $hex);
	for($i=0; $i<strlen($hex); $i = $i+2){
		$ascii .= chr(hexdec(substr($hex, $i, 2)));
	}
	return $ascii;
}

/*> letter($string, $numbers): Devuelve solo las letras, letras con
	acento, letras con dieresis o números de un texto
	- Parametros
		[requerido] $string (string) El texto a modificar
		[opcional] $numbers (bool) Permite o no devolver numeros
	- Valores Devueltos
		Tipo: String / Bool
		El texto con solo letras o FALSE en caso que no hayan letras
	- Ej:
		letters("Una fábula pinguïnica de $10k USD") = UnafábulapinguïnicadekUSD
		letters("Una fábula pinguïnica de $10k USD",TRUE) = Unafábulapinguïnicade10kUSD
*/
// 
function letters($string, $numbers = FALSE){
	if($numbers){
		$rexexp="[^/sa-zA-Z0-9á-úÁ-Úä-üÄ-Ü']";
	}else{
		$rexexp="[^/sa-zA-Zá-úÁ-Úä-üÄ-Ü']";
	}
	$string = str_replace(array('¨','´'),"",$string);
	$string = mb_ereg_replace($rexexp,"",$string);
	if($string == ""){
		return FALSE;
	}else{
		return $string;
	}
}

/*> mb_ucfirst($text, $encoding, $tolower): Cambia a mayúscula la primera letra de un texto
	- Parametros
		[requerido] $text (string) El texto a modificar
		[opcional] $encoding (string) {predet: "UTF-8"} La codificación del texto que se usara en la conversión
		[opcional] $tolower (bool) {predet: FALSE} Especifica si el resto de caracteres se cambiarán a minúsculas o se dejan tal cual estén
	- Valores Devueltos
		Tipo: String
		El texto convertido
	- Ej:
		mb_ucfirst("Único día de compras? ¡APROVECHA!',"UTF-8",FALSE) = Único día de compras? ¡APROVECHA!
		mb_ucfirst("Único día de compras? ¡APROVECHA!") = Único día de compras? ¡aprovecha!
*/
if (!function_exists('mb_ucfirst')) {
	function mb_ucfirst(string $str, string $encoding = "UTF-8", bool $lower_str_end = TRUE) {
		$first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
		$str_end = "";
		if ($lower_str_end){
			$str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
		}else{
			$str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
		}
		$str = $first_letter.$str_end;
		return $str;
	}
}

/*> str_lreplace($search, $replace, $subject): Reemplaza solo la ultima ocurrencia de un texto
	- Parametros
		[requerido] $search (string) El texto a buscar
		[requerido] $replace (string) El texto de reemplazo
		[requerido] $subject (string) El texto a modificar
	- Valores Devueltos
		Tipo: String
		El texto reemplazado su se encuentra una ocurrencia
	- Ej:
		str_lreplace("un","otro","En un agujero hay un animal") = En un agujero hay otro animal

		stripAccents("Un único pingüino VELEÑO visto en Æon Flux",TRUE) = Un unico pinguino VELEÑO visto en AEon Flux
*/
function str_lreplace($search, $replace, $subject){
    $pos = strrpos($subject, $search);

    if($pos !== false){
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }
    return $subject;
}

/*> stripAccents($string, $spanish): Reemplaza todos los caracteres con acento o grafemas por sus caracteres mas cercanos o sin acento
	- Parametros
		[requerido] $string (string) El texto a modificar
		[opcional] $spanish (bool) {predet: FALSE} Especifica si se convierte (TRUE) o no (FALSE) la eñe
	- Valores Devueltos
		Tipo: String
		El texto convertido
	- Ej:
		stripAccents("Un único pingüino VELEÑO visto en Æon Flux") = Un unico pinguino VELENO visto en AEon Flux
		stripAccents("Un único pingüino VELEÑO visto en Æon Flux",TRUE) = Un unico pinguino VELEÑO visto en AEon Flux
*/
function stripAccents(string $string, $spanish=FALSE){
	$unwanted_array = array(
		'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Ā'=>'A', 'Ă'=>'A', 'Ą'=>'A', 'Ǎ'=>'A', 'Ǻ'=>'A',
		'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'ā'=>'a', 'ă'=>'a', 'ą'=>'a', 'ǎ'=>'a', 'ǻ'=>'a',

		'Æ'=>'AE', 'Ǽ'=>'AE',
		'æ'=>'ae', 'ǽ'=>'ae',

		'Þ'=>'B',
		'þ'=>'b',

		'Ç'=>'C', 'Ć'=>'C', 'Ĉ'=>'C', 'Ċ'=>'C', 'Č'=>'C',
		'ç'=>'c', 'ć'=>'c', 'ĉ'=>'c', 'ċ'=>'c', 'č'=>'c',

		'Ð'=>'D', 'Ď'=>'D',
		'ď'=>'d', 'đ'=>'d',

		'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ē'=>'E', 'Ĕ'=>'E', 'Ė'=>'E', 'Ę'=>'E', 'Ě'=>'E',
		'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ē'=>'e', 'ĕ'=>'e', 'ė'=>'e', 'ę'=>'e', 'ě'=>'e',

		'ƒ'=>'f',

		'Ĝ'=> 'G', 'Ğ'=> 'G', 'Ġ'=> 'G', 'Ģ'=> 'G',
		'ĝ'=> 'g', 'ğ'=> 'g', 'ġ'=> 'g', 'ģ'=> 'g',

		'Ĥ'=>'H', 'Ħ'=>'H',
		'ĥ'=>'h', 'ħ'=>'h',

		'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ĩ'=>'I', 'Ī'=>'I', 'Ĭ'=>'I', 'Į'=>'I', 'İ'=>'I', 'Ǐ'=>'I',
		'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ĩ'=>'i', 'ī'=>'i', 'ĭ'=>'i', 'į'=>'i', 'ı'=>'i', 'ǐ'=>'i',

		'Ĳ'=>'IJ', 'Ĵ'=>'J',
		'ĳ'=>'ij', 'ĵ'=>'j',

		'Ķ'=>'K',
		'ķ'=>'k',

		'Ĺ'=>'L', 'Ļ'=>'L', 'Ľ'=>'L', 'Ŀ'=>'L',
		'ĺ'=>'l', 'ļ'=>'l', 'ľ'=>'l', 'ŀ'=>'l', 'Ł'=>'l', 'ł'=>'l',

		'Ń'=>'N', 'Ņ'=>'N', 'Ň'=>'N',
 		'ń'=>'n', 'ņ'=>'n', 'ň'=>'n', 'ŉ'=>'n',

		'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ō'=>'O', 'Ŏ'=>'O', 'Ő'=>'O', 'Œ'=>'OE', 'Ơ'=>'O', 'Ǒ'=>'O', 'Ǿ'=>'O',
		'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ō'=>'o', 'ŏ'=>'o', 'ő'=>'o', 'œ'=>'oe', 'ơ'=>'o', 'ǒ'=>'o', 'ǿ'=>'o', 'ð'=>'o',

		'Ŕ'=>'R', 'Ŗ'=>'R', 'Ř'=>'R',
		'ŕ'=>'r', 'ŗ'=>'r', 'ř'=>'r',

		'Š'=>'S', 'Ś'=>'S', 'Ŝ'=>'S', 'Ş'=>'S',
		'š'=>'s', 'ś'=>'s', 'ŝ'=>'s', 'ş'=>'s', 'ſ'=>'s',

		'ß'=>'ss',

		'Ţ'=>'T', 'Ť'=>'T', 'Ŧ'=>'T',
		'ţ'=>'t', 'ť'=>'t', 'ŧ'=>'t',

		'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ũ'=>'U', 'Ū'=>'U', 'Ŭ'=>'U', 'Ů'=>'U', 'Ű'=>'U', 'Ų'=>'U', 'Ư'=>'U', 'Ǔ'=>'U', 'Ǖ'=>'U', 'Ǘ'=>'U', 'Ǚ'=>'U', 'Ǜ'=>'U',
		
		'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ũ'=>'u', 'ū'=>'u', 'ŭ'=>'u', 'ů'=>'u', 'ű'=>'u', 'ų'=>'u', 'ư'=>'u', 'ǔ'=>'u', 'ǖ'=>'u', 'ǘ'=>'u', 'ǚ'=>'u', 'ǜ'=>'u',

		'Ŵ'=>'W',
		'ŵ'=>'w',

		'Ý'=>'Y', 'Ÿ'=>'Y', 'Ŷ'=>'Y',
		'ý'=>'y', 'ÿ'=>'y', 'ŷ'=>'y',

		'Ž'=>'Z', 'Ź'=>'Z', 'Ż'=>'Z',
		'ž'=>'z', 'ź'=>'z', 'ż'=>'z'
	);

	$n_accent = array(
		'Ñ'=>'N',
		'ñ'=>'n',
	);
	if(!$spanish){
		$unwanted_array = array_merge($unwanted_array, $n_accent);
	}

	return strtr($string, $unwanted_array);
}

/*> str2hex($string): Convierte un texto a su equivalente en hexadecimal
	- Parametros
		[requerido] $string (string) El texto a convertir
	- Valores Devueltos
		Tipo: String
		El valor hexadecimal del texto
	- Ej:
		str2hex("55 6E 20 6E 6F 6D 62 72 65 20 65 6E 20 C7 BC 6F 6E 20 46 6C 75 78") = Un nombre en Ǽon Flux
*/
function str2hex($ascii){
	$hex = '';
	for ($i = 0; $i < strlen($ascii); $i++){
		$byte = strtoupper(dechex(ord($ascii[$i])));
		$byte = str_repeat('0', 2 - strlen($byte)).$byte;
		$hex .= $byte." ";
	}
	return trim($hex);
}

?>

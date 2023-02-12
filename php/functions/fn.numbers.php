<?php

/*> numbers($string): Devuelve SOLO los números de un texto
	- Parametros
		[requerido] $string (string) El texto a modificar
	- Valores Devueltos
		Tipo: String/Boleano
		Solo los números de un texto, si no hay numeros de vuelve FALSE
	- Ej:
		numbers("5,000 Escarabajos") = 5000
		numbers("cinco mil Escarabajos") = FALSE
	*/
// Devuelve SOLO los números de un texto o FALSE si no encuetra ninguno
function numbers($string)
{
	$regexp = "[^0-9]";
	$res = mb_ereg_replace($regexp, "", $string);
	if ($res == "") {
		return FALSE;
	} else {
		return $res;
	}
}

/*> money($num,$cent,$currency): Convierte un numero a su equivalnete en moneda redondeando cualquier cantidad de la mitad para arriba
	- Parametros
		[requerido] $num (string/int/float) El número "monetizar"
		[opcional] $cent (int) {predet: "0"} El numero de decimales a utilizar en la salida
		[opcional] $currency (string) {predet: null} Permite especificar un nombre de moneda.
	- Valores Devueltos
		Tipo: String
		El número convertido en formato de moneda
	- Ej:
        money("4078.85",2,'COP') = $4,078.85 COP
        money("4078.85",2,'€') = 4,078.85€
        money("4a078.85",2,) = $4,078.85
        money(4078.85) = $4,079
*/
function money(string $num, int $cent = 0, $currency = NULL)
{
	if (is_string($num)) {
		$regex = '[^.,0-9]';
		$num = mb_ereg_replace($regex, "", $num);
		$dot = strpos($num, ".");
		$coma = strpos($num, ",");
		if ($coma < $dot) {
			$num = str_replace(".", "-ys_dot-", $num);
			$num = str_replace(",", "", $num);
			$num = str_replace("-ys_dot-", ".", $num);
		} else {
			$num = str_replace(",", "-ys_coma-", $num);
			$num = str_replace(".", "", $num);
			$num = str_replace("-ys_coma-", ".", $num);
		}
		$num = (float) $num;
	} elseif (is_int($num)) {
		$num = (float) $num;
	}
	$currency = strtoupper($currency);
	if ($cent == 0) {
		$num = round($num);
	} else {
		$num = round($num, $cent);
	}
	$num = number_format($num, $cent);
	if (is_null($currency)) {
		return "$" . $num;
	} elseif (mb_strtolower($currency) == "euro" || mb_strtoupper($currency) == "€") {
		return $num . "€";
	} else {
		return "$" . $num . " " . $currency;
	}
}

/*> moneyToFloat($string): Convierte string de tipo moneda
    (decimales separados con punto y sepradores de miles con coma) a flotante
	- Parametros
		[requerido] $string La cadena de texto de tipo moneda
	- Valores Devueltos
		Tipo: float
		La cantidad sin simbolos
	- Ej:
        moneyToFloat("$4,078.85 COP") = $4,078.85 COP
*/
function moneyToFloat($string)
{
	$regex = '[^.0-9]';
	return (float) mb_ereg_replace($regex, "", $string);
}
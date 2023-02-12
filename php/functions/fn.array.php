<?php
/*> multiexplode($delimiters,$string): Fracciona texto con multiples delimitadores como lo haría la función explode
	- Parametros
		[requerido] $delimiters (array) Arreglo con los delimitadores
		[requerido] $string (string) El texto a fraccionar
	- Valores Devueltos
		Tipo: array
		Arreglo fraccionado por cada delimitador
	- Ej:
		multiexplode(array("cada", ".", "y"),"Estaba yo, en cada paso. Haciendo cosas"))
		Resultado:
		Array (
			[0] => Estaba
			[1] => o, en
			[2] => paso
			[3] => Haciendo cosas
		)
*/
function multiexplode(array $delimiters,string $string) {
	$string = str_replace($delimiters, $delimiters[0], $string);
	return  explode($delimiters[0], $string);
}

/*> recursive_array_search($needle,$haystack): Muestra la clave en la primera dimensión de un
	arreglo donde se encuentra una coincidencia exacta. Distingue minúsculas y mayusculas
	- Parametros
		[requerido] $needle (string) La cadena a buscar
		[requerido] $haystack (array) El arreglo donde buscar
	- Valores Devueltos
		Tipo: string/int/bool
		String / int : El nombre de la clave principal donde se encuentra la coincidencia
		Bool: Devuelve FALSE en caso de no encontrar una coincidencia
	
	- Ej:
		$defs = array(
			"Colombia" => array(
				"Cundinamarca" => array(
					"Bogota"
				)
			),
			"Peru",
			"Panama",
		);
		recursive_array_search("Bogota",$defs) = "Colombia" (string)
		recursive_array_search("Bogota",$defs["Colombia"]) = "Cundinamarca" (string)
		recursive_array_search("Peru",$defs) = 0 (int)
		recursive_array_search("Venezuela",$defs) = FALSE (bool)
		recursive_array_search("bogotá",$defs["Colombia"]) = FALSE (bool) (minúsculas y tilde)
*/
function recursive_array_search(string $needle, array $haystack) {
	foreach($haystack as $key=>$value) {
		$current_key=$key;
		if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
			return $current_key;
		}
	}
	return false;
}

/*> value_array_search($value,$haystack): devuelve la clave primaria donde se encuentre una cadena de busqueda dentro de un arreglo
	- Parametros
		[requerido] $value (string) La cadena a buscar
		[requerido] $haystack (array) El arreglo donde buscar
	- Valores Devueltos
		Tipo: string/int/bool
		String / int : El nombre de la clave principal donde se encuentra la coincidencia
		Bool: Devuelve FALSE en caso de no encontrar una coincidencia
	
	- Ej:
		$defs = array(
			"Colombia" => array(
				"Cundinamarca" => array(
					"Bogota"
				)
			),
			"Peru",
			"Panama",
		);
		value_array_search("Bog",$defs) = "Colombia" (string)
		value_array_search("Bog",$defs["Colombia"]) = "Cundinamarca" (string)
		value_array_search("Pe",$defs) = 0 (int)
		value_array_search("Vene",$defs) = FALSE (bool)
		value_array_search("bogotá",$defs["Colombia"]) = FALSE (bool) (minúsculas y tilde)
*/
function value_array_search(string $value, array $haystack){
	foreach($haystack as $key=>$data) {
		$current_key=$key;
		if(strpos($key,$value) || strpos($data,$value) !== FALSE OR (is_array($data) && value_array_search($value,$data) !== false)){
			return $current_key;
		}
	}
	return false;
}
?>

<?php

/*> getVars(): imprime las variables usadas hasta donde es llamada la función
*/
function getVars(array $vars, $all = FALSE)
{
	// vars debe ser el arreglo que pasa la función get_defined_vars() de PHP, para enviar las variables usadas en ese contexto o funcion.
	//Ej: getVars(get_defined_vars());
	$vars_fn_getVars = array_merge($GLOBALS, $vars);
	$const = get_defined_constants(TRUE);
	$result = array();
	if (!$all) {
		//Composer autoload variable
		if (isset($vars_fn_getVars['__composer_autoload_files'])) {
			unset($vars_fn_getVars['__composer_autoload_files']);
		};
		unset($vars_fn_getVars['_GET']);
		unset($vars_fn_getVars['_POST']);
		unset($vars_fn_getVars['_COOKIE']);
		unset($vars_fn_getVars['_FILES']);
		unset($vars_fn_getVars['_SERVER']);
		unset($vars_fn_getVars['GLOBALS']);
		$const = $const['user'];
	}
	foreach ($vars_fn_getVars as $group => $value) {
		if (!empty($vars_fn_getVars[$group])) {
			$result[$group] = $vars_fn_getVars[$group];
		}
	}
	if (empty($result)) {
		$result = "\n\n<b>No vars previously declared</b>\n\n";
	}
	$file = str_replace("\\", "/", debug_backtrace()[0]['file']);
	$file = "<strong>Definitions before: " . substr($file, strrpos($file, "/") + 1) . " (" . debug_backtrace()[0]['line'] . ")</strong>";
	echo '<pre style="text-align: left;font-size: 14px !important">';
	echo $file . "<br><strong>Vars:</strong> ";
	print_r($result);
	echo "<br><strong>Consts:</strong> ";
	print_r($const);
	echo "</pre>";
	unset($vars_fn_getVars);
	unset($result);
}
/*> line(): imprime en pantalla la linea desde donde se llama la funcion.
	- Parametros
		[opcional] $comment (string) Texto que se agregará a la salida
	- Valores Devueltos
		Tipo: ninguno (impresion en pantalla)
	- Ej:
		line() = Called from script.php (36)
		line("Terminado") = Terminado - script.php (36)
*/
function line($comment = "")
{
	$separator = FETCH ? "\n\n" : "<br>";
	if ($comment == "") {
		$comment = "Called from ";
	} else {
		$comment = trim($comment) . " - ";
	}
	$file = str_replace("\\", "/", debug_backtrace()[0]['file']);
	echo $comment . substr($file, strrpos($file, "/") + 1) . " (" . debug_backtrace()[0]['line'] . ")" . $separator;
}

/*> prePrint($obj_arr,$comment,$return): hace una impresion del pirmer parametro para analisis
	- Parametros
		[requerido] $obj_arr (array) El elemeto que se analizará
		[opcional] $comment (string/bool) Texto que se agregará a la salida, si se pasa TRUE (bool) retornará el resultado
		[opcional] $return (string) El texto a fraccionar
	- Valores Devueltos
		Tipo: ninguno (impresion en pantalla)/array
		Si se pasa el parametro $return o $comment como boleano se devuelve lo que se pasó
		en el primer parametro como arraglo, de lo contrario se muestra en pantalla un
		análisis (archivo, linea y tipo de variable) delo enviado en el primer parámetro.
	- Ej:
		prePrint(array("piña", "jamon", "queso"), "Me huele a pizza");
		Resultado:
		File: archivo-y-linea-de-llamado-a-la-funcion.php (37)
		 - Me huele a pizza - Array
		(
			[0] => piña
			[1] => jamon
			[2] => queso
		)
	- Ej 2:
		prePrint(array("Pizza hawaiana"), TRUE)
		Resultado:
		Array (
			[0] => Pizza hawaiana
		)

*/
function prePrint($obj_arr, $comment = NULL, bool $return = FALSE)
{

	//opcion para devolver el codigo y ser impreso luego
	if (!is_string($comment) && is_bool($comment) && $comment) {
		$return = TRUE;
		$comment = NULL;
	}

	$type_obj_arr = gettype($obj_arr);
	switch (gettype($obj_arr)) {
		case 'unknown type';
			$tittle = '{unknown type} - ';
			break;

		case 'NULL':
			$tittle = 'NULL - Sin nada enviado o resultado NULO';
			break;

		case 'resource':
		case 'object':
		case 'array':
			$tittle = '';
			break;

		case 'double':
			$tittle = '<strong>FLOAT</strong>: ';
			break;

		case 'string':
		case 'integer':
			$tittle = "<strong>" . mb_strtoupper($type_obj_arr) . ": </strong>";
			break;
		case 'boolean':
			$tittle = "<strong>" . mb_strtoupper($type_obj_arr) . ": </strong>";
			$obj_arr === TRUE ? $obj_arr = 'TRUE' : NULL;
			$obj_arr === FALSE ? $obj_arr = 'FALSE' : NULL;
			break;

		default:
			$comment = 'buuuuuuuuuuu';
			$tittle = "<strong>ERROR!</strong> - ";
			$obj_arr = "Se pasó un parámetro NO VALIDO a la función. ";
	}
	$file = str_replace("\\", "/", debug_backtrace()[0]['file']);
	$file = "<strong>File: " . substr($file, strrpos($file, "/") + 1) . " (" . debug_backtrace()[0]['line'] . ")</strong>";
	$tittle = $file . "<br>" . $tittle;
	if (!empty($comment)) {
		$tittle = "$tittle - $comment - ";
	}
	$static_obj_arr = array();
	if ($type_obj_arr == 'array' || $type_obj_arr == 'object') {
		if ($type_obj_arr == 'object') {
			$class = new ReflectionClass(get_class($obj_arr));
			$tmp_obj_arr = $class->getStaticProperties();
			if (!FETCH) {
				array_walk_recursive($tmp_obj_arr, "filter_html");
			}
			$static_obj_arr = $tmp_obj_arr;
		}
		$tmp_obj_arr = $obj_arr;
		if (!FETCH) {
			array_walk_recursive($tmp_obj_arr, "filter_html");
		}
		$obj_arr = $tmp_obj_arr;
		//		$obj_arr = array_merge($tmp_obj_arr,$static_obj_arr);
	} elseif ($type_obj_arr == 'string' && !FETCH) {
		$obj_arr = htmlspecialchars($obj_arr, ENT_QUOTES, 'UTF-8');
	}
	if (!$return && !FETCH) {
		echo '<pre style="text-align: left;font-size: 14px !important">';
		echo $tittle;
		print_r($obj_arr);
		if (sizeof($static_obj_arr) > 0) {
			echo "    <b>Static vars of class " . get_class($obj_arr) . ":</b> ";
			print_r($static_obj_arr);
		}
		echo "</pre>";
	} elseif (!$return && FETCH) {
		$tittle = str_replace("<br>", "\n", $tittle);
		echo "----- " . strip_tags($tittle);
		print_r($obj_arr);
		if (sizeof($static_obj_arr) > 0) {
			echo "    <b>Static vars of class " . get_class($obj_arr) . ":</b> ";
			print_r($static_obj_arr);
		}
		echo "\n\n";
	} else {
		$captured = '<pre style="text-align: left;font-size: 14px !important">';
		$captured .= $tittle;
		$captured .= print_r($obj_arr, TRUE);
		if (sizeof($static_obj_arr) > 0) {
			$captured .= "    <b>Static vars of class " . get_class($obj_arr) . ":</b> ";
			$captured .= print_r($static_obj_arr, TRUE);
		}
		$captured .= "</pre>";
		return $captured;
	}
}

// Funcion utilizada dentro de prePrint para convertir (mostrar) etiquetas HTML
// a su equivalente en caracteres html del contenido de la variable.
function filter_html(&$value)
{
	if (is_object($value) || is_array($value)) {
		$value = print_r($value, TRUE);
	} elseif (is_bool($value)) {
		$value = $value ? 'TRUE' : 'FALSE';
	} elseif (is_resource($value)) {
		$value = "Resource";
	} else {
		$value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
	}
}

/*> stop($comment): Detiene la ejecución del programa, mostrando el archivo y linea
	donde se llamó la función
	- Parametros
		[opcional] $comment (string) Texto que se agregará a la salida
	- Valores Devueltos
		Ninguno (Impresión en pantalla)
	- Ej:
		stop("Paramos!");
		Resultado:
		Stop in functions.php (47) - Paramos!
)*/
function stop($comment = NULL)
{
	$file = str_replace("\\", "/", debug_backtrace()[0]['file']);
	$file = "<br><b>Stop in " . substr($file, strrpos($file, "/") + 1) . " (" . debug_backtrace()[0]['line'] . ")";
	$file .= is_null($comment) ? '' : ' - ' . $comment;
	$file .= "</b>";
	FETCH ? $file = strip_tags(str_replace("<br>", "\n", $file)) . "\n" : NULL;
	die($file);
}


/*> trace(): hace una impresion de los archivos, funciones y argumentos utilizados hasta encontrar
	la ejecución de la función
	- Parametros
		Ninguno
	- Valores Devueltos
		Ninguno (Impresión en pantalla)
	- Ej:
		trace();
		Resultado:
		Trace
		Array
		(
			[Trace called from] => Array
				(
					[file] => archivo-y-linea-de-llamado-a-la-funcion.php -> line: 37
					[function] => trace()
				)

			[Back 1] => Array
				(
					[file] => otro-archivo.php -> line: 24
					[args] => Array
						(
							[0] => /public_html/php/archivo-y-linea-de-llamado-a-la-funcion.php
						)

					[function] => require_once()
				)
			)

)*/

function trace()
{
	$traced_files = array();
	foreach (debug_backtrace() as $file => $data) {
		unset($data['object']);

		$data['file'] = array_reverse(preg_split('/[\\\\\/]/', $data['file']))[0] . " -> line: " . $data['line'];
		unset($data['line']);

		if (sizeof($data['args']) == 0) {
			unset($data['args']);
		}

		if (isset($data['class'])) {
			$data['function'] = $data['class'] . $data['type'] . $data['function'] . "()";
			unset($data['class']);
			unset($data['type']);
		} else {
			$data['function'] = $data['function'] . "()";
		}

		if ($file == 0) {
			$traced_files['Trace called from'] = $data;
		} else {
			$traced_files['Back ' . $file] = $data;
		}
	}
	$data = prePrint($traced_files, 'Trace', TRUE);
	if (!FETCH) {
		echo '<br> <h3>Trace</h3>';
		echo '<pre style="text-align: left;font-size: 14px !important">';
		print_r($data);
		echo '</pre>';
	} else {
		echo "Trace";
		print_r($data);
	}
}
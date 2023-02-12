<?php
// FUNCION PARA AUTODETECTAR UNA CLASE SIN TENER QUE AGREGAR EL ARCHIVO
// EL ARCHIVO DE LA CLASE DEBE ESTAR DENTRO DE LA CARPETA PRINCIPAL DE PHP,
// DENTRO DE LA CARPETA MODULES Y UNA CARPETA CON EL NOMBRE DE LA CLASE.
// EL ARCHIVO DEBE INICIAR CON LA PALABRA "class" SEGUIDO DE UN PUNTO (.), EL NOMBRE
// DE LA CLASE Y FINALIZANDO CON AL EXTENSION PHP
// Ej: Clase "ejecutar" debe estar la siguiete ruta
//		[PHP_LIB]/modules/ejecutar/class.ejecutar.php
//		(PHP_LIB) es la carpeta principal de librerias PHP en proyecto
if (!function_exists('classAutoLoader')) {
	function classAutoLoader($class)
	{
		$class_name = strtolower($class);
		$path = PHP_LIB . DS . "modules" . DS . $class_name . DS . "class." . $class_name . ".php";
		if (is_file($path) && !class_exists($class_name)) include $path;
	}
}
spl_autoload_register('classAutoLoader');

//Arrays Functions
require_once(PHP_LIB . DIRECTORY_SEPARATOR . "functions/fn.array.php");

//Date functions
require_once(PHP_LIB . DIRECTORY_SEPARATOR . "functions/fn.date.php");

//Debugging functions
require_once(PHP_LIB . DIRECTORY_SEPARATOR . "functions/fn.debug.php");

//Files and folders functions
require_once(PHP_LIB . DIRECTORY_SEPARATOR . "functions/fn.file-and-folder.php");

//miscellaneous functions (not defined)
require_once(PHP_LIB . DIRECTORY_SEPARATOR . "functions/fn.misc.php");

//Numbers and currency
require_once(PHP_LIB . DIRECTORY_SEPARATOR . "functions/fn.numbers.php");

//Text relative functions
require_once(PHP_LIB . DIRECTORY_SEPARATOR . "functions/fn.text.php");
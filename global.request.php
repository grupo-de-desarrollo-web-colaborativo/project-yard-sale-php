<?php
date_default_timezone_set("America/Bogota");

//GLOBAL FETCH CONSTANT EXECUTION
if (mb_strtolower($_SERVER["REQUEST_URI"]) == "/fetch") {
	define("FETCH", TRUE);
} else {
	define("FETCH", FALSE);
}


//Folder to php library
defined('PHP_LIB') ? NULL : define('PHP_LIB', __DIR__ . DIRECTORY_SEPARATOR . "php");
//PHP library test
if (!is_dir(PHP_LIB)) {
	$msg = "PHP library folder error. Not found! <br>";
	$msg .= 'PHP_LIB = ' . PHP_LIB . "<br><br>";
	die($msg);
}

//Composer load
/**Take care about install composer dependencies on PHP_LIB folder!!!
 * then configure the version of PHP with command composer config platform.php 8.0.7
 *  to avoid update dependencies requiring higher installed php version
 */
require PHP_LIB . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

//global variables file required
if (!file_exists(PHP_LIB . DIRECTORY_SEPARATOR . "global.variables.php")) {
	die("This page cannot run without a global.variables.php file, please construct on php folder!");
} else {
	require_once(PHP_LIB . DIRECTORY_SEPARATOR . "global.variables.php");
}
require_once(PHP_LIB . DIRECTORY_SEPARATOR . "global.functions.php"); 	//Global functions files and requires
require_once(PHP_LIB . DIRECTORY_SEPARATOR . "global.definitions.php");	//Global definitions

//If the URL is a especific file or folder with PHP file, will be readed/executed

$url = trim(str_replace(SERVER_URL, "", ACTUAL_URL), "/ ");
$file = SITE_ROOT . DS . (explode("?", $url)[0]);
unset($url);
if (is_file($file) || is_dir($file)) {
	read_file($file);
	//in read_file function the execution of script ends if file exists or directory
}
unset($file);

/**************************************
 * GLOBAL CLASSES TO PROJECT OPERATION*
 **************************************/

// variable global de sesion

$session = new Session();
//variable global de usuario
$user = new User(TRUE);
/*
AJAX execution verification
*/
if ($_SERVER["REQUEST_URI"] == "/fetch") {
	stop("revisar que pasa cuando se hace una solicitud fetch");
	require_once(PHP_LIB . DS . "global.ajax.php");
	die();
} else {
	// Request() Solicita y ejecuta la página requerida en la URL, allí termina el script
	$area = new Area(TRUE);
	die();
}

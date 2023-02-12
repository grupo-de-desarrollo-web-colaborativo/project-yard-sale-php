<?php

/*****************
 * MAIN CONSTANTS *
 *****************/

defined('NOW') ? NULL : define("NOW", time());	// Actual timestamp

/***************************
 * FOLDER AND URL CONSTANTS *
 ***************************/

defined("DS") ? NULL : define('DS', DIRECTORY_SEPARATOR);	//Shortcut for Directory separator character

//Project urls
$url_tmp = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

defined('SITE_ROOT') ? NULL : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT']); // Main server folder
defined('SERVER_URL') ? NULL : define('SERVER_URL', (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['SERVER_NAME']);	// Main server URL
defined('ACTUAL_URL') ? NULL : define("ACTUAL_URL", $url_tmp); //Current navigator URL
unset($url_tmp);

defined('CSS_LIB') ? NULL : define('CSS_LIB', SERVER_URL . "/" . $css_folder);	//Main CSS Stylesheet folder
defined('JS_LIB') ? NULL : define('JS_LIB', SERVER_URL . "/" . $js_folder);		//Main JavaScript folder
defined('IMG_LIB') ? NULL : define('IMG_LIB', SERVER_URL . "/" . $img_folder);	//Main Images folder
defined('PAGES_LIB') ? NULL : define('PAGES_LIB', PHP_LIB . DS . $pages_folder);	//Main pages folder

unset($css_folder, $js_folder, $img_folder, $pages_folder);



/*********************************************
 * DEFINICION DE CONSTANTES DE BASES DE DATOS *
 *********************************************/
$users_db = array( //Users Array
	'user' => array(
		'user' => $user_db, // Guest DB user name
		'pass' => $pass_db, // Guest DB user password
	),
	'admin' => array(
		'user' => $user_db_admin, // Administrator DB user name
		'pass' => $pass_db_admin, // Administrator DB user password
	)
);
defined('USERS_DB') ? NULL : define('USERS_DB', $users_db);				// DB Users
defined('DB') ? NULL : define('DB', $db_name);								// DB Name
defined('SERVER_DB') ? NULL : define('SERVER_DB', $server_db_name);	// DB server
defined('NULL_DB') ? NULL : define('NULL_DB', $null_db);					// NULL data for null cells in DB
unset($user_db, $pass_db, $user_db_admin, $pass_db_admin, $users_db, $db_name, $server_db_name, $null_db);


/***************************************
 * DEFINICION DE CONSTANTES ADICIONALES *
 ***************************************/
defined('MAINTENANCE') ? NULL : define("MAINTENANCE", $maintenance);	//Páginas de carga rapida (prefetchs)
defined('KEY_4_PASS') ? NULL : define("KEY_4_PASS", $key_4_pass);		// Clave para encriptacion
defined('TIME_LOG_OUT') ? NULL : define("TIME_LOG_OUT", $time_log_out);	// Tiempo de espera para cierre de sesion automático
defined('AUTHOR_PAGE') ? NULL : define("AUTHOR_PAGE", $author_page);	//autor de la página
defined("PROJECT") ? NULL : define("PROJECT", $project_folder); //folder del nombre del proyecto
unset($project_folder);

defined('COPYRIGHT') ? NULL : define("COPYRIGHT", $copyright);			//Páginas de carga rapida (prefetchs)
defined('PROJECT_NAME') ? NULL : define("PROJECT_NAME", $project_name);	//nombre textual del pryecto( como se debe ver)
defined('DEVELOP_APP') ? NULL : define("DEVELOP_APP", $develop_app);	//aplicacion o programa con el que se desarrolla el codigo
unset($maintenance, $copyright, $key_4_pass, $time_log_out, $theme_color, $author_page, $project_name, $develop_app);


/***********************************************
 * DEFINICION DE CONSTANTES POR MEDIO DE CLASES *
 ***********************************************/
//Clase para detección de dispositivos moviles y navegador Y Sistema operativo
$browser = new foroco\BrowserDetection();
$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
$browser = $browser->getAll($ua);

//verificación de solicitud desde powershell
$verifyps = strpos($ua, "WindowsPowerShell/");
if (mb_strtolower($browser['browser_name']) == "unknown" && $verifyps != false) {
	$data = explode("/", substr($_SERVER["HTTP_USER_AGENT"], $verifyps));
	$browser['browser_name'] = $data[0];
	$browser['browser_version'] = $data[1];
}

unset($ua);
//Definicion si el dispositivo es o no movil
defined("IS_MOBILE") ? NULL : define("IS_MOBILE", $browser['device_type'] == 'mobile');
defined("BROWSER") ? NULL : define("BROWSER", $browser);
unset($browser);

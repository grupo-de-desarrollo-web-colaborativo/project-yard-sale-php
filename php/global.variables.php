<?php
/*
		VARIABLE DE MANTENIMIENTO
	*/
$maintenance = FALSE;

/*
		DATOS GENERALES DEL PROYECTO
	*/
$project_folder = "yardSale";		//Nombre del proyecto usado como carpeta o nombre asociativo
$project_name = "Yard Sale";		//Nombre visual del proyecto
$server_prefix = "pcbogcom";			//prefijo asignado por el ISP

/*	CARPETAS PRINCIPALES DE LA APLICACION	*/
$css_folder = 'css';				//carpeta usada para archivos de estilo
$js_folder  = 'js';				//carpeta usada para los archivos JavaScript
$img_folder = "assets/img";	//carpeta para las imagenes globales de la plagina
$pages_folder = "pages";		//Carpeta donde se guardarán los archivos de las páginas o áreas
/*
		DATOS PARA LA BASE DE DATOS
	*/
$db_name = $server_prefix . '_null';				//Nombre de la DB
$user_db_admin  = $server_prefix . "_null";		//Usuario administrador de la DB
$pass_db_admin = "pass_null";						//Contraseña para el administrador de la DB
$user_db  = $server_prefix . "_null";				//Usuario usuario normal de la DB
$pass_db = "pass_null";								//Contraseña para el usuario normal de la DB
$server_db_name = 'localhost';					//Servidor de la DB (generalmente no necesita cambio)
$null_db = 'NULL_DB';								//Dato nulo en DB

/*
		DATOS ADICIONALES PARA LA PAGINA
	*/
$author_page = "Andres Salazar";	//autor de la página
$key_4_pass = 'key_null';					// clave para codificación de contraseñas
$time_log_out = 3600; //60 mins			//Tiempo de espera para cerrar session obligatoriamente en segundos
//$time_log_out = 1; //1 seg					//Tiempo de espera temporal para pruebas
$develop_app = 'Visual Studio Code';	//Aplicacion o programa con el que se desarrolla el codigo
$copyright = "Andres Salazar (ChyBeat) | &copy; " . date('Y', time()); //Nombre de la compañia y/o año

/*
	Datos importantes para conexion a la base de datos y contraseñas
*/
if (file_exists(PHP_LIB . DIRECTORY_SEPARATOR . "db-data.php")) {
	require_once(PHP_LIB . DIRECTORY_SEPARATOR . "/db-data.php");
}

unset($server_prefix);

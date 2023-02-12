<?php

/********************************
 * FUNCIONES DE ARCHIVO Y CARPETA *
 *********************************/

/*> need($file,$required): Buscar un archivo desde la raiz del sitio ($_SERVER["DOCUMENT_ROOT"])
	- Parametros
		[requerido] $file (string) El archivo a buscar
		[opcional] $required (bool) {predet.: TRUE} Si es requerido la solicitud devolverá error en caso de no encontrar la ruta

	-Valores devueltos
		Tipo: String
		Devuelve la ruta relativa del archivo a buscar respecto a la
		carpeta desde donde se llama la función, se envíe o no $required

		Tipo: bool
		Si se pasó FALSE para $required y no se encuentra devuelve un error,
		de encontrarse devuelve el string de la ruta

	- Error
		Si la ruta no se encuentra y no se paso FALSE para $required,
		la funcion termina la ejecución con un mensaje de error con información
		desde el script que llamó la función

	- Ej:
		need("img/test.png");
		Si la ruta es encontrada devuelve algo similar a ../img/test.png
		donde "../" quiere decir que la ruta enviada (ruta y archivo) están en un nivel
		superior al actual

		Si la ruta no es encontrada y se paso $required como FALSE, devuelve FALSE (BOOL)
*/

function need($file, bool $required = TRUE)
{
	//estandarizando el nombre de $file a que tenga el separador de directorios del sistema operativo
	$file = trim($file, "/\\");
	$file = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $file);

	//obteniendo el directorio raiz desde donde ejecuta el servidor el home de la pagina (public_html)
	$root = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $_SERVER["DOCUMENT_ROOT"]);
	//obteniendo el arreglo de todos los archivos dentro del servidor
	$all_files  = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));

	$result = [];
	$error = FALSE;
	$msg = "";

	//buscando las coincidencias del archivo dentro de root
	foreach ($all_files as $archive) {
		$archive = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $archive);
		$path = str_replace($root, "", $archive);
		if (str_ends_with($path, $file)) {
			$result[] = trim($root . $path, "/\\");
		}
	}

	//variable para el manejo de error (desde donde se llamó la función)
	$file_script = str_replace("\\", "/", debug_backtrace()[0]['file']);
	$file_script = "<br><b>Called from " . substr($file_script, strrpos($file_script, "/") + 1) . " (" . debug_backtrace()[0]['line'] . ")</b>";

	if (count($result) == 1) {
		if (strstr($result[0], $file) && $required) {
			return $result[0];
		}
	} elseif (count($result) > 1) {
		$error = TRUE;
		$msg = "<br>Two or more coincidences found: <br><pre>" . print_r($result, TRUE) . "</pre>" . $file_script;
	} else {
		$error = TRUE;
		$msg = "<br>Not Found: \"" . $file . "\"." . $file_script;
	}
	if ($error && !FETCH && $required) {
		die($msg);
	}
	return FALSE;
}

function read_file($file)
{
	$mime = mime_content_type($file);
	/*
	Tipos mime como texto
		css		text/plain
		txt		text/plain
		js			text/plain
		html		text/html
		shtml		text/html
	
	Tipós $mime para devolver ejecutados
		php		text/x-php
	
	Tipós $mime para devolver en binario
		svg		image/svg+xml	
		gif		image/gif
		png		image/png
		jpg		image/jpeg
		jpeg		image/jpeg
		ico		image/x-icon
		mp4		video/mp4
		ogg		audio/ogg
		oga		audio/ogg
		mp3		audio/mpeg
*/
	if (strpos($mime, "text/x-php") !== FALSE) {
		unset($mime);
		require($file);
		die();
	} else if ($mime == 'text/plain') {
		$ext = pathinfo($file)['extension'];
		if ($ext == 'css') {
			$mime = "text/css";
		}
		if ($ext == 'js') {
			$mime = "text/javascript";
		}
		if ($ext == 'htm' || $ext == 'html') {
			$mime = "text/html";
		}

		header('Content-type: ' . $mime);
		$data = file_get_contents($file);
		echo $data;
		die();
	} else if ($mime != "directory") {
		//Si no es un directorio puede ser cualquier otro archivo, se tratará según el MIME que detecte PHP
		header('Content-type: ' . $mime);
		$data = file_get_contents($file);
		echo $data;
		die();
	} else if ($mime == "directory") {
		if (file_exists($file . DS . "index.php")) {
			read_file($file . DS . "index.php");
			die();
		}
		if (file_exists($file . DS . "index.html")) {
			read_file($file . DS . "index.html");
			die();
		}
	}
}
<?php
/*> cleanUrl($url): Elimina la parte de una consulta o un formulario en una URL
	- Parametros
		[opcional] $url (string) {predet: La URL actual en el navegador} La URL a limpiar
	- Valores Devueltos
		Tipo: string
		La url sin los datos de consulta o formulario
	- Ej:
	cleanUrl("https://www.google.com/search?q=fisica+cuantica") = https://www.google.com/search
*/
function cleanUrl(string $url = NULL){
	if(is_null($url)){
		$url = (isset($_SERVER['HTTPS']) ? "https" : "http")."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
	$question = strpos($url,'?');
	$amp = strpos($url,'&');
	$question = $question != FALSE ? $question : 9999999999999;
	$amp = $amp != FALSE ? $amp : 9999999999999;

	$query = min($question,$amp);

	if($query !== FALSE){
		$url = substr($url, 0,$query);
	}
	return $url;
}

/*> redirect($url): redirecciona el navegador a una URL
	- Parametros
		[opcional] $url (string) {predet: La URL principal del servidor} La URL para redirección
	- Valores Devueltos
		Tipo: ninguno
		No devuelve ningun valor, simplemente redirige el navegador
	- Ej:
	redirect("https://www.google.com/") -> Ves la pagina de google
*/

function redirect($url = NULL){
	if(is_null($url)){
		$url = (isset($_SERVER['HTTPS']) ? "https" : "http")."://".$_SERVER['SERVER_NAME'];
	}
	header("Location: $url", true, 301);
}

/******************FUNCIONES PARA MOVER */

function json_answer(array $res_json){
	header('Content-Type: application/json');
	//Devuelve o "escribe" objeto Json
	$json = json_encode($res_json, JSON_FORCE_OBJECT);

	if ($json === false) {
		// Avoid echo of empty string (which is invalid JSON), and
		// JSONify the error message instead:
		$json = json_encode(["jsonError" => json_last_error_msg()]);
		if ($json === false) {
			// This should not happen, but we go all the way now:
			$json = '{"jsonError":"unknown"}';
		}
		// Set HTTP response status code to: 500 - Internal Server Error
		http_response_code(500);
	}
	echo $json;
	die();
}
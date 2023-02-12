<?php

/**
 * Clase de redirección para las URL
 */
class Area
{
	public $area;						//Área, categoría o solicitud en la URL
	public $error = FALSE;			//El tipo de error que se presenta
	public $file = FALSE;			//Especifica si la solicitud es un archivo o no
	private $areasFolder;			//Carpeta con los archivos de cada área
	private $mainPageFile = "_main.{area_name}.php";	//Nombre del archivo principal de ejecución en el área
	public $urlToPage = array();	//Arreglo que contiene la información de las clases utilizadas según la URL

	//Propiedades para la detección del navegador
	private $nav;						//Nombre del navegador que se usa
	private $navVer;					//Versión del navegador que se usa


	//initial area debe ser pasado exclusivamente desde global.request.php y como booleano TRUE, al pasarse false, no se inicializa nada y puede usarse para llamar las funciones de la clase
	public function __construct(bool $initialArea = FALSE)
	{
		if ($initialArea) {
			$this->get_url_area();
			$this->areasFolder = PAGES_LIB;
			$this->get_areas_folder_list();
			$this->navDetect(); //deteccón del navegador por si es obsoleto
			$this->get_area_data();
		}
	}

	public function get_current_area()
	{
		$this->get_url_area();
		return $this->area;
	}
	private function get_area_data()
	{
		//Obtener el dato del área según la condición
		if (!$this->file) {
			//obtener los datos del área
			if (array_key_exists($this->area, $this->urlToPage) === FALSE) {
				//si el área no existe muestra home con error 404 mostrado en Home
				$this->area = "404";
			}
			//Armado del archivo a llamar según datos
			$reqFile = $this->areasFolder . DS . $this->area . DS . $this->urlToPage[$this->area]; // carpeta "pages" de la libreria php donde se deben almacenar los archivos de las páginas
			require_once($reqFile);
		} else {
			stop("Ha ocurrido un error, existen un área y un nombre de archivo iguales 😅");
		}
	}

	private function get_areas_folder_list()
	{
		foreach (scandir(PAGES_LIB) as $key => $name) {
			if (!in_array($name, array(".", ".."))) {
				$element = PAGES_LIB . DS . $name;
				$mainFile = str_replace('{area_name}', $name, $this->mainPageFile);
				if (is_dir($element)) {
					if (!file_exists($element . DS . $mainFile)) {
						$message = "<b>Fatal error</b>:  \"$mainFile\" file not exists for <b>$name</b> page.<br><br>\n\n";
						$message .= "Remove folder \"$name\" in \"" . PAGES_LIB . "\" or create the file<br>\n";
						$message .= "\"$mainFile\". That file is <b>required</b> to show the page.<br><br>\n\n";
						$message .= "Avoid to create empty or innecesary folders in \"" . PAGES_LIB . "\".<br><br>\n\n";
						$message .= "Error triggered ";
						trigger_error($message, E_USER_ERROR);
					}
					$this->urlToPage[$name] = $mainFile;
				}
			}
		}
	}

	private function get_url_area()
	{
		//Obtener el nombre de área y clase (deben ser iguales), que se llamará durante la ejecución del programa

		$url = trim(str_replace(SERVER_URL, "", ACTUAL_URL), "/ ");
		$pos_slash = strpos($url, '/') === FALSE ? 9 * 99999 : strpos($url, '/');
		$pos_question = strpos($url, '?') === FALSE ? 9 * 99999 : strpos($url, '?');
		$pos_hashtag = strpos($url, '#') === FALSE ? 9 * 99999 : strpos($url, '#');
		if ($pos_slash !== FALSE || $pos_question !== FALSE) {
			$area = substr($url, 0, min($pos_slash, $pos_question, $pos_hashtag));
		}
		$this->area = ($area == '') ? 'home' : $area;
	}

	private function navDetect()
	{
		//navegadores requeridos para NO mostrar pagina de navegador obsoleto
		$reqBrowsers = array(
			"Other" => 9999,
			"Android Browser" => 100,
			"Chrome" => 91,
			"Edge" => 91,
			"Firefox" => 59,
			"Opera" => 78,
			"Safari Mobile" => 8,
			"Safari" => 13,
		);
		//obtener el nombre del navegador
		$this->nav = (!array_key_exists(BROWSER['browser_name'], $reqBrowsers)) ? "Other" : BROWSER['browser_name'];

		//obtener versión del navegador
		$this->navVer = (BROWSER['browser_version'] != 0 ? BROWSER['browser_version'] : NULL)
			?? (BROWSER['browser_chromium_version'] != 0 ? BROWSER['browser_chromium_version'] : NULL)
			?? 0;
		if ((int) $this->navVer < (int) $reqBrowsers[$this->nav]) {
			$this->area = "nav_outdate";
		}
	}

	// fin de clase area
}

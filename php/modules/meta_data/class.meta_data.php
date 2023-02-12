<?php

class meta_data
{

	//Atributos dinamicos
	public $title;			//Titulo de la página
	public $description;	//Descripción de la página max 150 caracteres
	public $keywords;		//palabras clave existentes en la página


	/*Atributos Fijos*/
	private $tplLib = __DIR__ . DS;				//carpeta de templates que le compete a la clase
	public $subject = "Yard Sale eCommerce";	//Asunto de la pagina, de lo que trata


	//atributos que se definen por contenidos de la página
	private $preconnects = array(	//Páginas a las que se conectará la web (preconnect)
		'https://fonts.googleapis.com' => array(),
		'https://fonts.gstatic.com' => array('crossorigin'),
	);

	private $prefetchs = array(); // used with link rel dns-prefetch. Usefull when requires some resources from other urls in JS

	//Tipos de letra y css externos agregadas al proyecto
	private $externalCSS = array(
		'https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&family=Roboto:wght@700&display=swap', // Google Font
	);

	//atributos que pueden incluir mas datos de los predeterminados
	public static $cssFiles = array(		//Archivos de estilo de la pagina
		PROJECT . ".css" => array(	//Archivo principal global del proyecto
			'view' => "all",
			'media' => "all",
		),
		"tailwind.css" => array(	//Archivo compilado de tailwind
			'view' => "all",
			'media' => "all",
		),

	);

	//Archivos JavaScript de la pagina
	public static $jsFiles = array(
		PROJECT . ".js" => array(
			'view' => 'all',
			'async' => FALSE,
			'defer' => TRUE,
			'head' => TRUE
		),
	);

	function __construct($title = NULL, $description = NULL, $keywords = NULL)
	{
		$this->title = !is_null($title) ? $title : PROJECT_NAME;
		$this->description = $description ?? $this->description;
		$this->keywords = $keywords ?? $this->keywords;
	}

	public function add_css($new_css_file, $visualizacion = NULL)
	{
		/*
		$visualizacion puede ser:
		all:			Se carga en todo momento
		no_mobile:	Si es destinado solo para computadores de escritorio
		mobile:		Si es destinado solo para dispositivos mobiles (tablets, celulares)
		admin:		Si es detinado solo para
		SI NO SE PASA NINGUN PARAMETRO SE TOMARA PARA TODOS LOS DISPOSITIVOS (no mobile) y SE CARGARA para todos los usuarios (all)
		*/
		$visualizacion = is_null($visualizacion) ? 'all' : $visualizacion;
		self::$cssFiles[$new_css_file]['view'] = $visualizacion;
	}

	public function add_js(string $new_js_file, string $visualizacion = NULL, bool $defer = FALSE, bool $async = FALSE, bool $head = FALSE)
	{
		/*
			$visualizacion puede ser:
				all:		Se carga en todo momento
				no_mobile:	Si es destinado solo para computadores de escritorio
				mobile:		Si es destinado solo para dispositivos mobiles (tablets, celulares)
				admin:		Si es detinado solo para
			$async: Si se pasa algun parametro async se convertira en TRUE, sea el que sea el parametro
			$head: Si se pasa algun parametro, sea el que sea el parametro, el script se cargará en el área del header, de lo contrario se cargará antes de finalizar body (despues del footer) y despues de cargar los scripts que ya estén dentro de self::$jsFiles
			SI NO SE PASA NINGUN PARAMETRO SE TOMARA PARA TODOS LOS DISPOSITIVOS, SE CARGARA EN TODO MOMENTO (all) Y ASYNC SERA FALSE
			*/
		$visualizacion = is_null($visualizacion) ? 'all' : $visualizacion;
		self::$jsFiles[$new_js_file] = array('view' => $visualizacion, 'defer' => $defer, 'async' => $async, 'head' => $head);
	}

	public function footerJs()
	{
		if (IS_MOBILE) {
			$jscripts = '<script type="text/javascript" language="javascript">window.isMobile = true;</script>';
		} else {
			$jscripts = '<script type="text/javascript" language="javascript">window.isMobile = false;</script>';
		}
		$jscripts .= $this->js_css_files(self::$jsFiles, 'js');
		return $jscripts;
	}

	public function getCode()
	{
		/*
			Función para ingresar los metadatos de la etiqueta head obtenidos desde la
			funcion $this->getDataTags()

			Para ingresar datos al final de la etiqueta head hay dos formas:
				1.	Agregando el HTML necesario al archivo tpl_head_end.php'

				2.	En el archivo 'tpl_head_data.html' insertar una
				variable como '{{$nueva_variable}}'y el código requerido en el arreglo $metaTplVars (de más abajo).

				*/
		$metaTplVars = array(
			'meta_data_tags'	=> $this->getDataTags(), //etiquetas
			'meta_head_end'	=> $this->metaHeadEnd(),
			//2. Ej:'nueva_variable'=> $codigo o 'el codigo';
		);
		return html::evalTemplate($this->tplLib . "tpl.head_data.html", $metaTplVars);
	}

	public function getDataTags()
	{

		$data = array(
			'http-equiv' => array(
				'x-dns-prefetch-control' => 'off', //"'on' requires some test before implementation"
				'X-UA-Compatible' => 'IE=edge',
				/*
					'Content-Security-Policy" => "default-src 'self' // Require tests before implementation
				*/
			),
			'name' => array(
				'copyright' => "Andres Salazar",
				'description' => $this->description,
				'format-detection' => 'telephone=no',
				'generator' => DEVELOP_APP,
				'googlebot' => 'index,follow',
				'keywords' => $this->keywords,
				'msapplication - TileColor' => '#acd9b2',
				'rating' => 'General',
				'referrer' => "no-referrer",
				'robots' => "index,follow",
				'subject' => $this->subject,
				'theme-color' => "#acd9b2", //Theme color not defined
				'title' => $this->title,
			),
		);

		//Metadatos de charset viewport y el title
		$metas = '<meta charset="utf-8">';
		$metas .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
		$metas .= '<base href="' . SERVER_URL . '">';
		$metas .= '<title>' . $this->title . '</title>';
		//Etiquetas meta de http-equiv, name y property
		foreach ($data as $type => $espec) {
			$meta_line = '<meta ' . $type . '="';
			foreach ($espec as $name => $value) {
				if ($value !== FALSE) {
					$metas .= $meta_line . $name . '" content="' . $value . '">';
				}
			}
		}

		//Etiquetas link
		$metas .= '<link rel="canonical" href="' . SERVER_URL . '">';
		$metas .= '<link rel="author" href="' . SERVER_URL . '/humans.txt">';
		$metas .= '<link rel="license" href="' . SERVER_URL . '/copyright">';
		$metas .= '<link rel="me" href="https://www.github.com/chybeat" type="text/html">';


		//prefetchs
		if (!empty($this->prefetchs)) {
			foreach ($this->prefetchs as $val) {
				$metas .= '<link rel="dns-prefetch" href="' . $val . '">';
			}
		}

		//preconnect
		if (!empty($this->preconnects)) {
			foreach ($this->preconnects as $attr => $val) {
				if (!empty($val)) {
					$val = implode(" ", $val);
				} else {
					$val = "";
				}
				$metas .= '<link rel="preconnect" href="' . $attr . '" ' . $val . '>';
			}
		}


		//favicon
		$metas .= '<link rel="apple-touch-icon" sizes="180x180" href="' . SERVER_URL . '/apple-touch-icon.png">';
		$metas .= '<link rel="icon" type="image/png" sizes="32x32" href="' . SERVER_URL . '/favicon-32x32.png">';
		$metas .= '<link rel="icon" type="image/png" sizes="16x16" href="' . SERVER_URL . '/favicon-16x16.png">';
		$metas .= '<link rel="manifest" href="' . SERVER_URL . '/site.webmanifest">';
		$metas .= '<link rel="mask-icon" href="' . SERVER_URL . '/safari-pinned-tab.svg" color="#5bbad5">';

		//enlaces a css externos
		if (!empty($this->externalCSS)) {
			foreach ($this->externalCSS as $url) {
				$metas .= '<link rel="preload" href="' . $url . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
			}
		}

		// archivos css para estilo
		$metas .= $this->js_css_files(self::$cssFiles, 'css');


		//archivos js con funciones en general (sin código de ejecución)
		$metas .= $this->js_css_files(self::$jsFiles, 'js', TRUE);
		return $metas;
	}

	private function js_css_files($css_js_array_data, $type, $head = FALSE)
	{
		global $user;
		$css_js_tag = '';
		$media = '';
		$rel = '';
		if ($type == 'css') {
			$open_tag = '<link ';
			$source_att = 'href=';
			$rel = ' rel="stylesheet" ';
			$folder = CSS_LIB;
			$type = 'text/css';
			$close_tag = ' />';
		} elseif ($type == 'js') {
			$open_tag = '<script ';
			$source_att = 'src=';
			$folder = JS_LIB;
			$type = 'text/javascript';
			$close_tag = '></script>';
		}
		foreach ($css_js_array_data as $file => $data) {
			if ($type == 'text/javascript' && $data['head'] != $head) {
				continue;
			};
			$testfile = SITE_ROOT . str_replace(SERVER_URL, "", $folder) . "/" . $file;
			if (!file_exists($testfile)) {
				continue;
			}
			unset($testfile);
			$set_file = FALSE;
			if ($data['view'] == "all") {
				//Si se debe colocar el CSS siempre
				$set_file = $file;
			} elseif ($data['view'] == "admin" && $user->idLevel >= 40) {
				//Se carga cuando es un usuario administrador
				stop("falta probar para usuarios administradores la crga de un archivo CSS o  JS;)");
				$set_file = $file;
			}
			if ($type == 'text/css' && isset($data['media'])) {
				$media = ' media="' . $data['media'] . '"';
			}
			if (!isset($data['defer']) && !isset($data['async']) && $type == 'text/javascript') {
				prePrint("!!!ERRRROOOOOORRR!!!! No se ha colocado el atributo async o head para el archivo " . $file);
				stop();
			}
			if ($type == 'text/javascript' && $data['async'] === TRUE) {
				$rel = ' async ';
			}
			if ($type == 'text/javascript' && $data['defer'] === TRUE && $data['async'] === FALSE) {
				$rel = ' defer ';
			}
			if ($set_file !== FALSE) {
				$css_js_tag .= $open_tag;
				$css_js_tag .= $rel;
				$css_js_tag .= $media;
				$css_js_tag .= 'type="' . $type . '"';
				$css_js_tag .= $source_att;
				$css_js_tag .= '"' . $folder . "/" . $set_file . '"';
				$css_js_tag .= $close_tag;
			}
		}
		//		prePrint($css_js_tag);
		return $css_js_tag;
	}

	private function metaHeadEnd()
	{
		//metadatos que se pueden agrear al final de la etiqueta head
		$script_html = file_get_contents($this->tplLib . "tpl_head_end.php");
		$script_html = preg_replace('/^<\?php.*\?\>/sUm', '', $script_html);
		return $script_html;
	}
}

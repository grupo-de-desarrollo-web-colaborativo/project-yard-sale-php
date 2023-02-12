<?php
//obtener los archivos (exceptuando class.html.php) e incluirlos
class HTML
{

	//Global attributes for HTML tags
	protected $globalAttributes = array(
		"accesskey",
		"class",
		"contenteditable",
		"data",
		"dir",
		"draggable",
		"enterkeyhint",
		"hidden",
		"id",
		"inputmode",
		"is",
		"itemid",
		"itemprop",
		"itemref",
		"itemscope",
		"itemtype",
		"lang",
		"nonce",
		"spellcheck",
		"style",
		"tabindex",
		"title",
		"translate",
	);

	//Global event hadler attributes for HTML tags
	protected $eventHandlerAttributes = array(
		"onabort",
		"onafterprint",		//Script to be run after the document is printed
		"onauxclick",
		"onbeforeprint",		//Script to be run before the document is printed
		"onbeforeunload",		//Script to be run when the document is about to be unloaded
		"onblur",
		"oncancel",
		"oncanplay",
		"oncanplaythrough",
		"onchange",
		"onclick",
		"onclose",
		"oncontextmenu",
		"oncopy",
		"oncuechange",
		"oncut",
		"ondblclick",
		"ondrag",
		"ondragend",
		"ondragenter",
		"ondragleave",
		"ondragover",
		"ondragstart",
		"ondrop",
		"ondurationchange",
		"onemptied",
		"onended",
		"onerror",
		"onfocus",
		"onformdata",
		"onhashchange",		//Script to be run when there has been changes to the anchor part of the a URL
		"oninput",
		"oninvalid",
		"onkeydown",
		"onkeypress",
		"onkeyup",
		"onload",				//Fires after the page is finished loading
		"onloadeddata",
		"onloadedmetadata",
		"onloadstart",
		"onmessage",			//Script to be run when the message is triggered
		"onmousedown",
		"onmouseenter",
		"onmouseleave",
		"onmousemove",
		"onmouseout",
		"onmouseover",
		"onmouseup",
		"onoffline",			//Script to be run when the browser starts to work offline
		"ononline",				//Script to be run when the browser starts to work online
		"onpagehide",			//Script to be run when a user navigates away from a page
		"onpageshow",			//Script to be run when a user navigates to a page
		"onpaste",
		"onpause",
		"onplay",
		"onplaying",
		"onpopstate",			//Script to be run when the window's history changes
		"onprogress",
		"onratechange",
		"onreset",
		"onresize",				//Fires when the browser window is resized
		"onscroll",
		"onsecuritypolicyviolation",
		"onseeked",
		"onseeking",
		"onselect",
		"onslotchange",
		"onstalled",
		"onstorage",			//Script to be run when a Web Storage area is updated
		"onsubmit",
		"onsuspend",
		"ontimeupdate",
		"ontoggle",
		"onunload",				//Fires once a page has unloaded (or the browser window has been closed)
		"onvolumechange",
		"onwaiting",
		"onwheel",
	);

	//Tags without close tag
	protected $autocloseTagsList = array(
		'area',
		'base',
		'br',
		'col',
		'embed',
		'hr',
		'img',
		'input',
		'link',
		'meta',
		'param',
		'source',
		'track',
		'wbr',

		//discontinued
		'command',
		'keygen',
		'menuitem',
	);

	//Common parameters of html tag 
	public $afterTag = "";		//	Anything need ater tag
	public $alt;
	public $autoplay;
	public $beforeTag = "";		//	Anything need before tag
	public $class;
	public $content;				//	Tag content (text,other tags, etc)
	public $controls;
	public $compact;
	public $data = [];			//	Array of 'data-' tag attributes
	public $disablePictureInPicture;
	public $disabled;
	public $emptyOption;
	public $for;
	public $forceTarget;			//Boolean to determinate a forced target attribute
	public $height;
	public $href;
	public $htmlCode = "";		//	HTML code to return
	public $id;
	public $ismap;
	public $label;
	public $loop;
	public $muted;
	public $onchange;
	public $options;
	public $playsinline;
	public $rel;
	public $required;
	public $selected;
	public $sources;
	public $src;
	public $tag = '';			//	Tag type/name like div, span, header, etc
	public $tagAttributes;		//	Compiled tag attributes
	public $target;
	public $title;
	public $tpl_lib;				//	Folder for class templates
	public $type;
	public $unsupportedText;
	public $value;
	public $width;

	//El constructor requiere el nombre de la etiqueta que se va a crear
	function __construct($tag = NULL)
	{
		$this->tag = $tag;
		$this->tpl_lib = (__DIR__);
	}

	public function getCode()
	{
		if (is_null($this->tag)) {
			stop("No se ha nombrado una etiqueta al cargar la clase");
		}
		$tagFn = $this->tag;
		$this->$tagFn();
		$this->render();
		return $this->htmlCode;
	}

	static public function svgLoad($svgFile)
	//This function returns the code of SVG files to load as HTML.
	{
		stop();
		$file = SITE_ROOT . IMG_LIB . DS . $svgFile;
		if (!file_exists($file) || mime_content_type($file) != 'image/svg+xml') {
			stop("El archivo '" . $svgFile . "' no existe en la carpeta " . SITE_ROOT . IMG_LIB . " o no es válido");
		}
		return file_get_contents($file);
	}

	private function getAttrs()
	{
		//obtiene los atributos globales para la etiqeuta
		$attributesList = "";
		$this->tagAttributes = is_array($this->tagAttributes) ? $this->tagAttributes : [];
		$totalAttributes = array_merge($this->globalAttributes, $this->eventHandlerAttributes, $this->tagAttributes);
		sort($totalAttributes);
		foreach ($totalAttributes as $attr) {
			//los atributos se agregrán sólo si existen en la clase y no están vacios
			if ((property_exists($this, $attr))) {
				if ($attr == "data") {

					//si el atributo es data- se recorre el arreglo en $this->data y si no es un arreglo se termina la ejecución
					if (!empty($this->data)) {
						stop('$html->data NO es un arreglo válido.');
					}
					ksort($this->data);
					foreach ($this->data as $dataAttr => $value) {
						$attributesList .= " data-" . $dataAttr . '="' . $value . '" ';
					}
				} elseif ($attr == "value") {
					//atributos que pueden ir vacios
					$attributesList .= " " . $attr . '="' . $this->$attr . '" ';
				} elseif (
					is_array($this->$attr)	//opciones que evitan agregar el atributo
					|| (trim($this->$attr) == '')
					|| is_null($this->$attr)
					|| $this->$attr === FALSE
				) {
					continue;
				} elseif (property_exists($this, $attr) && (is_bool($this->$attr) && $this->$attr === TRUE)) {
					//si el atributo es un boleano en verdadero, se escribe solo el nombre del atributo
					$attributesList .= $attr . " ";
				} else {
					$attributesList .= " " . $attr . '="' . $this->$attr . '" ';
				}
			}
		}
		return trim($attributesList);
	}

	private function render()
	{
		//apertura de etiqueta
		$this->htmlCode = $this->beforeTag . "<" . $this->tag;

		//obtención de los atributos para la etiqueta
		$attrs = $this->getAttrs();
		$attrs = ($attrs != '') ? ' ' . $attrs : NULL;
		$this->htmlCode .= $attrs;

		//verifica la etiqueta a crear lleva etiqueta de cierre
		if (in_array($this->tag, $this->autocloseTagsList, TRUE)) {
			$this->htmlCode .= "/>";
		} else {
			empty($this->content) ? $this->content = "No se ha pasado contenido (texto o codigo HTML) a \$html->content que vaya dentro de la etiqueta " . $this->tag : NULL;
			//obtención de los atributos
			//si no hay atributos no se necesita un separador entre el tag y los
			$this->htmlCode .= ">" . (string) $this->content . "</" . $this->tag . ">" . $this->afterTag;
		}
	}

	public static function clean(string $html_code, bool $returnLine = FALSE)
	{
		if (!$returnLine) {
			$html_code = preg_replace("/\r\n+|\r+|\n+/i", " ", $html_code);
		}
		$html_code = preg_replace("/\t+/i", " ", $html_code);
		// limpiar el codigo de espacios dobles y espacios entre etiquetas 
		$html_code = str_replace('<', '< ', $html_code);
		$html_code = str_replace('>', ' >', $html_code);
		while ((strpos($html_code, '  ') !== FALSE)
			|| (strpos($html_code, '>  <') !== FALSE)
			|| (strpos($html_code, ' >') !== FALSE)
			|| (strpos($html_code, '< ') !== FALSE)
		) {
			$html_code = str_replace('  ', ' ', $html_code);
			//			$html_code = str_replace('>  <','> <', $html_code);
			$html_code = str_replace(' >', '>', $html_code);
			$html_code = str_replace('< ', '<', $html_code);
		}

		$html_code = str_replace(' </', '</', $html_code);
		$html_code = str_replace(' >', '>', $html_code);

		return trim($html_code);
	}

	public static function evalTemplate($template, $vars = array(), $clean = FALSE, $returnLine = FALSE)
	{
		$ext = mb_strtolower(pathinfo($template, PATHINFO_EXTENSION));
		/*
			Devuelve el codigo html de una plantilla ($template) procesado con variables 
			donde $vars es un arreglo con las variables a llenar dentro de la plantilla a usar ($template)
			las claves dentro del arreglo $vars deben ser el texto o nombre a reemplazar y el
			valor debe ser el texto a reemplazar

			$return es opcional (para desarrollo) y es para mostrar el codigo html con retornos o saltos de linea
		*/
		// Variables de tipo global
		$tpl_vars_default = array(
			'SERVER_URL'		=> SERVER_URL,
			'ACTUAL_URL'		=> ACTUAL_URL,
			'IMG_LIB'			=> IMG_LIB,
			'CSS_LIB'			=> CSS_LIB,
		);
		// Todas las variables del template + las variables globales
		$vars = array_merge($tpl_vars_default, $vars);

		//leyendo el script html
		if ($ext == "html") {
			$script_html = file_get_contents($template);
			//reemplazo de variables en el HTML
			foreach ($vars as $key => $value) {
				if (is_array($value)) {
					echo "Hay un arreglo deberia ser un string";
					prePrint($value);
					trace();
					stop();
				}
				$search = '{{$' . $key . '}}';
				$script_html = str_replace($search, $value, $script_html);
				$script_html = str_replace('</html>', '', $script_html);
			}
		} else if ($ext == "php") {
			prePrint(file_get_contents($template));
			$script_html = eval(" ?> " . file_get_contents($template) . " <?php ");
			//$script_html = file_get_contents($template);
			prePrint($script_html);
			line("Verificar el codigo HTML entregado");
			stop("Se debe verificar el procesar una plantilla php");
		} else {
			trace();
			stop("El archivo " . $template . " no tiene una extensión válida.");
		}
		if ($clean) {
			$script_html = static::clean($script_html, $returnLine);
		}
		return $script_html;
	}

	/******************************************
	 *                                         *
	 * Funciones que generan HTML directamente *
	 *                                         *
	 *******************************************/

	public function button(string $content, string $type = NULL, string $css_class = NULL, string $id_boton = NULL, string $value = NULL, string $name = NULL, string $form_id = NULL, bool $disabled = FALSE, bool $label = FALSE, string $label_text = NULL, string $onclick = NULL, array $more_attrs = NULL)
	{
		stop();
		/*
		//Uso: $html->button($content,$type,$css_class,$id_boton,$value,$name,$form_id,$disabled,$label,$label_text,$onclick,array $more_attrs);
		string $content > El contenido interno del boton
		string $type > tipo de boton (button, reset, submit)
		string $css_class > clase css del boton
		string $id_boton > id unico del boton
		bool $disabled > si debe aparecer desactivado o no el boton
		bool $label > Si lleva o no la etiqueta label
		string $label_text > Texto que aparecerá en el label
		string $value > Valor del boton en el envío por formulario NO ES el texto por defecto que tendrá el boton
		string $name > atributo name del boton
		string $form_id > id del formulario al que hace referencia el boton para la acción
		string $onchange > javascript on change
		string $oninput > javascript on input
		string $onclick > javascript on click
		SI REQUIERE DE LO SIGUIENTE MEJOR HACER EL INPUT EN UN TEMPLATE DIRECTAMENTE!!
		array $more_attrs > en caso de extramelente requerirse pueden agregarse mas attributos en un arreglo con el formato array("attributo" => valor)
*/

		$type = $type != '' ? ' type="' . $type . '" ' : '';
		$css_class = $css_class != '' ? ' class="' . $css_class . '" ' : '';
		$id_boton = $id_boton != "" ? ' id="' . $id_boton . '" ' : '';
		$disabled = $disabled ? ' disabled="disabled" ' : '';
		$label = $label ? $this->label($label_text, $id_boton) : '';
		$value = $value != '' ? ' value="' . $value . '" ' : '';
		$name = $name != '' ? ' name="' . $name . '" ' : '';
		$form_id = $form_id != '' ? ' form="' . $form_id . '" ' : '';
		$on_change = '';
		$on_click = $onclick != '' ? ' onclick="' . $onclick . '"' : '';
		$attrs = is_array($more_attrs) ? $this->more_attrs($more_attrs) : '';
		return $label . '<button ' . $type . $css_class . $id_boton . $disabled . $value . $name . $form_id . $on_click . $attrs . '>' . $content . '</button>';
	}

	public function checkbox(string $name, string $value, string $id_checkbox = NULL, bool $checked = FALSE, bool $required = FALSE, bool $label = FALSE, string $label_text = NULL, string $css_class = NULL, bool $disabled = FALSE, bool $autofocus = FALSE, string $onchange = NULL, string $onclick = NULL, array $more_attrs = NULL)
	{
		stop();
		/*
		//Uso: $html->checkbox($name, $value, $id_checkbox, $checked, $required, $label, $label_text, $css_class, $disabled, bool $autofocus, $onchange, $onclick, $more_attrs)
		string $name > atributo name del input
		string $value > El texto por defecto que tendrá el input
		string $id_checkbox > id unico del input
		bool $checked > is el checkbox está o no marcado
		bool $required > Si al momento de enviar el formulario este dato es o no requerido de llenado
		bool $label > Si lleva o no la etiqueta label
		string $label_text > Texto que aparecerá en el label
		string $css_class > clase css del input
		bool $disabled > si debe aparecer desactivado o no el input
		bool $autofocus > si al cargar el formulario se debe autoenfocar el input
		string $onchange > javascript on change
		string $onclick > javascript on click
		SI REQUIERE DE LO SIGUIENTE MEJOR HACER EL INPUT EN UN TEMPLATE DIRECTAMENTE!!
		array $more_attrs > en caso de extramelente requerirse pueden agregarse mas attributos en un arreglo con el formato array("attributo" => valor)
*/
		//Uso: $html->input($type,$name,$placeholder,$required,$value,$id_input,$label,$label_text,$css_class,$disabled,$readonly,$id_list,$autosubmit,$max_chars,$autocomplete,$autofocus,$onchange,$oninput,$onclick,$more_attrs)
		return $this->input('checkbox', $name, $checked, $required, $value, $id_checkbox, $label, $label_text, $css_class, $disabled, FALSE, '', FALSE, '0', FALSE, $autofocus, $onchange, '', $onclick, $more_attrs);
	}

	public function datalist()
	{
		stop();
		$attrs = array(
			'id',	// (string)	id de la lista(debe coincidir en el input este mismo id)
		);

		if ((!property_exists($this, 'options')) || (!is_array($this->options))) {
			stop("No se ha enviado un arreglo con el par 'valor' => 'texto' para las opciones de la lista");
		} else {
			$listOptions = '';
			foreach ($this->options as $val) {
				$opt = new HTML("option");
				$opt->value = $val;
				$opt->content = FALSE;
				$listOptions .= $opt->getCode();
			}
		}
		$this->content = $listOptions;
		$this->tagAttributes = $attrs;
		$this->tag = "datalist";
	}


	public function form(string $content, string $method = 'GET', string $css_class = '', string $id_form = '', string $action = '', string $enctype = '', string $name = '', bool $autocomplete = FALSE, bool $novalidate = FALSE, string $onsubmit = '', array $more_attrs = NULL)
	{
		stop();
		/*
		Uso: $html->form($content,$method,$css_class,$id_form,$action,$enctype,$name,$autocomplete,$novalidate,$onsubmit,$more_attrs);
		string $content > el contenido html del formulario (divs, input, select, button, etc.,)
		string $method > metodo HTTP para el envío de datos al servidor posibles opciones: (get, post)
		string $css_class > clase css del input por motivos remotos de javascript
		string $id_form > ID del iput al que se le mostrará la lista
		string $action > Especifica la URL donde se enviará la información al enviar el formulario
		string $enctype	> el método de envío de datos al servidor posibles opciones: application/x-www-form-urlencoded, multipart/form-data, text/plain)
		string $name > nombre del form para uso con javascript
		bool $autocomplete > Especifica si el autocompletado esta activo para el formulario
		bool $novalidate > si el formulario debe o no ser validado al enviar
		string $onsubmit > function javascript al enviar el formulario
		SI REQUIERE DE LO SIGUIENTE MEJOR HACER EL DATALIST EN UN TEMPLATE DIRECTAMENTE!!
		array $more_attrs > en caso de extramelente requerirse pueden agregarse mas attributos en un arreglo con el formato array("attributo" => valor)
*/
		$method = $method != '' ? ' method="' . $method . '" ' : '';
		$css_class = $css_class != '' ? ' class="' . $css_class . '" ' : '';
		$id_form = $id_form != "" ? ' id="' . $id_form . '" ' : '';
		$action = $action != "" ? ' action="' . $action . '" ' : '';
		$enctype = $enctype != "" ? ' enctype="' . $enctype . '" ' : '';
		$name = $name != "" ? ' name="' . $name . '" ' : '';
		$autocomplete = $autocomplete ? ' autocomplete="on" ' : 'autocomplete="off"';
		$novalidate = $novalidate ? ' novalidate ' : '';
		$on_submit = $onsubmit != '' ? ' onsubmit="' . $onsubmit . '"' : '';
		$attrs = is_array($more_attrs) ? $this->more_attrs($more_attrs) : '';
		return '<form ' . $css_class . $id_form . $action . $method . $enctype . $name . $autocomplete . $novalidate . $on_submit . $attrs . '>' . $content . '</form>';
	}

	public function img()
	{
		stop();
		/*
		Uso: img($src,$alt,$css_class,$title,$height,$width,$id_image,$onclick,$more_attrs)
		string $src > la ruta de la imagen
		string $alt > texto alternativo de la imagen
		string $css_class > clase css del input
		string $title > información adicional de la imagen
		int $height > ancho especifico de la imagen
		int $width > alto especifico de la imagen
		string $id_image > id unico de la imagen
		string $onclick > javascript on click
		SI REQUIERE DE LO SIGUIENTE MEJOR HACER LA IMG EN UN TEMPLATE DIRECTAMENTE!!
		array $more_attrs > en caso de extramelente requerirse pueden agregarse mas attributos en un arreglo con el formato array("attributo" => valor)
*/

		$attrs = array(
			'alt',				//(string)		> Texto alterno para la imagen
			'crossorigin',		//(string)		> Permitir imagenes de otro sitio para uso en canvas
			'decodign',			//sync|async|auto > Musetra la imagen al tiempo (sync) o con demora (async) respecto a otro contenido.
			'height',			//(int)			> Alto de imagen
			'ismap',			//(bool)		> especifica que la imagen es un mapa de enlaces
			'loading',			//"eager|lazy"	> Eager carga la imagen inmediatamente Lazy carga hasta alguna condición
			'longdesc',			//string		> URL con informacion detalla de la imagen
			'referrerpolicy',	//string		> Especifica qué información de referencia usar al obtener una imagen (https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Referrer-Policy)
			'sizes',			//(string)		> media query para tamaño de imagenes
			'src',				//(string)		> La URL de la imagen
			'srcset',			//(string)		> Texto que indica distintos origenes de la imagen para el tamaño definido por el useragent (info: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/img#attr-srcset)
			'width',			//(int)			> ancho de la imagen
			'usemap',			//(string)		> URL (iniciando con #) de la imagen de mapa asociada.
		);
		$this->tagAttributes = $attrs;
		$this->tag = "img";

		//manejo de atributos boleanos
		$this->ismap = (property_exists($this, 'ismap') && $this->ismap === TRUE);
	}

	public function input()
	{
		stop();
		$attrs = array(
			"accept",			//extensión de archivo o tipo de medio que el usuario puede seleccionar en larchivos (Se susa en type="file")
			"alt",				//Texto alternativo para type="image"
			"autocomplete",	//(on|off) autocompletado del campo
			"autofocus",		//(bool) foco automatico a un input al cargar la página
			"checked",			//(bool) preselcción de una opción en type="checkbox" / "radio"
			"disabled",			//(bool) si debe aparecer desactivado o no el input
			"form",				//(string) id del form al que pertenece el input
			"formaction",		//(strind) URL donde se procesarán los datos (para type="submit" o "image")
			"formenctype",		//(application/x-www-form-urlencoded | multipart/form-data | text/plain) codificación de la información al enviar (para type="submit" o "image")
			"formmethod",		//(get | post) metodo de envío de los datos (para type="submit" o "image")
			"formnovalidate",	//(bool) No validar un elemento al envíar
			"formtarget",		//(_blank | _self | _parent | _top | framename) elemento donde se mostrará la respuesta recibida (para type="submit" o "image")
			"height",			//(int) alto de un input type="image"
			"list",				//(string) id de la lista para ayuda en el igreso de datos del input
			"max",					//(int|date) Valor maximo para un elemento input (para date: max="1979-12-31")
			"maxlength",		//(int) maximos caracteres admitidos en el input
			"min",				//(int|date) Valor mínimo para un elemento input (para date: min="1979-12-31")
			"minlength",		//(int) caracteresque mínimo se deben escribiren el input
			"multiple",			//(bool) colocar varios elementos (separados por coma) o seleccionar varios archivos en imput="file"
			"name",				//(string) atributo name del input
			"pattern",			//(regexp) patron válido para un input
			"placeholder",		//(string) Ayuda o pista para llenado del elemento
			"readonly",			//(bool) si el input es para solo lectura
			"required",			//(bool) Si al momento de enviar el formulario este dato es o no requerido de llenado
			"size",				//(int) ancho de un input medido en caracteres
			"src",				//(string) URL de una imagen usada como boton de envío (solo para type="image")
			"step",				//(int | any) Intervalo para input: number, range, date, datetime-local, month, time y week.
			"type",				/*(string) tipo de input
				button, checkbox, color, date, datetime-local, email, file, hidden, image, month, number, password, radio, range, reset, search, submit, tel, text, time, url, week */
			"value",				//El texto que tendrá el input
			"width"				//(int) ancho de un input type="image"
		);
		$this->tagAttributes = $attrs;
		$this->tag = "input";
	}

	public function label()
	{
		stop();
		$attrs = array(
			'for'			//(string) Id del elemento de destino que se activará al dar click sobre el label
		);
		$this->tagAttributes = $attrs;
		$this->tag = "label";
	}

	public function li()
	{
		$this->tag = "li";
		$attrs = array(
			'type',		//(string)	> estilo de numeración (disc|square|circle) (1|a|A|i|I)
			'value',	//(int)	> 'un número' para reinicializar número de secuencia.
		);
		empty($this->value) ? $attrs = array_diff($attrs, ["value"]) : NULL;
		if (empty($this->content)) {
			$this->emptyError($this->tag);
		}
		$this->tagAttributes = $attrs;
	}


	public function a()
	{
		$attrs = array(
			'download',	//string	> Especifica para descarga la URL de ser un archivo o un contenido de tipo data o blob
			'href',		//string	> Especifica la URL de destino
			'hreflang',	//lang 	> Sugerencia del idioma de la URL de destino
			'rel',		//string	> Relacion entre el documento actual y el de destino. posibles datos: (alternate author bookmark external help license next nofollow noreferrer noopener prev search tag)
			'target',	//string	> Especifica donde se abrirá el enlace. Posibles datos: (_blank _parent _self _top framename)
			'type',		//MIME type	> Especifica el tipo de medio que abrirá el enlace
			'forceTarget', // bool	> Boleano que fuerza mantener el target al enlace
		);

		$this->tagAttributes = $attrs;
		$this->tag = "a";

		// si el enlace dirige a otra pagina siempre se abrirá en una nueva pestaña
		if (
			!empty($this->href)
			&& $this->href !== FALSE
			&& $this->forceTarget !== FALSE
			&& strpos($this->href, SERVER_URL) === FALSE
		) {
			$this->target = "_blank";
		}

		//Mejora de asegurar con nofollow noreferrer noopener en la etiqueta rel
		$relValues = array('nofollow', 'noreferrer', 'noopener');
		if ($this->rel === TRUE) {
			$this->rel = $this->rel . (implode(' ', $relValues));
		} else {
			$this->rel = str_replace($relValues, '', $this->rel);
		}
	}

	public function nav()
	{
		$this->tag = "nav";
	}

	public function ol()
	{
		$this->tag = "ol";
		$attrs = array(
			'type',		//(string)	> Indica el estilo de los items de la lista (1|a|A|i|I)
			'compact',	//(bool)	> Indica que la lista debe mostrase compactada.
			'start',		//(int)	> número inicial de la secuencia
		);
		if (empty($this->content)) {
			$this->emptyError($this->tag);
		}
		$this->tagAttributes = $attrs;
	}


	public function option()
	{
		stop();
		//Creacion de la etiqueta option (que generalmente va dentro de select)
		$attrs = array(
			'disabled',	//(bool)	> Si el select esta desactivado al leer la página
			'selected',	//(string)	> el valor (value) del select que se mostrá al cargar 
			'value',	//(string)	> valor
		);
		$this->tagAttributes = $attrs;
		$this->tag = "option";

		//manejo de atributos boleanos
		$this->disabled = (property_exists($this, 'disabled') && $this->disabled === TRUE);

		if (!property_exists($this, 'content')) {
			stop("No se puede generar un option, con un valor vacio");
		}
	}

	public function output()
	{
		stop();
		$attrs = array(
			'for',	//(string) el id del elemento al que pertenece
			'form',	//(bool)	el id del formulario al que pertenece
			'name',	//(bool)	name para el elemento
		);
		$this->tagAttributes = $attrs;
		$this->tag = "output";
	}

	public function select()
	{
		stop();
		/*Datos que utiliza select y que se pueden enviar a la clase

		Ej:
		$select=new HTML;
		$select->emptyOption=TRUE;
		$select->name = rango-usuario;
		$select->options = array('data','more data');

		autosubmit (bool)			> envio automático del formulario al cambiar de opción
		emptyOption (bool/string)	> Booleano o texto que especifica si se coloca una opcion vacia. Si se envía un string, eso aparecerá como opción vacía;
		selected(string)			> la opción que debe estar seleccionada al cargar
		label (string)				> Texto que aparecerá en el label
		options (array)				> Arreglo con el par 'valor' => 'texto'
		*/
		$attrs = array(
			'disabled',		//(bool)	> Especifica
			'required',		//(bool)	> Si es requerido o no un valor para envío del formulario
			'name',			//(string)	> Especifica el valor que se enviará al servidor
		);
		$this->tagAttributes = $attrs;
		$this->tag = "select";

		//manejo de atributos boleanos
		$this->required = (property_exists($this, 'required') && $this->required === TRUE);
		$this->disabled = (property_exists($this, 'disabled') && $this->disabled === TRUE);

		// Creacion del label si se envia un texto a $html->label
		if (property_exists($this, 'label')) {
			$label = new HTML("label");
			$label->for = property_exists($this, 'id') ? $this->id : NULL;
			$label->content = $this->label;
			$label = $label->getCode();
			$this->beforeTag = $label;
		}
		if (property_exists($this, 'autosubmit')) {
			if (property_exists($this, 'onchange')) {
				$this->onchange = str_replace($this->onchange, '', $this->onchange) . '; ';
			} else {
				$this->onchange = '';
			}
			$this->onchange = trim($this->onchange, '; ');
			$this->onchange .= 'this.form.submit();';
		}

		if ((!property_exists($this, 'options')) || (!is_array($this->options))) {
			stop("No se ha enviado un arreglo con el par 'valor' => 'texto' para las opciones del select");
		} else {
			$selectOptions = '';
			//Verificacion de opcion vacia (sirve para requerir que se seleccione una opcion antes de enviar el formulario)
			if (property_exists($this, 'emptyOption') && !property_exists($this, 'selected')) {
				$emptyOption = new HTML("option");
				$emptyOption->value = "";
				$emptyOption->content = is_bool($this->emptyOption) ? 'Selecciona' : $this->emptyOption;
				$selectOptions .= $emptyOption->getCode();
				unset($emptyOption);
			}
			if (property_exists($this, 'selected')) {
				$selected = $this->selected;
			}
			foreach ($this->options as $val => $txt) {
				$opt = new HTML("option");
				$opt->selected = (isset($selected) && $selected == $val) ? TRUE : NULL;
				$opt->content = $txt;
				$opt->value = $val;
				$selectOptions .= $opt->getCode();
			}
		}
		$this->content = $selectOptions;
	}

	public function span()
	{
		$attrs = array(); //span no tiene atributos especificos
		$this->tagAttributes = $attrs;
		$this->tag = "span";
	}

	public function source()
	{
		stop();
		$attrs = array(
			'src',			//(URL)		> URL de un elemento (audio o video)
			'type',			//(string)	> Tipo mime del elemento nombrado en src
			'srcset',		//(string)	> lista separada por comas de un conjunto de de posibles imagenes en base a src. Los parametros para cada conjunto son URL del archivo, ancho en px seguido de w y la densidad de pixeles de la imagen EJ; (imagen-1.jpg 120w 2x). Funciona solo para la etiqueta <picture>
			'sizes',		//(string)	> Lista separa por comas de pares de longitd (w h, w h). Funciona solo para la etiqueta <picture>
			'media',		//(string)	> Media query del src para elementos picture. Funciona solo para la etiqueta <picture>

		);
		$this->tagAttributes = $attrs;
		$this->tag = "source";
	}

	public function td(string $content, int $colspan = NULL, int $rowspan = NULL, string $css_class = NULL, string $id_td = NULL, string $onclick = NULL, array $more_attrs = NULL)
	{
		stop();
		/*
		string $content > contenido dentro del td
		int $colspan > numero de celdas hacia la derecha que se agruparan
		int $rowspan > numero de celdas hacia abajo que se agruparan
		string $css_class > clase css para el td
		string $id_td > Identificador del td
		string $onclick > javascript onclick,
		SI REQUIERE DE LO SIGUIENTE MEJOR HACER EL TD EN UN TEMPLATE DIRECTAMENTE!!
		array $more_attrs > en caso de extramelente requerirse pueden agregarse mas attributos en un arreglo con el formato array("attributo" => valor)
*/
		$colspan = $colspan != '' ? ' colspan="' . $colspan . '" ' : '';
		$rowspan = $rowspan != '' ? ' rowspan="' . $rowspan . '" ' : '';
		$css_class = $css_class != '' ? ' class="' . $css_class . '" ' : '';
		$id_td = $id_td != '' ? ' id="' . $id_td . '" ' : '';
		$on_click = $onclick != '' ? ' onclick="' . $onclick . '"' : '';
		$attrs = is_array($more_attrs) ? $this->more_attrs($more_attrs) : '';
		return '<td ' . $colspan . $rowspan . $css_class . $id_td . $on_click . $attrs . '>' . $content . '</td>';
	}

	public function ul()
	{
		$this->tag = "ul";
		$attrs = array(
			'type',		//(string)	> Indica el estilo de los items de la lista
			'compact',	//(bool)	> Indica que la lista debe mostrase compactada.
		);
		if (empty($this->content)) {
			$this->emptyError($this->tag);
		}
		$this->tagAttributes = $attrs;
	}

	public function video()
	{
		stop();
		/*Datos que pueden enviarse a video y que se pueden enviar a la clase

		Ej:
		$video=new HTML;
		$video->unsupportedText='Tu navegador no es capaz de visualizar videos :(';
		$video->sources = array(
			'video-1.mp4'
			'video-1.webm')
		);
		
		unsupportedText (string)	> El texto que se motrará si el navegador no es compatible para ver videos, puede ser en formato HTML.
		sources (array)				> Arreglo con las URL de los video
		type(string)				> Texto del tipo MIME del video
		*/
		$attrs = array(
			'autoplay',					//(bool)	> Especifica si se reproduce el video autmaticamente
			'buffered',					//(int)		> Determina el rango de tiempo del búfer
			'controls',					//(bool)	> Muestra los controles de de reproducción para el video
			'controlslist',				//(string)	> Seleccion de controles que se muestran en el video (nodownload, nofullscreen and noremoteplayback)
			'crossorigin',				//(string)	> Indica las credenciales de uso de un video desde otra web
			'currentTime',				//(int)		> Indica el tiempo de inicio del video
			'disablePictureInPicture',	//(bool)	> Evita el menu para ver una imagen pequeña del video en la pantalla
			'height',					//(int)		> El alto del video
			'loop',						//(bool)	> Indica que el video se reproduzca infinitamente
			'muted',					//(bool)	> Indica que el video no reproduzca sonido
			'playsinline',				//(bool)	> Indica que el video se reproducirá 'inline' en el área de reproducción
			'poster',					//(string)	> URL de una imagen que se mostrará mientras el video carga
			'preload',					//none|metadata|auto > especifica que contenido se carga del video antes de reproducirse none: indica que no se hará una precarga del video | metadata: Idica que solo metdatso del video serán precargados| auto: Indica que el video se cargará así el usuario no lo vaya a ver.
			'src',						//(string)	> URL del video
			'width',					//(int)		> ancho del video
		);
		$this->tagAttributes = $attrs;
		$this->tag = "video";
		//manejo de atributos boleanos
		$this->autoplay = (property_exists($this, 'autoplay') && $this->autoplay === TRUE);
		$this->controls = (property_exists($this, 'controls') && $this->controls === TRUE);
		$this->disablePictureInPicture = (property_exists($this, 'disablePictureInPicture') && $this->disablePictureInPicture === TRUE);
		$this->loop = (property_exists($this, 'loop') && $this->loop === TRUE);
		$this->muted = (property_exists($this, 'muted') && $this->muted === TRUE);
		$this->playsinline = (property_exists($this, 'playsinline') && $this->playsinline === TRUE);

		//Agregar el contenido de texto para navegadores que no sportan la etiqueta video
		if (isset($this->unsupportedText) && (!is_null($this->unsupportedText) || $this->unsupportedText != '')) {
			stop("Algo comentariado");
			//$this->content = str_replace($this->unsupportedText,"", $this->$content).$this->unsupportedText;
		} else if (isset($this->unsupportedText)) {
			$this->content = $this->unsupportedText;
		}

		//agregar el contenido de source
		if (isset($this->src) || (isset($this->sources) && is_array($this->sources))) {
			//buscar dentro del arreglo si existe el contenido de src
			if (isset($this->sources) &&  is_array($this->sources)) {
				if (isset($this->src) && array_search($this->src, $this->sources) === FALSE) {
					$this->sources[] = $this->src;
				}
			} else {
				$this->sources[] = $this->src;
			}
			unset($this->src);
			//para cada elemento obtener el codigo html de sources
			!isset($this->content) ? $this->content = "" : NULL;
			foreach ($this->sources as $source) {
				$src = new HTML('source');
				$src->src = $source;
				(isset($this->type)) ? $src->type = $this->type : NULL;
				$this->content .= $src->getCode();
			}
			//para el tributo type, buscar el tipo este o no vacio
			//mime_content_type ( resource|string $filename )
		} elseif (isset($this->src) && !isset($this->sources)) {
			stop("Algo comentariado");
			//stop("\$html->sources no es un arreglo válido y tampoco hay información en $html->src");
		}
	}

	private function emptyError($tag)
	{
		trigger_error("La <b>etiqueta " . mb_strtoupper($tag) . " requiere</b> de contenido pasado al atributo <b>content (\$html->content)</b> antes de generar el código HTML.<br><br> Error ", E_USER_ERROR);
	}

	private function more_attrs(array $attrs)
	{
		stop();
		$tag_attrs = '';
		array_filter($attrs);
		foreach ($attrs as $attr => $value) {
			$tag_attrs .= ' ' . $attr . '="' . $value . '" ';
		}
		return $tag_attrs;
	}

	/******************************************
	 *                                         *
	 * Funciones que generan HTML directamente *
	 *                                         *
	 *******************************************/

	static public function hideEmail($email, $title)
	{
		$character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
		$key = str_shuffle($character_set);
		$cipher_text = '';
		$id = 'e' . rand(1, 999999999);
		for ($i = 0; $i < strlen($email); $i += 1) {
			$cipher_text .= $key[strpos($character_set, $email[$i])];
		}
		$script = 'a="' . $key . '";b=a.split("").sort().join("");var c="' . $cipher_text . '";var d="";for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));document.getElementById("' . $id . '").innerHTML="<a href=\\"mailto:"+d+"\\" target=\"_blank\" rel=\"noopener noreferrer\" title=\"' . $title . '\">"+d+"</a>";delete a;delete b;delete c;delete d;delete e';
		$script = "eval(\"" . str_replace(array("\\", '"'), array("\\\\", '\"'), $script) . "\")";
		$script = '<script>/*<![CDATA[*/' . $script . '/*]]>*/</script>';
		return '<span id="' . $id . '"></span>' . $script;
	}

	// Devuelve un texto sin etiquetas HTML
	public function no_tags($txt)
	{
		stop();
		$result = preg_replace('#<[^>]+>#', ' ', $txt);
		return $result;
	}
}

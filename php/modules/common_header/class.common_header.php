<?php
class common_header
{
	private $tpl_lib;		// carpeta de donde se obtienen los templates
	private $areaName;	// area a la que pertenece el header (se usa en cual es activa)
	private $currentCategory; //Categoria de producto actual

	function __construct($areaName = "")
	{

		$area = new Area;
		$this->tpl_lib = (__DIR__);
		$this->areaName = $area->get_current_area();
		$this->currentCategory = product::get_category_url_request();
	}

	public function getCode()
	{
		//puede aparecer logo, o titulo de shopping cart o my orders
		// en #cart el <i> puede cambiar el tiutlo y la clase dependiendo del contenido en carrito 
		$commonHeader = $this->tpl_lib . DS . "tpl.common_header.html";
		$headerVars = array(
			"header_title"	=> $this->header_title($this->areaName),
			"user_actions"	=> $this->user_actions(),
			"nav_list"		=> $this->nav_list(),
			"cart_status"	=> $this->cart_status(),
		);
		$commonHeader = html::evalTemplate($commonHeader, $headerVars);
		return $commonHeader;
	}

	private function cart_status()
	{
		//trigger_error("No cart class created!!!");
		return (" empty");
	}
	private function header_title(string $area = null)
	{
		$title = "";
		$logoClass = "";
		if ($area == "shopping-cart") {
			stop("\$area = $area... checkout");
			$title = '<h2>Shopping cart</h2>';
			$logoClass = "hidden";
		} else if ($area == "my-orders") {
			stop("\$area = $area... checkout");
			$title = '<h2>My orders</h2>';
			$logoClass = "hidden";
		} else if ($area == "order") {
			stop("\$area = $area... checkout");
			$title = '<h2>My order</h2>';
			$logoClass = "hidden";
		}
		return $title . '<img class="' . $logoClass . '"src="' . IMG_LIB . '/logo-yard-sale.svg" alt="logo-yard-sale" width="144" height="30">';
	}

	private function nav_list()
	{
		$cats = new product(TRUE);
		$cats = $cats->get_product_categories();

		$listElements = "";

		foreach ($cats as $catName => $desc) {
			$element = new HTML('li');
			$element->class = "nav__item";
			$element->title = $desc['description'];

			$anchor = new HTML('a');
			$anchor->href = SERVER_URL . "/category/" . strtolower(urlencode($catName));
			$anchor->class = "btn-link";
			$anchor->content = $catName;
			if ($this->currentCategory == strtolower($catName)) {
				$anchor->class .= " btn--transparent";
			}
			$element->content = $anchor->getCode();
			$listElements .= $element->getCode();
		}
		$list = new html('ul');
		$list->class = "nav__list";
		$list->content = $listElements;

		$nav = new html('nav');
		$nav->class = "header-nav";
		$nav->content = $list->getCode();
		return $nav->getCode();
	}

	private function user_actions()
	{
		if ($_SESSION[PROJECT]['logged']) {
			return '			<p>user@email.com <i class="icon-arrow"></i></p><!-- flex-order:3 // 1 ; nunca se ve en mobile-->
			<p>my account</p><!-- flex-order:1 // 2;  en desktop es en un menu; en mobile con flex order-->
			<p>my order</p><!-- flex-order:2 // 3; en desktop es en un menu; en mobile con flex order-->

			<!-- Depende del usuario actual-->
			<p>sign-out</p><!-- flex-order:4; se une al nav en mobile; en desktop se ve en el menu-->';
		} else {
			return '<a href="' . SERVER_URL . '/login">Log in</a><!-- flex-order:4;  se une al nav en mobile; en desktop se ve en el menu-->';
		}
		preprint($_SESSION);
	}
}

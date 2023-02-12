<?php
class Common_footer
{

	private $tpl_lib;	// carpeta de donde se obtienen los templates
	private $area;		// area a la que pertenece el header (se usa en cual es activa)

	function __construct($area = "")
	{
		$this->tpl_lib = __DIR__;
		$this->area = $area;
	}

	public function getCode()
	{
		$commonFooter = $this->tpl_lib . DS . "tpl.common_footer.html";
		$jsfooter = new Meta_data();
		$footerVars = array(
			"email"		=> html::hideEmail("contact@yardsale.com", "Write us to Yard sale's contact email"),
			"copyright"	=> COPYRIGHT,
			"jscripts"	=> $jsfooter->footerJs(),
		);
		$commonFooter = html::evalTemplate($commonFooter, $footerVars);
		return $commonFooter;
	}
}

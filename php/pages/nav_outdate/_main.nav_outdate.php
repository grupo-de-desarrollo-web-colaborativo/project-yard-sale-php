<?php
$tplLib = __DIR__;
$meta = new meta_data();
$meta->title = "Error de navegador | YardSale";
$meta->description = "Utiliza un navegador compatible con el proyecto YardSale eCommerce";
$meta->subject = "Navegador no compatible";
$meta->keywords = "navegador, incompatible, firefox, google, chrome, CSS3, ES6";
$meta->add_css('nav_error.css');
/* header */
$common_header = new common_header();

/*footer */
$common_footer = new common_footer();

$dataNavError = array(
	"head" => $meta->getCode(),
	"common_header" => $common_header->getCode(),
	"common_footer" => $common_footer->getCode(),
);
echo html::evalTemplate($tplLib . DS . "tpl.nav_error.html", $dataNavError, TRUE);
die();

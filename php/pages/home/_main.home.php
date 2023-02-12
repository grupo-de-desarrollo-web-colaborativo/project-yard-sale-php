<?php

/********************
 Datos para la página
 ********************/
$tpl = __DIR__ . DS . "tpl.home.html";


/*	Meta datos */
$meta =  new meta_data();
$meta->title = "Home | Yard Sale";
$meta->description = "Yard sale eCommerce common develop";
$meta->keywords = "sale, shopping, ecommerce";
$meta->subject = "Yard Sale eCommerce";
$meta->add_css('home.css');

echo $meta->getCode();
stop();

/* header */
$common_header = new common_header();

/*footer */
$common_footer = new common_footer();

$home_vars = array(
	"head" => $meta->getCode(),
	"common_header"	=> $common_header->getCode(),
	"common_footer"	=> $common_footer->getCode(),
);

$home = html::evalTemplate($tpl, $home_vars, TRUE);

/*
la etiqueta </html> por prettier se cierra automáticamente
en tpl.head_data.html del modulo meta_data y se elimina al llamar
html::evalTemplate. Es necesario agregarla manualmente
*/

$home .= '</html>';
echo $home;


echo "Welcome to yard sales";
echo "<img src=\"https://yardsale.web.chy/assets/img/logo-chrome.png\">";
echo "data";

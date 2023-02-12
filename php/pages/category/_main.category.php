<?php
stop("This area is the same in home but with some changes because URL");
$tplLib = __DIR__;

$meta = new meta_data();
$meta->title = "Copyright | YardSale";
$meta->description = "Copyright imformation Yard Sale project";
$meta->subject = "Project's Copyright";
$meta->keywords = "yard, sale, copyright, ecommerce, project";
/* header */
$common_header = new common_header();

echo ($common_header->getCode());
stop("COPYRIGHT DATA!!!");
/*footer */
$common_footer = new common_footer();

$dataNavError = array(
	"head" => $meta->getCode(),
	"common_header" => $common_header->getCode(),
	"common_footer" => $common_footer->getCode(),
);
echo html::evalTemplate($tplLib . DS . "tpl.nav_error.html", $dataNavError, TRUE);
die();

<?php
require_once("class.product_categories.php");
class product extends product_categories
{
	//DB Data
	private $idProduct;					//Unique identifier for product
	private $name;							//Product name
	private $description;				//Product description
	private $price;						//Product price
	private $idImage;						//Image id for main image
	private $productCategories = array();	//Product categories
	private $active;						//Boolean to identify if product is avaliable/active
	private $added;						//Timestamp when product was added
	private $modified;					//Timestamp when product was modified
	private $productImagesList;		//
	private $tpl_lib;		// carpeta de donde se obtienen los templates

	function __construct($db = FALSE)
	{
		if ($db === TRUE) { // si DB es true se van a tulizar funciones que requieren consulta en la base de datos
			$this->productCategoriesList = $this->get_product_categories();
		}
	}

	public static function get_category_url_request()
	{
		return $_GET['category'] ?? "";
	}
	public function get_product_categories($complete = FALSE)
	{
		return $this->get_categories($complete);
	}
}

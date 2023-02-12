<?php
class product_categories extends databaseobject
{
	//DB Data
	private $name;				//Category's name
	private $description;	//Category description
	private $enable;			//Boolean that enables or disable the category

	//Lists
	protected $productCategoriesList;	//List of all categories

	public function __construct()
	{
		stop("Construct de product_categoires");
	}

	protected function get_categories($complete = FALSE)
	{
		if ($complete && user::$level >= 40) {
			$sql = "SELECT * FROM `pcbogcom_yardsale`.`product_categories`;";
		} else {
			$sql = "CALL sp_product_getAllCategories();";
		}
		$data = array();
		foreach (($this->query($sql, $data)) as $val) {
			$cats[$val['name']]['description'] = $val['description'];
		}
		return $cats;
	}

	public function current_category()
	{
	}
}

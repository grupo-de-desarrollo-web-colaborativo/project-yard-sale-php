<?php
require_once("class.query.php");

class DataBaseObject extends Query
{

	function __construct(string $db = null, $table = null)
	{
		preprint($this);
		stop();
		$this->setConexion($db, $table);
	}

	public function queryToClass(string $sql, array $data = NULL, bool $self = FALSE)
	{
		stop();
		$data = $this->query($sql, $data)[0];
		$class = get_called_class();
		$class = new $class;
		foreach (array_merge($class::$dbViewFields, $class::$dbFields) as $field) {
			if (property_exists($class, $field)) {
				if ($self) {
					$this->$field = $data[$field];
				} else {
					$class->$field = $data[$field];
				}
			}
		}
		if ($self) {
			return;
		} else {
			return $class;
		}
	}
}
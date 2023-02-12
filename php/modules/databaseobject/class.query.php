<?php
require_once("class.databaseobject.php");

class Query
{
	//datos de uso de la base de datos
	protected $conn;				//varible con la conexión a la DB
	protected $lastSql;			//Ultima consulta realizada
	public $sql;					//Petición SQL publica
	protected $dbRowcount;		//Numero de líneas devueltas
	protected  $msg;				//Mensajes de resultados o procesos de consulta
	protected $lastInsertID;	//id insertado en la ultima consulta realizada

	//Datos de la base de datos
	private static $key4Pass = KEY_4_PASS;	//clave de codificación de textos
	protected $db;				//base de datos predeterminada que usará el usuario

	public static function decode($string)
	{
		// funcion para decodificar textos
		$ascii = '';
		$string = str_replace(" ", "", $string);
		for ($i = 0; $i < strlen($string); $i = $i + 2) {
			$ascii .= chr(hexdec(substr($string, $i, 2)));
		}
		$result = '';
		$ascii = base64_decode($ascii);
		for ($i = 0; $i < strlen($ascii); $i++) {
			$char = substr($ascii, $i, 1);
			$keychar = substr(self::$key4Pass, ($i % strlen(self::$key4Pass)) - 1, 1);
			$char = chr(ord($char) - ord($keychar));
			$result .= $char;
		}
		return $result;
	}

	public static function encode($string)
	{
		// funcion para codificar textos
		$result = '';
		for ($i = 0; $i < strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr(self::$key4Pass, ($i % strlen(self::$key4Pass)) - 1, 1);
			$char = chr(ord($char) + ord($keychar));
			$result .= $char;
		}
		$result = base64_encode($result);
		$hex = '';
		for ($i = 0; $i < strlen($result); $i++) {
			$byte = strtoupper(dechex(ord($result[$i])));
			$byte = str_repeat('0', 2 - strlen($byte)) . $byte;
			$hex .= $byte . " ";
		}
		$codificado = str_replace(" ", "", $hex);
		return $codificado;
	}

	public function query(string $sql, array $data = null)
	{
		$sql = trim($sql);
		!is_object($this->conn) ? $this->setConexion() : null;
		$this->lastSql = $sql;
		if (is_null($data)) {
			die("<br><br><b>La consulta no se puede realizar pues falta el parametro de datos o no es del tipo requerido.</b><br> Si ejecuta un procedimiento almacenado debe ser del siguiente modo:<br><br>\$query = \"call procedimiento();\";<br>\$params=array('Dato1'.'Dato2');<br>Si el procedimiento no requiere de parametros, se debe enviar un arreglo vacío");
			//			prePrint('Ej: call routine("a",0);\n\nSe solicitó: '. $sql);
		}

		if (str_starts_with(mb_strtolower($sql), "call ") && str_ends_with($sql, ");")) {
			//obtener las variables del call

			//cambiar call por CALL
			$fixSql = "CALL ";
			$fixSql .= mb_substr($sql, 5, strrpos($sql, ");") - 5);
			if (count($data) == 1 && empty($data[0])) {
				$data = array();
			} else {
				//generar el nuevo SQL
				for ($i = 0; $i < count($data); $i++) {
					$fixSql .= "?,";
				}
				$fixSql = trim($fixSql, ",");
			}
			$sql = $fixSql . ");";
		} else if (str_starts_with(mb_strtolower($sql), "call ") && str_ends_with($sql, ");") === FALSE || str_ends_with($sql, ";") === FALSE) {
			die("<br><br><b>La consulta está mal escrita.</b><br>Siempre debe terminar con punto y coma \";\" y en el caso de procedimientos debe tener el nombre del procedimiento y terminar con \"();\"<br><br>SELECT * FROM table;<br>CALL procediminto();<br>");
		}

		try {
			$request = $this->conn->prepare($sql);
			$request->execute($data);
			$this->lastInsertID = $this->conn->lastInsertId();
			$resultSet = $request->fetchALL(PDO::FETCH_ASSOC);
			$this->msg = "Última consulta realizada con exito";
		} catch (PDOException $e) {
			$this->msg = "Consulta ejecutada con errores";
			if ($e->getCode() == 42000) {
				print "¡Error!: PDOException: No es posible los obterner datos <br>";
				print "SQL = $sql";
				print FETCH ? "\n" : "<br>";
				print "Params: <pre>";
				print_r($data);
				print "</pre>";
				print FETCH ? "\n\n" : "<br><br>";
				print $e->getMessage();
			} else {
				preprint($sql);
				prePrint($data);
				prePrint($e->getMessage());
			}
			die();
		}
		$this->dbRowcount = $request->rowCount();
		return ($resultSet);
	}

	private function confirmar_consulta($resultado)
	{
		stop();
		if (!$resultado) {
			die('<div>No se pudo realizar la consulta "' . $this->lastSql . '". Intente nuevamente.<br><br>' . $this->conn->error . '</div>');
		} else {
			$this->msg = "Verificación de " . $this->lastSql . " realizada con éxito.";
			return TRUE;
		}
	}

	protected function setConexion($db = NULL)
	{

		if (!is_null($db)) {
			$this->db = $db;
		} else {
			$this->db = DB;
		}
		if (isset($_SESSION[PROJECT]['level']) && $_SESSION[PROJECT]['level'] >= 40) {
			$dbMP = USERS_DB['admin']['pass'];
			$dbUser = USERS_DB['admin']['user'];
		} else {
			$dbMP = USERS_DB['user']['pass'];
			$dbUser = USERS_DB['user']['user'];
		}
		try {
			$this->conn = new PDO('mysql:host=' . SERVER_DB . ';dbname=' . $this->db . ";charset=utf8", $dbUser, $dbMP);
			$this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			print "¡Error!: " . $e->getMessage() . '<br>';
			die();
		}
		$this->msg = "conexión realizada con exito";
	}
}

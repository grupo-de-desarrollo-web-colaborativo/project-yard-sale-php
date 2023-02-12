<?php
class User extends dataBaseObject
{
	//datos identificativos del usuario 

	//datos en DB
	protected $idUser;	//Identificador único del usuario en la DB
	protected $name;		//Primer Nombre
	protected $pass;		//Contraseña de acceso
	protected $email;		//Correo electrónico del usuario (correo de acceso)
	protected $idLevel;	//Nivel numérico del usuario

	//Datos obtenidos del usuario en la clase User
	static $level;			//Es el mismo nivel que $idlevel pero publico
	public $range;			//Rango o rol del usuario";
	public $logged;		//Boleano para identificar si un usuario esta logeado o no

	//atributos para uso dentro de la clase
	private $tplLib;				//carpeta de la de templates que le compete a la clase usuario
	static private $userList;	//Listado de usuarios obtenidos desde la DB

	/******************************
	Arreglos con datos desde la DB
	 ******************************/
	private $userTypesList = array();


	//Se pueden utilizar usuarios no globales para ejecutar funciones sin afectar los datos del usuario actual, simplemente no pasar ningun parametro para el usuario 
	public function __construct(bool $initialUser = FALSE)
	{
		$this->tplLib = (__DIR__);
		//verificación para inicializar el usuario que ingresó en la página, no es uno generico para ejecutar metodos de la clase
		if ($initialUser === TRUE) {
			if (empty($this->userTypesList)) {
				$this->getUserTypes();
			}
			$this->idUser = databaseobject::decode($_SESSION[PROJECT]['idUser']);
			if ($_SESSION[PROJECT]['logged'] === TRUE) {
				stop('revisar que pasa cuando el usuario inició sesion');				//El usuario como tal no se debe instanciar aún, ese proceso debería hacerse solo cuando se requieran datos del usuario!
				$this->logged = TRUE;
				$this->idLevel = $_SESSION[PROJECT]['level'];
				stop('$this->loguser debe ejecutarse?');
			} else {
				$this->logged = FALSE;
				$this->idLevel = 1;
			}
			$this->range = $this->userTypesList[$this->idLevel];
			self::$level = $this->idLevel;
		}
	}

	private function getUserTypes()
	{
		$sql = "CALL sp_user_getAllusersLevel();";
		$dbData = $this->query($sql, []);
		foreach ($dbData as $dbrow) {
			$this->userTypesList[$dbrow['idUser_level']] = $dbrow['name'];
		}
	}
	/*
	private function logUser($level, $id)
	{
		$_SESSION[PROJECT]['logged'] = TRUE;
		$_SESSION[PROJECT]['level'] = $level;
		$_SESSION[PROJECT]['idUser'] = databaseobject::encode($id);
	}
	private function getUserByEmail($email)
	{
		$sql = 'call getUserByEmail(' . $email . ');';
		$user = $this->query($sql);
		if ($this->dbRowcount == 1) {
			$user = $user[0];
		} else {
			$user = [];
		}
		return $user;
	}
*/
}

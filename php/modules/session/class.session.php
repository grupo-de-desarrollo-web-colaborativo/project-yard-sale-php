<?php
class Session
{
	private $autoClose;				//Bandera que delimita si se debe o no cerrar automaticamente la sesion
	private $dest;						//Destino a donde se redigirá el navegador luego de eliminar los elementos de la sesion
	private $lastActivity;			//Timestamp que determina la hora en que el usuario estuvo activo por ultima vez
	private $tplLib = __DIR__;		//carpeta de templates que le compete a la clase session
	private $idUser;					//ID de usuario actual
	static $logged;					//Bandera que determina si el usuario inició o no session

	public function __construct()
	{
		($this->isSessionStarted() === FALSE) ? session_start() : NULL; //verificación e inicio de session si es requerido
		$this->checkLogin(); //verificar si se ha iniciado sesion previamente
		$this->checkAutoClose(); //Verificar cierre de sesion automático
		$this->redirect(); //Verificar y ejecutar redireccion a alguna página
	}

	public static function close_session()
	{
		$redirect_url = $_SESSION[PROJECT]['redirect'] ?? ACTUAL_URL;

		setcookie('PHPSESSID', '', time() - 1, '/');
		unset($_SESSION);
		session_unset();
		session_destroy();
		session_start();
		header("Location: $redirect_url", true, 301);
		die();
	}

	private function checkLogin()
	{
		self::$logged = $_SESSION[PROJECT]['logged'] = $_SESSION[PROJECT]['logged'] ?? FALSE;
	}

	private function checkAutoClose(bool $force = FALSE)
	{

		//En caso de ser una llamada desde fetch, las opciones de session se reducen por completo, eso se trabaja desde el php para fetch
		if (FETCH) {
			return;
		}

		//Colocar en la clase desde sesion el cierre automático de session o asignarlo como true;
		$this->autoClose = isset($_SESSION[PROJECT]['autoClose']) ? (bool) $_SESSION[PROJECT]['autoClose'] : TRUE;

		//Colocar en la clase el id de usuario desde session o asignar 0 si no existe
		$this->idUser = $_SESSION[PROJECT]['idUser'] = $_SESSION[PROJECT]['idUser'] ??  DataBaseObject::encode(0);

		//Colocar en la clase el timestamp de la ultima actividad o asignar el tiempo actual
		$this->lastActivity = isset($_SESSION[PROJECT]['lastActivity']) ? (int) $_SESSION[PROJECT]['lastActivity'] : NOW;

		//verificación de cierre de sesion forzado
		if (
			$force //force está como true?
			|| ((self::$logged === FALSE) && ($this->idUser != DataBaseObject::encode(0))) // o si el id de usuario fue manuipulado
			|| ($this->autoClose === TRUE && NOW - $this->lastActivity >= TIME_LOG_OUT) //o si pasó el tiempo para auto cierre
			|| (ACTUAL_URL == SERVER_URL . "/logout") // o si es la URL de salida
		) {
			//eliminar y reiniciar datos de sesion
			self::close_session();
		} else {
			//Si no hay manipulación y no se debe cerrar sesion se actualiza el dato de ultima actividad
			$this->lastActivity = $_SESSION[PROJECT]['lastActivity'] = NOW;
		}
	}

	private function isSessionStarted()
	{
		//verificar si la session con session_started() ha sido ya iniciado para evitar conflicto
		if (PHP_SAPI !== 'cli') {
			if (version_compare(phpversion(), '5.4.0', '>=')) {
				return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
			} else {
				return session_id() === '' ? FALSE : TRUE;
			}
		}
		return FALSE;
	}

	private function redirect()
	{
		//si existe un dato de redirección en sesion y el dato no es el mismo de la URL actual lo redirige
		if (isset($_SESSION[PROJECT]['dest']) && ACTUAL_URL != $_SESSION[PROJECT]['dest']) {
			header("Location: " . $_SESSION[PROJECT]['dest'], TRUE, 301);
		} else if (isset($_SESSION[PROJECT]['dest'])) {
			//si existe el dato de redirección (dest) se elimina, por la compación de ACTUAL_URL se sabe que estamos en la URL de destino.
			unset($_SESSION[PROJECT]['dest']);
		}
	}
}

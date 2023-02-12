<?php
$db_name = $server_prefix . '_yardsale';					//Default database name

//data for user admin
$user_db_admin  = $server_prefix . "_ys_admin";	//Usuario administrador de la DB
$pass_db_admin = "((\U>j*2WZ_ys_C]L7Q#A*";			//Contraseña para el administrador de la DB

//data for user no admin
$user_db  = $server_prefix . "_ys_general";				//Usuario usuario normal de la DB (permiso solo de 'EXECUTE')
$pass_db = ")}r@&[6m_ys_zps5D*A3.";					//Contraseña para el usuario normal de la DB

//other db data
$server_db_name = 'localhost';						// servidor de la DB (generalmente no necesita cambio)
$null_db = 'NULL_YS';									//Dato nulo en DB
$key_4_pass = 'y4rD{S4l3}v!';					// clave para codificación de cadenas de texto


//SQL to create DB and users

// $createDb = "
// SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0; SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0; SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'; DROP SCHEMA IF EXISTS `" . $db_name . "`; CREATE SCHEMA IF NOT EXISTS `" . $db_name . "` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci; USE `" . $db_name . "`;";
// 
// $createAdmin = "DROP USER IF EXISTS '" . $user_db_admin . "'@'localhost'; CREATE USER '" . $user_db_admin . "'@'localhost' IDENTIFIED BY '" . $pass_db_admin . "'; GRANT USAGE ON *.* TO '" . $user_db_admin . "'@'localhost' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0; GRANT ALL PRIVILEGES ON `" . $db_name . "`.* TO '" . $user_db_admin . "'@'localhost' WITH GRANT OPTION;";
// 
// $createUser = "DROP USER IF EXISTS '" . $user_db . "'@'localhost'; CREATE USER '" . $user_db . "'@'localhost' IDENTIFIED BY '" . $pass_db . "'; GRANT USAGE ON *.* TO '" . $user_db . "'@'localhost' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0; GRANT EXECUTE ON `" . $db_name . "`.* TO '" . $user_db . "'@'localhost';";
// 
// echo $createDb . "<br>";
// echo $createAdmin . "<br>";
// echo $createUser . "<br>";
// die("<br><br><br><br> Die @db-data.php in line 33 or closer");

// unset($createDb);
// unset($createAdmin);
// unset($createUser);
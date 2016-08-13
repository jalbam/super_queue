<?php
	// Super Queue
	// * Chinese translation: 董双丽
	// * Graphics: 乔安
	// * Code: Joan Alba Maldonado


	header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", FALSE);
	header("Pragma: no-cache");

	//Includes the config file and the data base object :
	require_once "config.php";
	require_once "php/db.php";

	//Connects to the database:
	$dataBase = new DataBase();
	$dataBase->connect(DB_HOST, DB_USER, DB_PASSWORD);
	$dataBase->select(DB_NAME);

	//Includes the required files:
	require_once "php/operations.php";
	require_once "game_finished.php";
	require_once "get_user_host.php";
	require_once "identify_user.php";
	require_once "html.php";

	//Disconnects from the database:
	$dataBase->disconnect();
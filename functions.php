<?php

// function __autoload($class) { include $class.'php'; }
// spl_autoload_register(function($class){ try { include_once $class.".php"; } catch (Exception $e) { Enigma::notify(ERR_LOAD_CLASS.$e->getError()); } });

if(!CORE_EXTAL) {
		# libs
	require_once "lib/phpass-0.3/PasswordHash.php";
		# core
	require_once "class/core.class.php";
		# io module
	require_once "class/io.class.php";
		# shield module
	require_once "inc/shield.inc.php";
	require_once "class/shield.class.php";
		# sql module
	require_once "class/sql.class.php";
		# client module
	require_once "class/client.class.php";
		# fs module
	//require_once "class/fs.class.php";
}

//if(class_exists(CORE_CLASS)) set_error_handler('__error') and set_exception_handler('__exception'); else die(CORE_NULL);
!class_exists(CORE_CLASS) and die(CORE_NULL);
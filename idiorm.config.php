<?php
	require_once "idiorm.php";

	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

	ORM::configure('mysql:host=' . 'localhost' . ';dbname=' . 'mahjong');
	ORM::configure('username', 'mahjong');
	ORM::configure('password', '');
	ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

<?php

require "inc/settings.inc.php";

class Settings {
	// Generated errors list
	// public static $ERRLIST = [
	// 	303 => "See Other",
	// 	403 => "Forbidden",
	// 	404 => "Not Found",
	// 	500 => "Internal Server Error",
	// 	502 => "Bad Gateway",
	// 	503 => "Service Unavailable",
	// 	504 => "Gateway Timeout"
	// ];

	public static $ERRLIST = [
		303 => "See Other",
		403 => "Forbidden",
		404 => "Not Found",
		500 => "Internal Server Error",
		502 => "Bad Gateway",
		503 => "Service Unavailable",
		504 => "Gateway Timeout"
	];

	// Autospamlist
	public static $SPAMLIST = [
		'adf.ly',
		'allshort.ru',
		'bc.vc',
		'bit.ly',
		'clck.ru',
		'cli.gs',
		'cos30.ru',
		'cutlink.info',
		'go2.me',
		'goo.gl',
		'is.gd',
		'itw.me',
		'linkshrink.net',
		'okey.link',
		'ouo.io',
		'ow.ly',
		'sh.st',
		'snipurl.com',
		'soe.ru',
		'tiny.cc',
		'tinyurl.com',
		'tr.im',
		'u.to',
		'vk.cc'
	];

	// Special engine features
	public static $SPECIALS = [
		'##useragent##'
	];

	// Basic themes
	public static $CSS = [
		0 => 'blink',
		1 => 'flow',
		2 => 'neo',
		9 => 'zero'
	];

	// Static pages list
	public static $PAGELIST = [
		"home" => "Глагне",
		"news" => "Новости",
		"faq" => "Щито?",
	];

	// Dynamic cat pages list
	public static $BOARDLIST = array();

	// Special dynamic cat pages list
	public static $BOARDLIST_S = array();

	// Unlisted pages list for services
	public static $HIDNLIST = [
		"login" => "Войти",
		"options" => "Настройки",
		"test" => "Тест"
	];

	// Defines whether thread image is required in this category or not
	public static $IMGREQ = [
		"b" => true,
		"test" => false,
		"o" => false
	];

	public static function init() { // initialize || reset
		$list = SQL::q("SELECT `index`, `title` FROM `boards` ORDER BY `bid` ASC");
		while($row = mysqli_fetch_row($list)) self::$BOARDLIST[ $row[0] ] = $row[1];
		$list = SQL::q("SELECT `index`, `title` FROM `cknn_boards` ORDER BY `bid` ASC");
		while($row = mysqli_fetch_row($list)) self::$BOARDLIST_S[ $row[0] ] = $row[1];
		
		$_SESSION["current"] = self::getpage();
	}
	public static function getpage($i = 1) { // get page identificator (index from address line)
		$routes = explode('/', mb_strtolower(SQL::escape($_SERVER['REQUEST_URI']), 'UTF-8'));
		return (is_null($routes[$i]) ? $routes[0] : $routes[$i]);
	}
	public static function getpagearray() { // get page path divided in array
		return explode('/', mb_strtolower(SQL::escape($_SERVER['REQUEST_URI']), 'UTF-8'));
	}
	public static function getpagename($index = null) { // get page name based on it's identificator (index from pages lists)
		if($index) return array_merge(self::$BOARDLIST,self::$PAGELIST,self::$HIDNLIST)[$index];
		return null;
	}
	public static function listboards($cknn = false) { // list all categories with names
		$links = "";
		if($cknn) foreach (Settings::$BOARDLIST_S as $key => $value) $links .= "<li><a data-service-dynlink class='smooth' href='/{$key}'>/{$key}/ - {$value}</a></li>";
		else foreach (Settings::$BOARDLIST as $key => $value) $links .= "<li><a data-service-dynlink class='smooth' href='/{$key}'>/{$key}/ - {$value}</a></li>";
		return $links;
	}
	public static function listpages() { // list all static pages with names
		$pages = "";
		foreach (Settings::$PAGELIST as $key => $value) $pages .= "<li><a data-service-dynlink class='smooth' href='/{$key}'>{$value}</a></li>";
		return $pages;
	}
	public static function imagerequired($board = null) { // safe function to check if the image is required
		//if(is_null($board) || !in_array($board, array_keys(self::$IMGREQ))) return false;
		return isset(self::$IMGREQ[$board])?self::$IMGREQ[$board]:false;
	}
	public static function is_board($index){ if(Settings::$BOARDLIST[$index] || Settings::$HIDNLIST[$index]) return true; }

	public static function getcss($type) {
		$c = json_decode($_COOKIE['ae_board_options'], true);
		$csslist = array();
		
		// main css file
		$theme = empty($c['THM']) ? 'blink' : Settings::$CSS[intval($c['THM'])];
		$csslist[0] = "<link rel='stylesheet' type='text/css' media='screen' href='/css/theme_{$theme}.css' />";

		// smooth optional
		if(json_decode($c['SMTH']) == true) $csslist[1] = "<link rel='stylesheet' type='text/css' media='screen' href='/css/smooth.css' />";

		return implode($csslist);
	}
}
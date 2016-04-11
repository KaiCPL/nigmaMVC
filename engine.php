<?php
class Controller {	
	public $model;
	public $view;

	function __construct() { //override in self-controller
		$this->view = new View();
	}
	function action_index() {}
}
class Model { public function get_data() {} }
class View {
	//public $template = "frame.php";
	function generate($content, $template, $data=null) {
		// эта функция задаёт в первую очередь область видимости, из которой видна $data и остальное
		include 'views/'.$template;
	}
}

class Route {
	static function servicecall($object) {
		switch ($object) {
			case 'frames': return true;
			case 'music': return true;
			case 'options': return true;
			default:return false;
		}
		return true;
	}

	static function start() {
		// DEFAULT
			// сервисные функции при надобности будут вызваны сразу, без продолжения генерации
		$service = array('toggle', 'enable', 'disable');
			// мы не подхватываем контент из main, мы только генерируем фрейм, а уже в нём подгружается статический контент
		$cname = 'main';
		$mname = $cname;
		$action = 'index';

		// NAMES
		$routes = explode('/', mb_strtolower(SQL::escape($_SERVER['REQUEST_URI']), 'UTF-8'));
		//if(!$_SESSION['io_in'] && in_array($routes[1], array_keys(array_merge(Settings::$BOARDLIST,Settings::$BOARDLIST_S)))) $routes[1] = "o";
		if(!empty($routes[1])) $cname = $routes[1];
		if(!empty($routes[2])) $action = $routes[2];
		if(!empty($routes[3])) $subaction = $routes[3];

		// SERVICE CALLS
		if(in_array($cname, $service)) exit(Route::servicecall($action));

		//__log("was: ".$_COOKIE["io_music"]);
		//$_COOKIE["io_music"] = ($_COOKIE["io_music"] == "on" ? "off" : "on");
		//__log("now: ".$_COOKIE["io_music"]);

		// MODEL
		$mfile = "models/{$mname}.php";
		if(file_exists($mfile)) include $mfile;

		// CONTROLLER
		$board = (in_array($routes[1], array_keys(Settings::$BOARDLIST)) ? $routes[1] : "");
			//if(!isset($_SESSION['io_in']) || !$_SESSION['io_in']) $board = 'o';
		$cfile = (empty($board) ? "controllers/{$cname}.php" : "controllers/board.php");
		$cname = (empty($board) ? $cname : "board");

		// DEFINE CONTROLLER TREE
		if(in_array($routes[1], array_keys(Settings::$ERRLIST))) {
			$cfile = "controllers/error.php";
			$cname = "Error";
			$construct_argument = $routes[1];
		}
		elseif(in_array($routes[1], array_keys(Settings::$BOARDLIST)) || in_array($routes[1], array_keys(Settings::$BOARDLIST_S))) {
			$cfile = "controllers/board.php";
			$cname = "Board";
		}
		elseif(in_array($routes[1], array_keys(Settings::$PAGELIST)) 
			|| in_array($routes[1], array_keys(Settings::$HIDNLIST)) 
			|| empty($routes[1])
			|| ($routes[1] == '/')) {
				$cfile = "controllers/{$cname}.php";
		}
		else Route::redirect(404);

		if(!file_exists($cfile)) Route::redirect(500); // we have page controller defined in settings but somehow we don't have a controller, it's engine error
		include $cfile;
		$controller = (isset($construct_argument) ? new $cname($construct_argument) : new $cname);
		
		// METHOD
		if(!method_exists($controller, $action)) Route::redirect(404);
		if(isset($subaction)) $controller->$action($subaction);
		else $controller->$action();
	}

	static function redirect($code = 303) {
		$status = "Not Found";
		if(in_array($code, array_keys(Settings::$ERRLIST))) $status = Settings::$ERRLIST[$code];
		$addr = "/".$code;
		if(is_nan($code)) $code = 404;
		if($code === 303) $addr = $_SERVER['HTTP_REFERER'];
		if($code === 0) { $code = 303; $addr = "/"; }
		$code = intval($code);
		//echo $http."<br/>".$stat."<br/>".$addr."<br/>";
	
		header("HTTP/1.1 {$code} {$status}");
		//header("Status: {$code} {$status}");
		header("Location: ".$addr, false, 303);

		/*
		if(!headers_sent()) {
			echo "HTTP/1.1 {$code} {$status}<br/>";
			echo "Status: {$code} {$status}<br/>";
			echo "Location: ".$addr;
		}
		*/
		exit();
		return false;
	}

	static function fatal($code) { echo "/!\ [{$code}] Фатальная ошибка ядра. Пожалуйста, не делайте больше того, что вы только что сделали."; }

	static function load($content) {}
}

?>
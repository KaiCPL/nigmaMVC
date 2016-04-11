<?php

function __error($errno, $errstr, $errfile, $errline) {
	if(!error_reporting() && $errno) return;
	switch($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			Enigma::notify("[note] [{$errno}] ~ {$errstr}");
			break;
		case E_WARNING:
		case E_USER_WARNING:
			Enigma::notify("[warning] [{$errno}] ~ {$errstr}");
			break;
		case E_ERROR:
		case E_USER_ERROR:
			Enigma::notify("[error] Фатальная ошибка в строке [{$errline}] файла [{$errfile}]. [{$errno}] ~ {$errstr}");
			exit(1);
			break;
		default:
			Enigma::notify("[?] Произошла неизвестная ошибка. [{$errno}] ~ {$errstr}");
			break;
	}
	return true;
}

function __exception($e) {
	Enigma::notify("[exception] Неперехватываемое исключение. @ ".$e->getMessage());
}

function __log($msg) {
	$time = date('l jS \of F Y | h:i:s');
	$f = fopen("notes.log", 'a+b');
	if(flock($f, LOCK_EX)) {
		fwrite($f, "[{$time}] ".$msg.PHP_EOL);
		fflush($f);
		flock($f, LOCK_UN);
	}
	fclose($f);
}

function __url() { return "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"; }

function __serverecho() {
	$index = ['PHP_SELF', 'argv', 'argc', 'GATEWAY_INTERFACE', 'SERVER_ADDR', 'SERVER_NAME', 'SERVER_SOFTWARE', 'SERVER_PROTOCOL', 'REQUEST_METHOD', 'REQUEST_TIME', 'REQUEST_TIME_FLOAT', 'QUERY_STRING', 'DOCUMENT_ROOT', 'HTTP_ACCEPT', 'HTTP_ACCEPT_CHARSET', 'HTTP_ACCEPT_ENCODING', 'HTTP_ACCEPT_LANGUAGE', 'HTTP_CONNECTION', 'HTTP_HOST', 'HTTP_REFERER', 'HTTP_USER_AGENT', 'HTTPS', 'REMOTE_ADDR', 'REMOTE_HOST', 'REMOTE_PORT', 'REMOTE_USER', 'REDIRECT_REMOTE_USER', 'SCRIPT_FILENAME', 'SERVER_ADMIN', 'SERVER_PORT', 'SERVER_SIGNATURE', 'PATH_TRANSLATED', 'SCRIPT_NAME', 'REQUEST_URI', 'PHP_AUTH_DIGEST', 'PHP_AUTH_USER', 'PHP_AUTH_PW', 'AUTH_TYPE', 'PATH_INFO', 'ORIG_PATH_INFO'];
	print '<table cellpadding="10">' ; 
	foreach ($index as $arg) isset($_SERVER[$arg]) ? (print '<tr><td>'.$arg.'</td><td>'.$_SERVER[$arg].'</td></tr>') : (print '<tr><td>'.$arg.'</td><td>(not set)</td></tr>');
	print '</table>' ;
}

function __normalizepath($path) {
    $parts = array();
    $path = str_replace('\\', '/', $path);
    $path = preg_replace('/\/+/', '/', $path);
    $segments = explode('/', $path);
    $test = '';
    foreach($segments as $segment) if($segment != '.') {
		$test = array_pop($parts);
		if(is_null($test)) $parts[] = $segment;
		else if($segment == '..') {
			($test == '..') and $parts[] = $test;
			($test == '..' || $test == '') and $parts[] = $segment;
		}
		else {
			$parts[] = $test;
			$parts[] = $segment;
		}
	}
    return implode('/', $parts);
}

function __define($constarr) { return array_map("define",array_keys($constarr),array_values($constarr)); }

function __translate_arrays($string, $array){
	return preg_replace_callback("/\b_(\w*)_\b/u", function($match) use ($array) {
		if(isset($array[$match[0]])) return $array[$match[0]];
		return $match[0];
	}, $string);
}

function __quote() {
	$q = array(
		"oops! sqrt(-3) is nan.",
		"enjoy your mellow coffee.",
		"you are now in a shadow mode.",
		"we have your cookies now.",
		"all your base are now belong to us.",
		"antifreeze & red mohawk.",
		"supreme dream mastery.",
		"an adventurous race against time.",
		"you are now in a quantum superposition.",
		"we don't give a fuck.",
		"lex parsimoniae.",
		"cubage of sphere.",
		"mirrors in your head.",
		"zomfght.",
		"we are the Monolith.",
		"random operators.",
		"go through the portal.",
		"cognac & rain.",
		"don't fuck with us.",
		"wtf.",
		"fear is the only enemy.",
		"noone to ask.",
		"novem essentia.",
		"the door of improbability.",
		"scientia potentia est.",
		"omnis qui quaerit invenit.",
		"qui arcanum asservent.",
		"nocturna adoperta.",
		"hic requiescit efg.",
		"42.",
		"through the looking glass.",
		"the wanderes of the fallen worlds.",
		"<!--we seek for hidden.-->",
		"the end of your trivial existence.",
		"97,34% probability.",
		"citadel station.",
		"mind hack.",
		"B0 2E E7 70.",
		"in silico.",
		"..."
	);
	shuffle($q);
	return "&lt;{$q[0]}&gt;";
}
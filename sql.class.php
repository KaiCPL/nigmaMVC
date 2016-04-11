<?php

Class SQL {
	private static $instance = FALSE;
	private static $transactions = array();
	private static $defargs = array(DB_HOST, DB_USER, DB_PASS, DB);

	// no need if static but we have singletone so override
	public function __construct() { self::$instance = self::connect(); }
	public function __destruct() { self::$instance->close(); }
	public function __clone() { /* @return Singleton */ }
	public function __sleep() { return self::$defargs; }
	public function __wakeup() { self::connect(); }
	//public function __invoke($directive=null) { return $directive; }
	//public function __set_state() { return []; }
	//public function __toString() { return '[sql protected]'; }
	//public function __debugInfo() { return []; }


	public static function get() { return self::$instance; }

	public static function connect(array $args = null) {
		$instance = FALSE;
		try {
			if(!empty($args))
				$instance = new mysqli($args['host'], $args['user'], $args['pass'], $args['database']);
			else
				$instance = new mysqli(self::$defargs[0], self::$defargs[1], self::$defargs[2], self::$defargs[3]);
			if ($instance->connect_error) { die("<div class='notification'>Ошибка подключения (" . $instance->connect_errno . ") " . $instance->connect_error . "</div>"); }
			if (!$instance->set_charset("utf8")) { printf("<div class='notification'>Ошибка при загрузке набора символов utf8: %s\n", $instance->error . "</div>"); }
		} catch (Exception $e) {
			die("<div class='notification'>Catchable SQL class error (".$e->getCode().") on chunk line: ".$e->getMessage() . "</div>");
		}
		return $instance;
	}

	public static function check() { if(!self::$instance) self::$instance = self::connect(); }

	public static function escape($string) {
		$string = nl2br(htmlspecialchars(trim($string)));
		/*
		if(self::$instance !== NULL) {
			$string = self::$instance->real_escape_string($string);
		}
		*/
		return $string;
	}

	public static function q($query, $type=FALSE, $external_instance=null) {
		if(!is_string($query)) { die("<div class='notification'>[error -1] ~ String expected</div>"); exit(); }
		self::check();
		$connection = empty($external_instance) ? self::$instance : $external_instance;
		$result = $connection->query($query)
			or die("<div class='notification'>[error ".$connection->connect_errno."] ~ ".$connection->error."</div><pre debug>{$query}</pre>");
		if($result && $type) switch ($type) {
			case 'fetch':
				return $result->fetch_array(MYSQLI_NUM);
				break;
			case 'assoc':
				return $result->fetch_array(MYSQLI_ASSOC);
				break;
			case 'fetchrow':
				return $result->fetch_row();
				break;
			case 'solo':
				return current($result->fetch_array(MYSQLI_NUM));
				break;
			default:break;
		}
		self::flush();
		return $result;
	}

	public static function mq($stack, $type=FALSE, $external_instance=null) {
		if(!is_array($stack)) { die("<div class='notification'>[error -1] ~ String expected</div>"); exit(); }
		$inst=empty($external_instance) ? self::$instance : $external_instance;
		$results=array();
		if(!$inst->multi_query(join($stack,'; '))) return FALSE;
		while($inst->more_results() && $inst->next_result()) if($res=$inst->store_result()) {
			$results[]=$res->fetch_all(MYSQLI_ASSOC);
			$res->free();
		}
		self::flush();
		return $results or die("<div class='notification'>[error ".$connection->connect_errno."] ~ ".$connection->error."</div>");
	}

	public static function transact($stack,$external_instance=null) {
		if(!$stack) return true;
		$results=array();
		self::check();
		self::$instance->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
		self::$instance->autocommit(FALSE);
		foreach($stack as $q) $results[]=self::$instance->query($q);
		self::$instance->commit();
		self::flush();
		return $results;
	}

	public static function flush() { while(self::$instance->more_results()) if($_result=self::$instance->store_result()) $_result->free(); }

	public static function e() { return self::$instance->error; }

	public static function lastid() { return self::$instance->insert_id; }
	public static function getLastQueryID(){ return self::$instance->insert_id; }
}
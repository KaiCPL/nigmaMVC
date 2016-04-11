<?php
class Error extends Controller {
	private static $errorcode;
	function __construct($arg) {
		$this->view = new View();
		self::$errorcode = intval($arg);
	}
	function index() { $this->view->generate('error.php', '_static.php', self::$errorcode); }
}
<?php
class Board extends Controller {
	function index() { $this->view->generate('board.php', '_static.php'); }
	function add() {
		if(isset($_POST)) {
			$cat = Settings::getpage(1);
			if(empty($_SERVER['HTTP_REFERER'])) {
				header("Location: /".$cat, true, 303);
				exit();
			}
			header("Location: /{$cat}/", true, 303);
			exit();
		}
	}
}
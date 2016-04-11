<?php
class Options extends Controller {
	function index() { $this->view->generate('options.php', '_static.php'); }
	function save() {}
}
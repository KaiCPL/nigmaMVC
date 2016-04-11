<?php
class Home extends Controller {
	function index() { $this->view->generate('home.php', '_static.php'); }
}
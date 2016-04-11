<?php
class FAQ extends Controller {
	function index() { $this->view->generate('faq.php', '_static.php'); }
}
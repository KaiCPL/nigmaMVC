<?php
class News extends Controller {
	function index() { $this->view->generate('news.php', '_static.php'); }
}
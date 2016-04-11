<?php

Class FS {
	public static function link($link) {
		$link = preg_replace("/(^|[\n ])([\w]*?)((ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a href=\"$3\" >$3</a>", $link);
		$link = preg_replace("/(^|[\n ])([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a href=\"http://$3\" >$3</a>", $link);
		//$link = preg_replace("/(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+)+)/i", "$1<a href=\"mailto:$2@$3\">$2@$3</a>", $link);
		return $link;
	}
	public static function add($link, $name, $desc, $awmd = true) {
		$link = SQL::escape($link);
			if(strlen($link) > 500) exit("<code><span class='error'>Длина ссылки превышает лимит (".strlen($link)."/500 символов)</span></code>");
		$name = SQL::escape($name);
			if(strlen($name) > 100) exit("<code><span class='error'>Название слишком длинное (макс. 100 символов)</span></code>");
		$desc = SQL::escape($desc);
			if(strlen($desc) > 4000) exit("<code><span class='error'>Описание слишком длинное (макс. 4000 символов)</span></code>");
		if(!SQL::q("INSERT INTO fs (`link`, `name`, `description`) VALUES ('{$link}', '{$name}', '{$desc}')"))
			exit("<code><span class='error'>Ошибка на стороне сервера</span></code>");
		exit("<code><span class='state ok'>Ссылка успешно добавлена в [Unsorted]</span></code><script type='text/javascript'>location.reload();</script>");
	}
	public static function remove($lid) {}
	public static function search($inf, $inf_class) {}
	public static function enum($count, $from) {}
	public static function access($uid, $field) {}
}
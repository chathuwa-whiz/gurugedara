<?php

class Connection{

	public function connect(){

		$link = new PDO("mysql:host=127.0.0.1;dbname=gurugedara_db1", "avcedits", "Avc@2024%db");

		$link -> exec("set names utf8");

		return $link;
	}

}
<?php

	require_once('database.class.php');

	// escape variables for security
	$nameIn = $_POST["name"];
	$scoreIn = $_POST["score"];

	// Create database object
	$db = new Database();

	$db->setHighscore($nameIn,$scoreIn);
	echo "added entry to database";

?> 
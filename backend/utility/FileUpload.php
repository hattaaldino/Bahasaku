<?php
	require_once('filetotext.php');

	$targetdir = "isifile\\";
	$file = $_FILES['file'];
	$targetfile = $targetdir . basename($file['name']);
	$targettxt = $targetdir . pathinfo($file['name'], PATHINFO_FILENAME) . ".txt";

	move_uploaded_file($file['tmp_name'], $targetfile);

	$doc = new Filetotext($targetfile);
	$text = $doc->convertToText();

	file_put_contents($targettxt, $text);
	
?>
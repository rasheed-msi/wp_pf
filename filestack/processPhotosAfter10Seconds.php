<?php

	$path = $_SERVER['DOCUMENT_ROOT'];
	$albumId = $_POST['albumId'];
	exec('php ' . $path . '/filestack/processPhotosIfNoWebhook.php ' . $albumId);

?>
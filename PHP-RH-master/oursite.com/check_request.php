<?php
	if($_SERVER['REQUEST_METHOD'] != 'POST') {
		header("HTTP/1.0 403 Forbidden");
		echo "<h1>403 Access Forbidden</h1>";
		exit;
	}
?>

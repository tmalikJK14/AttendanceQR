<?php
	if (isset($_GET['i']) && !empty($_GET['i'])) {
		require '../connection.php';
		$uid = $conn->real_escape_string(strip_tags($_GET['i']));
		$sql = "DELETE FROM `users` WHERE `uid` = '$uid'";
		if ($conn->query($sql)) {
			header('Location: home.php');
			die();
		}
		else {
			header('Location: home.php');
			die();
		}
	}
	else {
		header('Location: home.php');
		die();
	}
?>
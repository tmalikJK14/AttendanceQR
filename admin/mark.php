<?php
	date_default_timezone_set("Asia/Kolkata");
	if (isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['time']) && !empty($_GET['time'])) {
		require '../connection.php';
		$uid = $conn->real_escape_string($_GET['id']);
		$time = $conn->real_escape_string(urldecode($_GET['time']));

		$sql = "SELECT * FROM `users` WHERE `uid` = '$uid'";
		$result = $conn->query($sql);
		$details = $result->fetch_assoc();
		if ($details['access'] == "restricted") {
			echo '5'; //user restricted
			return 5;
		}
		if ($details['access'] == "regular") {
			if ((date("H:i:s", time()) < $details['start_time']) || (date("H:i:s", time()) > $details['end_time'])) {
				echo '6'; //outside hours
				return 6;
			}
		}
		if ($result->num_rows) {
			if (time() - strtotime($time) <= 30) {
				$sql = "SELECT * FROM `attendance` WHERE `uid` = '$uid' ORDER BY `aid` DESC LIMIT 1";
				$result = $conn->query($sql)->fetch_assoc();
				if (date("Y-m-d", strtotime($result['timestamp'])) == date("Y-m-d", time())) {
					if ($result['action'] == "out") {
						$aid = $result['aid'];
						$current = date("Y-m-d H:i:s", time());
						$sql = "UPDATE `attendance` SET `timestamp` = '$current' WHERE `aid` = '$aid'";
						$conn->query($sql);
						if ($conn->affected_rows) {
							echo '2'; //attendance updated!
							return 2;
						}
						else {
							echo '0';
							return 0;
						}
					}
					else {
						$current = date("Y-m-d H:i:s", time());
						$sql = "INSERT INTO `attendance` VALUES (NULL, '$uid', 'out', '$current')";
						if ($conn->query($sql)) {
							echo '1';
							return 1;
						}
						else {
							echo '0';
							return 0;
						}
					}
				}
				else {
					$today = date("Y-m-d H:i:s", time());
					$sql = "INSERT INTO `attendance` VALUES (NULL, '$uid', 'in', '$today')";
					if ($conn->query($sql)) {
						echo '1'; //attendance marked successfully!
						return 1;
					}
					else {
						echo '0'; //attendance not marked!
						return 0;
					}
				}
			}
			else {
				echo '3'; //qr code expired!
				return 3;
			}
		}
		else {
			echo '4'; //user does not exist!
			return 4;
		}
	}
?>
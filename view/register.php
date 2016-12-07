<!DOCTYPE html>
<html>
<head>
	<title>Tugas 1 Individu</title>
</head>
<body>
	<div style="margin-top:10px;"></div>
	<?php
		$object['nama'] = $_POST['name'];
		$object['user_id'] = $_POST['npm'];
		$object['ip_domisili'] = $_POST['ip'];
		$ch = curl_init("https://ilham.sisdis.ui.ac.id/ewallet/register");
		curl_setopt_array($ch, array(
			CURLOPT_POST => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_HTTPHEADER => array('Content-Type:application/json; charset=utf-8'),
			CURLOPT_POSTFIELDS => json_encode($object)
		));
		$response = curl_exec($ch);
		echo $response;
	?>
</body>
</html>

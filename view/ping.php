<!DOCTYPE html>
<html>
<head>
	<title>Tugas 1 Individu</title>
</head>
<body>
	<div style="margin-top:10px;"></div>
	<?php
		$url = $_POST['ip_domisili']."/ewallet/ping";
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_SSL_VERIFYPEER => 1,
			CURLOPT_SSL_VERIFYHOST => 2,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_URL => $url
		));
		$response = curl_exec($ch);
		echo $response;
	?>
</body>
</html>

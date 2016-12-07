<!DOCTYPE html>
<html>
<head>
	<title>Tugas 1 Individu</title>
</head>
<body>
	<div style="margin-top:10px;"></div>
	<?php
		$object['nilai'] = $_POST['nilai'];
		$object['user_id'] = $_POST['user_id'];
		$object['cabang_tujuan'] = $_POST['cabang_tujuan'];
		$ch = curl_init("https://ilham.sisdis.ui.ac.id/ewallet/requestTransfer");
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

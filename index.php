<?php
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$servername = 'localhost';
$username = 'root';
$password = 'root';
$dbname = 'sisdis';
$env = 'http://ilham.sisdis.ui.ac.id/';

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

$app->get('/test', function () {
	$app = \Slim\Slim::getInstance();
	$cabang = getCabangURL();
	$quorum = 0;
	for ($i=0; $i < count($cabang); $i++) {
		$cabang_tujuan = $cabang[$i];
		$url = $cabang_tujuan."/ewallet/ping";
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_URL => $url,
			CURLOPT_CONNECTTIMEOUT => 5,
			CURLOPT_TIMEOUT => 5
		));
		$response = curl_exec($ch);
		$response = json_decode($response);
		if (!empty($response)) {
			$quorum += $response->pong;
		}
	}
	$app->response()->headers->set('Content-Type', 'application/json');
	echo json_encode($quorum);
});

$app->get('/view', function() {
	$app->view()->setTemplatesDirectory('./view');
  $app->render('index.php', array('hehe'=> 'wkwk'));
});

$app->get('/ping', function () {
	ping();
});

$app->get('/getSaldo', function () use ($conn){
	$quo = quorum();
	if ( $quo > 4) {
		$app = \Slim\Slim::getInstance();
		$user_id = $app->request->get('user_id');
		$output["nilai_saldo"] = getSaldo($conn, $user_id);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode($output);
	} else {
		echo "quorum berjumlah ".$quo.". Sehingga operasi tidak dapat dilakukan";
	}
});

$app->get('/getTotalSaldo', function () use ($conn){
	$quo = quorum();
	if ( $quo > 8) {
		$app = \Slim\Slim::getInstance();
		$user_id = $app->request->get('user_id');
		$output["nilai_saldo"] = getTotalSaldo($conn, $user_id);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode($output);
	} else {
		echo "quorum berjumlah ".$quo.". Sehingga operasi tidak dapat dilakukan";
	}
});

$app->post('/register', function () use ($conn) {
	$quo = quorum();
	if ( $quo > 4) {
		$app = \Slim\Slim::getInstance();
		$json = json_decode($app->request->getBody());
		$sql = "INSERT INTO user (user_id, ip_domisili, nama)
		VALUES ('".$json->user_id."', '".$json->ip_domisili."', '".$json->nama."')";
		if (mysqli_query($conn, $sql)) {
			echo "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	} else {
		echo "quorum berjumlah ".$quo.". Sehingga operasi tidak dapat dilakukan";
	}
});

$app->post('/requestTransfer', function () use ($conn){
	$quo = quorum();
	if ( $quo > 4) {
		global $env;
		$app = \Slim\Slim::getInstance();
		$json = json_decode($app->request->getBody());
		$cabang_tujuan = $json->cabang_tujuan;
		$user_id = $json->user_id;
		$nilai = $json->nilai;

		$response = getSaldoCurl($env, $user_id);
		if($response == -1){
			$object['status_transfer'] = -1;
			$object['message'] = 'user tidak ditemukan di cabang ilham.';
			echo json_encode($object);
		}else {
			$saldo = $response;
			if ($saldo < $nilai) {
				$object['status_transfer'] = -1;
				$object['message'] = 'saldo tidak cukup.';
				echo json_encode($object);
			}else {
	        // call transfer
				$object['user_id'] = $user_id;
				$object['nilai'] = $nilai;
				$ch = curl_init($cabang_tujuan."/ewallet/transfer");
				curl_setopt_array($ch, array(
					CURLOPT_POST => TRUE,
					CURLOPT_RETURNTRANSFER => TRUE,
					CURLOPT_HTTPHEADER => array('Content-Type:application/json; charset=utf-8'),
					CURLOPT_POSTFIELDS => json_encode($object)
					));
				$response = curl_exec($ch);
				$response = json_decode($response,true);
				if($response['status_transfer'] == 0){
					$saldo = getSaldoCurl($env, $user_id);
					$saldo = $saldo - $nilai;
					$sql = "UPDATE user SET saldo='$saldo' where user_id='$user_id'";
					$result = $conn->query($sql);
					if($result){
						$object['status_transfer'] = 0;
						$object['message'] = 'Transfer ke cabang tujuan BERHASIL';
						echo json_encode($object);
					}else{
						$object['status_transfer'] = -1;
						$object['message'] = 'Transfer ke cabang tujuan GAGAL';
						echo json_encode($object);
					}
				}
			}
		}
	} else {
		echo "quorum berjumlah ".$quo.". Sehingga operasi tidak dapat dilakukan";
	}
});

$app->post('/transfer', function () use ($conn) {
	global $env;
	$app = \Slim\Slim::getInstance();
	$json = json_decode($app->request->getBody());
	$user_id = $json->user_id;
	$nilai = $json->nilai;

	$response = getSaldoCurl($env, $user_id);
	if($response == -1){
		$object['status_transfer'] = -1;
		$object['message'] = 'user tidak ditemukan di cabang ilham.';
		echo json_encode($object);
	}else{
      // process transfer
		$saldo = $response + $nilai;
		$sql = "UPDATE user SET saldo='$saldo' where user_id='$user_id'";
		$result = $conn->query($sql);
		if($result){
			$object['status_transfer'] = 0;
			$object['message'] = 'Transfer ke cabang tujuan BERHASIL';
			echo json_encode($object);
		}else{
			$object['status_transfer'] = -1;
			$object['message'] = 'Transfer ke cabang tujuan GAGAL';
			echo json_encode($object);
		}
	}
});

function getCabangURL(){
	$cabang = array();
	array_push($cabang, "https://ilham.sisdis.ui.ac.id");
	array_push($cabang, "https://alhafis.sisdis.ui.ac.id");
	array_push($cabang, "https://kurniawan.sisdis.ui.ac.id");
	array_push($cabang, "https://radityo.sisdis.ui.ac.id");
	array_push($cabang, "https://putra.sisdis.ui.ac.id");
	array_push($cabang, "https://aditya.sisdis.ui.ac.id");
	array_push($cabang, "https://azhari.sisdis.ui.ac.id");
	array_push($cabang, "https://prakash.sisdis.ui.ac.id");
	array_push($cabang, "https://ratna.sisdis.ui.ac.id");
	return $cabang;
}

function getTotalSaldo($conn, $user_id){
	$app = \Slim\Slim::getInstance();
	$sql = "SELECT ip_domisili FROM user WHERE user_id = '".$user_id."'";
	$result = $conn->query($sql);
	$row = mysqli_fetch_row($result);
	// cek ip domisili
	if ($row[0] === 'ilham.sisdis.ui.ac.id') {
		$cabang = getCabangURL();
		$totalSaldo = 0;
		for ($i=0; $i < count($cabang); $i++) {
			$cabang_tujuan = $cabang[$i];
			$url = $cabang_tujuan."/ewallet/getSaldo?user_id=".$user_id;
			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_SSL_VERIFYHOST => 0,
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_URL => $url
			));
			$response = curl_exec($ch);
			$response = json_decode($response);
			if (!empty($response) && $response->nilai_saldo >= 0) {
				$totalSaldo += $response->nilai_saldo;
			}
		}
		return $totalSaldo."";
	} else {
		$totalSaldo = 0;
		$cabang_tujuan = $row[0];
		$url = $cabang_tujuan."/ewallet/getTotalSaldo?user_id=".$user_id;
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_URL => $url
		));
		$response = curl_exec($ch);
		$response = json_decode($response);
		if (!empty($response)) {
			$totalSaldo += $response->nilai_saldo;
		}
		return $totalSaldo."";
	}
}

function ping(){
	$app = \Slim\Slim::getInstance();
	$output["pong"] = 1;
	$app->response()->headers->set('Content-Type', 'application/json');
	echo json_encode($output);
}

function getSaldoCurl($tujuan, $user_id){
	global $env;
	$url = $env."ewallet/getSaldo?user_id=".$user_id;
	$ch = curl_init();
	curl_setopt_array($ch, array(
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_RETURNTRANSFER => TRUE,
		CURLOPT_URL => $url
		));
	$response = curl_exec($ch);
	$response = json_decode($response);
	return $response->nilai_saldo;
}

function getSaldo($conn, $user_id){
	$app = \Slim\Slim::getInstance();
	$sql = "SELECT saldo FROM user WHERE user_id = '".$user_id."'";
	$result = $conn->query($sql);
	$row = mysqli_fetch_row($result);
	if (count($row) > 0) {
		return $row[0];
	} else {
		return "-1";
	}
}

function quorum(){
	$app = \Slim\Slim::getInstance();
	$cabang = getCabangURL();
	$quorum = 0;
	for ($i=0; $i < count($cabang); $i++) {
		$cabang_tujuan = $cabang[$i];
		$url = $cabang_tujuan."/ewallet/ping";
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_URL => $url,
			CURLOPT_CONNECTTIMEOUT => 5,
			CURLOPT_TIMEOUT => 5
		));
		$response = curl_exec($ch);
		$response = json_decode($response);
		if (!empty($response)) {
			$quorum += $response->pong;
		}
	}
	return $quorum;
}

$app->run();

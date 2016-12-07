<!DOCTYPE html>
<html>
<head>
	<title>Tugas 1 Individu</title>
</head>
<body>
	<div style="margin-top:10px;"></div>
	<?php
		$url = "https://ilham.sisdis.ui.ac.id/ewallet/getSaldo?user_id=".$_GET['user_id'];
		header('Location: '.$url);
	?>
</body>
</html>

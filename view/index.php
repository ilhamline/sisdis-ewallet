<!DOCTYPE html>
<html>
<head>
	<title>Tugas 1 Individu</title>
</head>
<body>
	<div style="margin-top:10px;"></div>
	<h4>PING</h4>
	<form action="ping.php" method="post">
		<p>Cabang:</p>
		<select type="text" name="ip_domisili">
			<option value="https://ilham.sisdis.ui.ac.id">ilham.sisdis.ui.ac.id</option>
			<option value="https://alhafis.sisdis.ui.ac.id">alhafis.sisdis.ui.ac.id</option>
			<option value="https://kurniawan.sisdis.ui.ac.id">kurniawan.sisdis.ui.ac.id</option>
			<option value="https://radityo.sisdis.ui.ac.id">radityo.sisdis.ui.ac.id</option>
			<option value="https://putra.sisdis.ui.ac.id">putra.sisdis.ui.ac.id</option>
			<option value="https://aditya.sisdis.ui.ac.id">aditya.sisdis.ui.ac.id</option>
			<option value="https://azhari.sisdis.ui.ac.id">azhari.sisdis.ui.ac.id</option>
			<option value="https://prakash.sisdis.ui.ac.id">prakash.sisdis.ui.ac.id</option>
			<option value="https://ratna.sisdis.ui.ac.id">ratna.sisdis.ui.ac.id</option>
		</select>
		<p><input type="submit" /></p>
	</form>
	<h4>Register</h4>
	<form action="register.php" method="post">
		<p>Nama: <input type="text" name="name" /></p>
		<p>NPM: <input type="text" name="npm" /></p>
		<p>IP Domisili:</p>
		<select type="text" name="ip">
			<option value="https://ilham.sisdis.ui.ac.id">ilham.sisdis.ui.ac.id</option>
			<option value="https://alhafis.sisdis.ui.ac.id">alhafis.sisdis.ui.ac.id</option>
			<option value="https://kurniawan.sisdis.ui.ac.id">kurniawan.sisdis.ui.ac.id</option>
			<option value="https://radityo.sisdis.ui.ac.id">radityo.sisdis.ui.ac.id</option>
			<option value="https://putra.sisdis.ui.ac.id">putra.sisdis.ui.ac.id</option>
			<option value="https://aditya.sisdis.ui.ac.id">aditya.sisdis.ui.ac.id</option>
			<option value="https://azhari.sisdis.ui.ac.id">azhari.sisdis.ui.ac.id</option>
			<option value="https://prakash.sisdis.ui.ac.id">prakash.sisdis.ui.ac.id</option>
			<option value="https://ratna.sisdis.ui.ac.id">ratna.sisdis.ui.ac.id</option>
		</select>
		<p><input type="submit" /></p>
	</form>

	<h4>Transfer</h4>
	<form action="transfer.php" method="post">
		<p>NPM: <input type="text" name="user_id" /></p>
		<p>Nilai Transfer: <input type="text" name="nilai" /></p>
		<p>Cabang Tujuan:</p>
		<select type="text" name="cabang_tujuan">
			<option value="https://ilham.sisdis.ui.ac.id">ilham.sisdis.ui.ac.id</option>
			<option value="https://alhafis.sisdis.ui.ac.id">alhafis.sisdis.ui.ac.id</option>
			<option value="https://kurniawan.sisdis.ui.ac.id">kurniawan.sisdis.ui.ac.id</option>
			<option value="https://radityo.sisdis.ui.ac.id">radityo.sisdis.ui.ac.id</option>
			<option value="https://putra.sisdis.ui.ac.id">putra.sisdis.ui.ac.id</option>
			<option value="https://aditya.sisdis.ui.ac.id">aditya.sisdis.ui.ac.id</option>
			<option value="https://azhari.sisdis.ui.ac.id">azhari.sisdis.ui.ac.id</option>
			<option value="https://prakash.sisdis.ui.ac.id">prakash.sisdis.ui.ac.id</option>
			<option value="https://ratna.sisdis.ui.ac.id">ratna.sisdis.ui.ac.id</option>
		</select>
		<p><input type="submit" /></p>
	</form>

	<h4>Get Saldo</h4>
	<form action="getsaldo.php" method="get">
		<p>NPM: <input type="text" name="user_id" /></p>
		<p><input type="submit" /></p>
	</form>

	<h4>Get Total Saldo</h4>
	<form action="gettotalsaldo.php" method="get">
		<p>NPM: <input type="text" name="user_id" /></p>
		<p><input type="submit" /></p>
	</form>
</body>
</html>

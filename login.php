<?php
if (isset($_POST['email']) && isset($_POST['password'])) { //an exei ypovlithei i forma sto login.html
	$email = trim($_POST['email']," ");
	$pass = $_POST['password'];

	require_once('config.php');
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
		or die('Error connecting to MySQL server.');
	mysqli_query($dbc, "SET NAMES 'utf8'");
	
	$query = "SELECT * FROM pharmacists WHERE email = '$email' AND password = '$pass'";
	
	if ($data = mysqli_query($dbc, $query)) {
		if (mysqli_num_rows($data) > 0) {
			session_start();
			$_SESSION['authorized'] = 'yes';
			while($oneRow = mysqli_fetch_array($data)) {
				$_SESSION['afm'] = $oneRow['afm'];
			}
			header('location: catalog.php');
		} else {			
			header('location: unauthorized.html');
		}	
	}	
	
	mysqli_close($dbc);
	
} else { //an den exei ypovlithei i forma sto submit.html
	header('location: login.html');
}
	
?>

          
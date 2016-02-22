<?php
	session_start();
	if(!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes'){		
		header('location: login.html');
		exit();
				
	} else {
		if (isset($_GET['flag']) && $_GET['flag'] == "true") {
			
			require_once('empty.php');
			clearCart();	
			header('location: cart.php');
		}
		
	}

	
?>


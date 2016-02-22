<?php
	session_start();
	if(!isset($_SESSION['authorized']) || $_SESSION['authorized'] != 'yes'){		
		header('location: login.html');
		exit();				
	}	
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Αποθήκη Φαρμάκων "MEDICAL" - Φαρμακοτρίφτης & Σια </title>
        <link type="text/css" rel="stylesheet" href="stylesheet.css">
    </head>
    <body>
        <header>
            <img src="pharmacy_banner.jpg" width="900" height="230">
        </header>
        <div id="surroundingImage"">
            <div id="content">

                <h1>
                    MEDICAL
                </h1>
				<h2>
					Λεπτομέρειες Προϊόντος
				</h2>
                <form action = "<?php echo $_SERVER['PHP_SELF']; ?>" method = "get">
				<?php
					require_once('config.php');
					
					if (isset($_GET['product_code'])) {
						$product_code = $_GET['product_code'];
						
						$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
						or die('Error connecting to MySQL server.');
						mysqli_query($dbc, "SET NAMES 'utf8'");
					
						$query = "SELECT * FROM medications WHERE product_code='". $product_code ."'";
						$data = mysqli_query($dbc, $query);
						while($oneRow = mysqli_fetch_array($data)) {
							echo '<p>Κωδικός προϊόντος: <br><b>' . $oneRow['product_code'] . '</b></p>';
							echo '<p>Ονομασία προϊόντος: <br><b>' . $oneRow['product_name'] .'</b></p>';
							echo '<p>Τύπος συσκευασίας: <br><b>' . $oneRow['packet_type'] .'</b></p>';
							echo '<p>Οδηγίες χρήσης: <br><b>' . $oneRow['instructions'] . '</b></p>';
							echo '<p>Τιμή προϊόντος: <br><b>' . $oneRow['price'] .'&euro;</b></p>';
							echo '<p>Ποσοστό ΦΠΑ: <br><b>' . ($oneRow['fpa_factor'] * 100) .'%</b></p>';
							
							echo '<select name="quantity'. $oneRow['product_code'] .'"><option value="0">0</option><option value="1">1</option>'
						. '<option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option>'
						.'</select> &nbsp;&nbsp;<input type="submit" value="Καλάθι" name="' . $oneRow['product_code'] . '"><input type="hidden"  name="cart" value="pressed">'
						.'<input type="hidden"  name="product_code" value="' . $oneRow['product_code'] . '">';						
						}	

						mysqli_close($dbc);						
										
					}						
				?>	
				</form>
				<br>
				<br>
					<p><a class = "modified" href="catalog.php"><- Κατάλογος</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class = "modified" href="cart.php">Προβολή Καλαθιού</a></p>
					<br><p><a class = "modified" href="logout.php">Έξοδος Χρήστη</a></p>
				<br>
			</div>	
			
			<?php
				if (isset($_GET['cart'])) {			
			?>
				<div id="content">                
					<h2>
						Καλάθι Αγορών
					</h2>
			<?php
					
					foreach(array_keys($_GET) as $key ) {						
						if (strpos($key, 'ph') === 0) {		
							/* echo $key . ' => ' .$_GET['quantity'.$key];						 */
							if(!isset($_SESSION[$key]) || $_SESSION[$key] <=0 ) {
								if (isset($_GET['quantity'.$key])) {
									if (is_numeric($_GET['quantity'.$key]) && $_GET['quantity'.$key] <=5 && $_GET['quantity'.$key] >=0 ) {
										$_SESSION[$key] = $_GET['quantity'.$key];									
									} else {
										$_SESSION[$key] = 1;
									}
									echo '<p>'. $_SESSION[$key] .' τεμάχια προστέθηκαν στο καλάθι.</p>';		
								} else {
									echo '<p>Απέτυχε η προσθήκη. Βρέθηκε σφάλμα στα στοιχεία του προϊόντος!</p>';
								}
														
							} else {
								echo '<p>Απέτυχε η προσθήκη. Το προϊόν '. $key .' υπήρχε ήδη στο καλάθι.</p>';
							}							
						}						
					}	
					
					$totalItems = 0;
					
					foreach(array_keys($_SESSION) as $itemKey) {
						if(strpos($itemKey, 'ph') === 0) {
							$totalItems = $totalItems + $_SESSION[$itemKey];
						}
					}
					echo '<p><b>Το καλάθι περιέχει συνολικά '. $totalItems  .' τεμάχια.</b></p>';
			?>		
				
			</div>
			
		<?php
				}
		?>
			
		</div>
    </body>
</html>
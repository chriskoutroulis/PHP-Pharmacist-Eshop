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
        <div id="surroundingImageTable">
            <div id="content">

                <h1>
                    MEDICAL
                </h1>
				<h3>
					Κατάλογος Προιόντων
				</h3>
                
	<?php	
					require_once('config.php');
					
					if (!is_numeric(GROUP) || GROUP <= 0) {
						echo '<p>Η τιμή της σταθεράς GROUP στο αρχείο config.php δεν είναι έγκυρη. Χρησιμοποιήστε μια θετική αριθμητική τιμή.</p>';
						exit();
					}
					
					if (isset($_GET['thisPage'])) {
						if(is_numeric($_GET['thisPage']) && $_GET['thisPage'] > 0) {
							$thisPage = $_GET['thisPage'];
						} else {
							$thisPage = 1;
						}								
					} else {
						$thisPage = 1;
					}
					
					$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
					or die('Error connecting to MySQL server.');
					mysqli_query($dbc, "SET NAMES 'utf8'");
					
					$query = "SELECT * FROM medications";
					$data = mysqli_query($dbc, $query);
					$allProducts = mysqli_num_rows($data);					
					$pages = ceil($allProducts / GROUP);
					
					$query = "SELECT * FROM medications ORDER BY price LIMIT " . (($thisPage - 1) * GROUP) . ", " . GROUP;
					$data = mysqli_query($dbc, $query);
	?>
				<form action = "<?php echo $_SERVER['PHP_SELF'] . "?thisPage=$thisPage"; ?>" method = "post">
					<table id="resultTable">                        
                        <tr>
                            <th class="resultData">
                                Κωδικός Προϊόντος
                            </th>
                            <th class="resultData">
                                Ονομασία Προϊόντος
                            </th>
							<th class="resultData">
                                Συσκευασία
                            </th>
							<th class="resultData">
                                Τιμή (-ΦΠΑ)
                            </th>
							<th class="resultData">
                                Αγορά
                            </th>
                        </tr>
	
	<?php					
					while($oneRow = mysqli_fetch_array($data)) {
						echo '<tr class="resultRow"><td class="resultData"><a href="product.php?product_code='. $oneRow['product_code'] .'">' . $oneRow['product_code'] . '</a></td>'
						. '<td class="resultData"><a href="product.php?product_code='. $oneRow['product_code'] .'">' . $oneRow['product_name'] . '</a></td><td class="resultData">'.  $oneRow['packet_type']  
						. '</td><td class="resultData">'. $oneRow['price'] . '</td><td class="resultData"><select name="quantity'. $oneRow['product_code'] .'"><option value="0">0</option><option value="1">1</option>'
						. '<option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option>'
						.'</select> &nbsp;&nbsp;<input type="submit" value="Καλάθι" name="' . $oneRow['product_code'] . '"><input type="hidden"  name="cart" value="pressed"></td></tr>';						
					}
					
					mysqli_close($dbc);
	?>
					</table>
					
					<br>
					<p>
					<?php
						for ($i = 1; $i<=$pages ; $i++) {
							if($thisPage == $i) {
								echo '<a class="selected" href="catalog.php?thisPage=' . $i  . '">'. $i .'</a> &nbsp;&nbsp;';
							} else {
								echo '<a class = "modified" href="catalog.php?thisPage=' . $i  . '">'. $i .'</a> &nbsp;&nbsp;';
							}
							
						}
						echo "<p>Σελίδα " . $thisPage . "/" . $pages ."</p>";
					?>
					</p>
					
					<br>
					<p><a class = "modified" href="cart.php">Προβολή Καλαθιού</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class = "modified" href="logout.php">Έξοδος Χρήστη</a></p>
				</form>
				<br>
			</div>
			
	<?php
		if (isset($_POST['cart'])) {			
	?>
			<div id="content">                
				<h2>
					Καλάθι Αγορών
				</h2>
				<?php	
					
					foreach(array_keys($_POST) as $key ) {						
						if (strpos($key, 'ph') === 0) {		
							/* echo $key . ' => ' .$_POST['quantity'.$key];						 */
							if(!isset($_SESSION[$key]) || $_SESSION[$key] <=0) {
								if (isset($_POST['quantity'.$key])) {
									if (is_numeric($_POST['quantity'.$key]) && $_POST['quantity'.$key] <=5 && $_POST['quantity'.$key] >=0 ) {
										$_SESSION[$key] = $_POST['quantity'.$key];									
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
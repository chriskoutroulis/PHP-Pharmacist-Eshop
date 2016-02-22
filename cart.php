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
				<h2>
					Καλάθι Αγορών
				</h2>
				<form action = "<?php echo $_SERVER['PHP_SELF']; ?>" method = "post">
					<table id="resultTable">                        
                        <tr>
							<th class="resultData">
								Ποσότητα
							</th>
                            <th class="resultData">
                                Κωδικός Προϊόντος
                            </th>
                            <th class="resultData">
                                Ονομασία Προϊόντος
                            </th>							
							<th class="resultData">
                                Τιμή
                            </th>
							<th class="resultData">
                                ΦΠΑ
                            </th>
							<th class="resultData">
                                Αλλαγή Ποσότητας
                            </th>
                        </tr>
				
		<?php
		
		
					/* print_r($_SESSION); */
					
					
		
					if(isset($_POST['change'])) {
						
						unset($_SESSION['cart_total']);
						
						$i = 1;
						foreach(array_keys($_SESSION) as $key ) {						
							if (strpos($key, 'cart_entry' . $i) === 0) {					
								unset($_SESSION[$key]);
								$i++;	
							}						
						}
						
						foreach(array_keys($_POST) as $key ) {						
							if (strpos($key, 'ph') === 0) {		
								/* echo $key . ' => ' .$_POST['quantity'.$key];						 */
								if(isset($_SESSION[$key])) {
									if (isset($_POST['quantity'.$key])) {
										if (is_numeric($_POST['quantity'.$key]) && $_POST['quantity'.$key] <=5 && $_POST['quantity'.$key] > 0) {										
											$_SESSION[$key] = $_POST['quantity'.$key];		
										} else {
											unset($_SESSION[$key]);
										}
									} else {										
										unset($_SESSION[$key]);
									}							
								} 	
							}						
						}					
					}
					
					
					
					/* print_r($_SESSION); */
		
					require_once('config.php');
					
					$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
					or die('Error connecting to MySQL server.');
					mysqli_query($dbc, "SET NAMES 'utf8'");
					
					$total_with_fpa = 0;
					$i = 1;
					$cart_entry = array();
					$cart_total = 0;
					
					foreach(array_keys($_SESSION) as $key ) {						
						if (strpos($key, 'ph') === 0) {						
							$quantity = $_SESSION[$key];
							if ($quantity > 0) {
								$query = "SELECT * FROM medications WHERE product_code='". $key ."'";
								$data = mysqli_query($dbc, $query);
								while($oneRow = mysqli_fetch_array($data)) {
								
									echo '<tr class="resultRow"><td class="resultData">'. $quantity . '</td><td class="resultData">'. $oneRow['product_code'] . '</td>'
									.'<td class="resultData">' . $oneRow['product_name'] . '</td>'
									.'<td class="resultData">'. $oneRow['price'] . '</td><td class="resultData">' . ($oneRow['fpa_factor'] * 100) .'%</td>'
									.'<td class="resultData"><select name="quantity'. $oneRow['product_code'] .'"><option value="0">0</option><option value="1">1</option>'
									. '<option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option>'
									.'</select> &nbsp;&nbsp;<input type="submit" value="Αλλαγή" name="' . $oneRow['product_code'] . '">'
									.'<input type="hidden"  name="change" value="pressed"></td></tr>';	
								
									$price_with_fpa = $quantity * ($oneRow['price'] + ($oneRow['price'] * $oneRow['fpa_factor']));
									/* echo $price_with_fpa . '<br>'; */
									$total_with_fpa = $total_with_fpa + $price_with_fpa;	
									
									$cart_entry ['quantity'] = $quantity;
									$cart_entry ['product_code'] = $oneRow['product_code'];
									$cart_entry ['product_name'] = $oneRow['product_name'];
									$cart_entry ['price'] = $oneRow['price'];
									$cart_entry ['fpa_factor'] = $oneRow['fpa_factor'];
									
									
									
									$_SESSION['cart_entry'.$i] = $cart_entry;
									$i++;
								}					
							} 
						}						
					}

		
				mysqli_close($dbc);
		?>			
				
						
				</table>	
			</form>
		<?php
			$cart_total = $total_with_fpa;
			$_SESSION['cart_total'] = $cart_total;
			/* print_r($_SESSION); */
			echo '<p id="total"><b>Σύνολο: '. number_format((float)$total_with_fpa, 2, '.', '') . '&euro;</b></p>';
		?>
		
			<br>
			<p><a class = "modified" href="catalog.php"><- Κατάλογος</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp <a class = "modified" href="buy.php">Ταμείο</a></p>
			<br><p><a class = "modified" href="clearcart.php?flag=true">Διαγραφή όλων</a></p>
			<br><p><a class = "modified" href="logout.php">Έξοδος Χρήστη</a></p>
			<br>
		
		</div>
       </div>
    </body>
</html>
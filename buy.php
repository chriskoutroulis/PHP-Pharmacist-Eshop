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
					Αγορά προϊόντων
				</h2>
				<?php
					$i =1;
					$text = "";
					$found = false;
				
					foreach(array_keys($_SESSION) as $key ) {						
						if (strpos($key, 'cart_entry' . $i) === 0) {
							
							$found = true;
							
							$cart_entry = $_SESSION[$key];
							$text .= $cart_entry ['quantity'] . "   " . $cart_entry ['product_code']
							. "\t\t" . $cart_entry ['product_name'] ;
							if (strlen($cart_entry ['product_name']) < 8) {
								$text .=  "\t\t" . $cart_entry ['price'];
							} else {
								$text .=  "\t" . $cart_entry ['price'];
							}
							$text .= "€\t" . ($cart_entry ['fpa_factor'] * 100) . "%\r\n";
							
							$i++;
						}						
					}				
					if ($found) {
						$text .= "\r\n\tΤο σύνολο της παραγγελίας με ΦΠΑ είναι: " . number_format((float)$_SESSION['cart_total'], 2, '.', '') . "€\r\n\r\n \t -------------------------------- \r\n\r\n";
						date_default_timezone_set("Europe/Athens"); 
						$path = __DIR__ . "/orders/";
						$filename = $_SESSION['afm'] . "_" .date("Y-m-d_H-i-s", time()) . ".txt";
						$file = fopen($path . $filename, "a") or exit("An error occurred while creating the agora.txt file!");	
						fwrite($file, $text);					
						fclose($file);
						
						require_once('empty.php');

						clearCart();	
						
				?>
						
					<p>Η παραγγελία καταχωρήθηκε με επιτυχία.</p>
					<p>Ευχαριστούμε για την προτίμηση σας!</p>
					<br>
					<p><a download href = " <?php echo substr($_SERVER['REQUEST_URI'] , 0, -8) . "/orders/" . $filename; ?>">Περίληψη παραγγελίας</a></p>
					<br>
					<p><a class = "modified" href="catalog.php"><- Κατάλογος</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp <a class = "modified" href="logout.php">Έξοδος Χρήστη</a></p>
					<br>
					

				<?php	
				
					} else {
				?>
				
					<p>Το καλάθι αγορών είναι άδειο!</p>
					<p>Δεν πραγματοποιήθηκε παραγγελία.</p>
					<br>
					<br>
					<p><a class = "modified" href="catalog.php"><- Κατάλογος</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp <a class = "modified" href="logout.php">Έξοδος Χρήστη</a></p>
					<br>
				
				
				<?php		
					}
				?>
					
					
					
					
				
				
		
		</div>
       </div>
    </body>
</html>
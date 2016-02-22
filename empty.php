<?php

	function clearCart() {
		$i = 1;
		foreach(array_keys($_SESSION) as $key ) {						
			if (strpos($key, 'cart_entry' . $i) === 0) {					
				unset($_SESSION[$key]);
				$i++;	
			}						
		}	
							
		foreach(array_keys($_SESSION) as $key ) {						
			if (strpos($key, 'ph') === 0) {
										
				unset($_SESSION[$key]); 
			}						
		}
		
		if (isset($_SESSION['cart_total']))	{
			unset($_SESSION['cart_total']);
		}				
								
							
	}	
?>
<?php
	session_start();

	include "itemdata.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Ever Grande City Checkout</title>
	<link rel="stylesheet" href="/assets/css/shop.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
	<?php include "header.php";?>
	<h2>Checkout<hr></h2>
	<div class="center" style='width: 30%'>
		<?php
			if (!isset($_SESSION['cart'])){
				echo "<div class='item' style='text-align:center'>Your cart is empty!</div>";
				$cartEmpty = True;
			} else {
				if(empty($_SESSION['cart'])) {
					echo "<div class='item' style='text-align:center'>Your cart is empty!</div>";
					$cartEmpty = True;
				} else {
					$total = 0;
					foreach($_SESSION['cart'] as $item => $quantity ) {
						$total += $itemPrices[$item] * $quantity;
					}
					echo "<div class='item' style='flex-direction: row'><div class='title'>Total:</div><div class='add'>$";
					printf("%.2f", $total);
					echo "</div></div>";
				}
			}
		?>
		<form action="submit.php" method="post">
			<div class='item'>
				<div>
				Name:<input class="checkoutInput" type="text" name="name"><br>
				Street Address:<input class="checkoutInput" type="text" name="street"><br>
				City:<input class="checkoutInput" type="text" name="city"><br>
				State:<input class="checkoutInput" type="text" name="state"><br>
				ZIP Code:<input class="checkoutInput" type="text" name="zip"><br>
			</div>
			</div>
			<div class='item'><input type='submit' class='add' value="Complete Order"></div>
		</form>
	</div>
	<?php include "../footer.php";?>
</body>
</html>
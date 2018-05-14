<?php
	session_start();

	include "itemdata.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Ever Grande City Cart</title>
	<link rel="stylesheet" href="/assets/css/shop.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="/assets/script/shop.js"></script>
</head>
<body>
	<?php include "header.php";?>
	<h2>Cart<hr></h2>
	<div class="center" style='width: 30%'>
		<?php
			if (!isset($_SESSION['cart'])){
				echo "<div class='item' style='text-align:center'>Your cart is empty!</div>";
			} else {
				if(empty($_SESSION['cart'])) {
					echo "<div class='item' style='text-align:center'>Your cart is empty!</div>";
				} else {
					$total = 0;
					foreach($_SESSION['cart'] as $item => $quantity ) {
						echo "<div class='item'>";
						echo "<span class='title'>".$itemNames[$item]."</span>";
						echo "<img src='".$spritepath.$itemSprites[$item].".png'>";
						echo "<div><div><button class='remove' onclick='removeFromCart(".$item.")''>Remove</button></div><div class='add'>".$quantity." @ $";
						printf("%.2f",$itemPrices[$item]);
						echo "</div></div>";
						echo "</div>";
						$total += $itemPrices[$item] * $quantity;
					}
					echo "<div class='checkoutRow'><div class='title'>Total:</div><div class='add'>$";
					printf("%.2f", $total);
					echo "</div></div>";
					echo "<form class='item' action='checkout.php'><input class='add' type='submit' value='Checkout'></form>";
				}
			}
		?>
	</div>
	<?php include "../footer.php";?>
</body>
</html>
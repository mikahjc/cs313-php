<?php
	session_start();

	include "itemdata.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Ever Grande City Order Confirmation</title>
	<link rel="stylesheet" href="/assets/css/shop.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
	<?php include "header.php";?>
	<h2>Invoice<hr></h2>
	<div class="center" style='width: 30%'>
		<div class="item">
			<div class="title">Ship to:</div>
			<?php
				echo htmlentities($_POST['name'])."<br>";
				echo htmlentities($_POST['street'])."<br>";
				echo htmlentities($_POST['city']).", ".htmlentities($_POST['state'])."  ".htmlentities($_POST['zip']);
			?>
		</div>
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
						echo "<img src='".$spritepath.$itemSprites[$item]."'>";
						echo "<div><div></div><div class='add'>".$quantity." @ $";
						printf("%.2f",$itemPrices[$item]);
						echo "</div></div>";
						echo "</div>";
						$total += $itemPrices[$item] * $quantity;
					}
					echo "<div class='checkoutRow'><div class='title'>Total:</div><div class='add'>$";
					printf("%.2f", $total);
					echo "</div></div>";
					session_destroy();
					session_unset();
				}
			}
		?>
	</div>
	<?php include "../footer.php";?>
</body>
</html>
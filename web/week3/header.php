<?php
	if (session_status() != 2) {
		session_start();
	}

	$totalItems = 0;

	if (isset($_SESSION['cart'])) {
		foreach ($_SESSION['cart'] as $quantity) {
			$totalItems += $quantity;
		}
	}
?>
<header>
	<a class="logo" href="/week3/">Poké Mart</a>
	<div class="headerRight">
		<a id="cart" href="cart.php">Cart <?php echo "(".$totalItems.")";?></a>
	</div>
</header>
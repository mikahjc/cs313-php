<?php session_start();

function adjustItem($itemIndex) {
	echo "called adjustItem";
	if (isset($_POST['type'])) {
		if(!isset($_SESSION['cart'][$itemIndex])) {
			$_SESSION['cart'][$itemIndex] = 0;
		}
		if ($_POST['type'] == "add") {
			$_SESSION['cart'][$itemIndex] += 1;
			echo "Item added to cart!";
		} elseif ($_POST['type'] == "remove") {
			$_SESSION['cart'][$itemIndex] -= 1;
			if ($_SESSION['cart'][$itemIndex] == 0) {
				unset($_SESSION['cart'][$itemIndex]);
			}
			echo "Item removed from cart.";
		}
	}
}

if (!isset($_SESSION['cart'])) {
	$_SESSION['cart'] = [];
}

if (isset($_POST['item'])) {
	echo "item set in post";
	$item = $_POST['item'];
	adjustItem($item);
}

if (isset($_GET['clear'])) {
	session_destroy();
	header('Location: /week3/');
	exit();
}

?>
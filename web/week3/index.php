<?php session_start() ?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="/assets/css/shop.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="/assets/script/shop.js"></script>
	<title>Ever Grande City Poké Mart</title>
</head>
<body>
	<?php include "header.php";?>
	<h2>Items<hr></h2>
	<div class="center shop">
		<div class="item">
			<div class="title">Pokéball</div>
			<img src="/assets/img/sprites/items/pokeball.png">
			<div>
				<div class="price">$1.85</div>
				<button class="add" onclick="addToCart(1)">Add to Cart</button>
			</div>
		</div>
		<div class="item">
			<div class="title">Great Ball</div>
			<img src="/assets/img/sprites/items/greatball.png">
			<div>
				<div class="price">$5.50</div>
				<button class="add" onclick="addToCart(2)">Add to Cart</button>
			</div>
		</div>
		<div class="item">
			<div class="title">Ultra Ball</div>
			<img src="/assets/img/sprites/items/ultraball.png">
			<div>
				<div class="price">$11.00</div>
				<button class="add" onclick="addToCart(3)">Add to Cart</button>
			</div>
		</div>
		<div class="item">
			<div class="title">Potion</div>
			<img src="/assets/img/sprites/items/potion.png">
			<div>
				<div class="price">$2.75</div>
				<button class="add" onclick="addToCart(4)">Add to Cart</button>
			</div>
		</div>
		<div class="item">
			<div class="title">Super Potion</div>
			<img src="/assets/img/sprites/items/superpotion.png">
			<div>
				<div class="price">$6.50</div>
				<button class="add" onclick="addToCart(5)">Add to Cart</button>
			</div>
		</div>
		<div class="item">
			<div class="title">Hyper Potion</div>
			<img src="/assets/img/sprites/items/hyperpotion.png">
			<div>
				<div class="price">$11.00</div>
				<button class="add" onclick="addToCart(6)">Add to Cart</button>
			</div>
		</div>
		<div class="item">
			<div class="title">Max Potion</div>
			<img src="/assets/img/sprites/items/maxpotion.png">
			<div>
				<div class="price">$22.85</div>
				<button class="add" onclick="addToCart(7)">Add to Cart</button>
			</div>
		</div>
		<div class="item">
			<div class="title">Full Restore</div>
			<img src="/assets/img/sprites/items/fullrestore.png">
			<div>
				<div class="price">$27.40</div>
				<button class="add" onclick="addToCart(8)">Add to Cart</button>
			</div>
		</div>
		<div class="item">
			<div class="title">Revive</div>
			<img src="/assets/img/sprites/items/revive.png">
			<div>
				<div class="price">$13.75</div>
				<button class="add" onclick="addToCart(9)">Add to Cart</button>
			</div>
		</div>
		<div class="item">
			<div class="title">Antidote</div>
			<img src="/assets/img/sprites/items/antidote.png">
			<div>
				<div class="price">$1.00</div>
				<button class="add" onclick="addToCart(10)">Add to Cart</button>
			</div>
		</div>
		<div class="item">
			<div class="title">Paralyze Heal</div>
			<img src="/assets/img/sprites/items/paralyzeheal.png">
			<div>
				<div class="price">$1.85</div>
				<button class="add" onclick="addToCart(11)">Add to Cart</button>
			</div>
		</div>
		<div class="item">
			<div class="title">Awakening</div>
			<img src="/assets/img/sprites/items/awakening.png">
			<div>
				<div class="price">$2.25</div>
				<button class="add" onclick="addToCart(12)">Add to Cart</button>
			</div>
		</div>
		<div class="item">
			<div class="title">Burn Heal</div>
			<img src="/assets/img/sprites/items/burnheal.png">
			<div>
				<div class="price">$2.25</div>
				<button class="add" onclick="addToCart(13)">Add to Cart</button>
			</div>
		</div>
		<div class="item">
			<div class="title">Ice Heal</div>
			<img src="/assets/img/sprites/items/iceheal.png">
			<div>
				<div class="price">$2.25</div>
				<button class="add" onclick="addToCart(14)">Add to Cart</button>
			</div>
		</div>
		<div class="item">
			<div class="title">Full Heal</div>
			<img src="/assets/img/sprites/items/fullheal.png">
			<div>
				<div class="price">$5.50</div>
				<button class="add" onclick="addToCart(15)">Add to Cart</button>
			</div>
		</div>
		<div class="item">
			<div class="title">Escape Rope</div>
			<img src="/assets/img/sprites/items/escaperope.png">
			<div>
				<div class="price">$5.00</div>
				<button class="add" onclick="addToCart(16)">Add to Cart</button>
			</div>
		</div>
		<div class="item">
			<div class="title">Repel</div>
			<img src="/assets/img/sprites/items/repel.png">
			<div>
				<div class="price">$3.25</div>
				<button class="add" onclick="addToCart(17)">Add to Cart</button>
			</div>
		</div>
		<div class="item">
			<div class="title">Super Repel</div>
			<img src="/assets/img/sprites/items/superrepel.png">
			<div>
				<div class="price">$4.60</div>
				<button class="add" onclick="addToCart(18)">Add to Cart</button>
			</div>
		</div>
		<div class="item">
			<div class="title">Max Repel</div>
			<img src="/assets/img/sprites/items/maxrepel.png">
			<div>
				<div class="price">$6.40</div>
				<button class="add" onclick="addToCart(19)">Add to Cart</button>
			</div>
		</div>
	</div>
	<?php include "../footer.php";?>
</body>
</html>
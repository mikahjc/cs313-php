function addToCart(item) {
	var posting = $.post("add.php", { item: item, type: "add" });
	posting.done(function( data ) { console.log(data);});
	var cartLink = document.getElementById('cart');
	var cartText = cartLink.innerHTML;
	var startPos = cartText.indexOf('(') + 1;
	var endPos = cartText.indexOf(')');
	var cartContents = cartText.substring(startPos, endPos);
	cartContents++;
	cartLink.innerHTML = "Cart (" + cartContents + ")";
}

function removeFromCart(item) {
	var posting = $.post("add.php", { item: item, type: "remove" });
	posting.done(function( data ) { console.log(data); location.reload();});
}
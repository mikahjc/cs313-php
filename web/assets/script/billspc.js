function releasePokemon(name, id) {
	if(confirm("Are you sure you want to release " + name + "?")) {
		document.getElementById("releaseValue").value = id;
		document.getElementById("releaseForm").submit();
	}
}

function editPokemon(id) {
	document.getElementById("editValue").value = id;
	document.getElementById("editForm").submit();
}
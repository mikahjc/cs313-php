<footer>
	<p>
	<?php if (isset($GBC_NOTICE)) {
		echo "<a href='https://decatilde.deviantart.com/art/Game-Boy-Color-skins-for-VisualBoyAdvance-303673863'>Gameboy Color skin by decatilde</a></p><p>Game Boy, Game Boy Color, ";
	}
	if (strpos($_SERVER['REQUEST_URI'], 'teambuilder') !== false) {
		echo "Pokemon sprites were retrieved from the <a href='https://www.smogon.com/dex/sm/pokemon/'>Smogon Sun/Moon Strategy Pokedex</a>.</p>
		<p>Data from the Pokemon games (moves, items, abilities, Pokemon) were retrieved from <a href='https://github.com/Zarel/Pokemon-Showdown'/>Pokemon Showdown</a> source code.</p><p>";
	}
	?>
    Pokemon and all respective names are Trademark & &copy; of Nintendo 1989-<?=date("Y")?>.</p>
</footer>
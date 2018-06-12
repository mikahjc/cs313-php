function deleteTeam() {
  var teamSelect = document.forms["teamSelect"]["team"];
  var teamName = teamSelect[teamSelect.selectedIndex].text;
  if (confirm("Are you sure you want to delete " + teamName + "?\nYour Pokemon will not be released.")) {
    document.forms["teamSelect"].action = "deleteTeam.php";
    document.forms["teamSelect"].submit();
  }
}

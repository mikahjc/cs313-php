var lastMove = {};

function updateMove(slot) {
  if (slot > 4) {
    return;
  }
  var value = document.getElementById("move" + slot).value;
  if (value != "") {
    for (var i = 1; i < 5; i++) {
      if (i == slot) continue;
      if (value == document.getElementById("move" + i).value) {
        alert(document.getElementById("name").innerHTML + " already knows that move.");
        document.getElementById("move" + slot).value = lastMove[slot];
        return;
      }
    }
    document.getElementById("m" + slot + "name").innerHTML = moves[value].name;
    document.getElementById("m" + slot + "type").innerHTML = moves[value].type;
    document.getElementById("m" + slot + "category").innerHTML = moves[value].category;
    document.getElementById("m" + slot + "power").innerHTML = moves[value].power;
    document.getElementById("m" + slot + "pp").innerHTML = moves[value].pp;
    document.getElementById("m" + slot + "accuracy").innerHTML = moves[value].accuracy;
    document.getElementById("m" + slot + "desc").innerHTML = moves[value].desc;
  } else {
    if (slot < 4 && document.getElementById("move" + (slot + 1)).value != "") {
      document.getElementById("move" + slot).value = document.getElementById("move" + (slot + 1)).value;
      var value = document.getElementById("move" + slot).value;
      document.getElementById("move" + (slot + 1)).value = "";
      document.getElementById("m" + slot + "name").innerHTML = moves[value].name;
      document.getElementById("m" + slot + "type").innerHTML = moves[value].type;
      document.getElementById("m" + slot + "category").innerHTML = moves[value].category;
      document.getElementById("m" + slot + "power").innerHTML = moves[value].power;
      document.getElementById("m" + slot + "pp").innerHTML = moves[value].pp;
      document.getElementById("m" + slot + "accuracy").innerHTML = moves[value].accuracy;
      document.getElementById("m" + slot + "desc").innerHTML = moves[value].desc;
      updateMove(slot + 1);
    } else {
    document.getElementById("m" + slot + "name").innerHTML = '\xa0';
    document.getElementById("m" + slot + "type").innerHTML = '\xa0';
    document.getElementById("m" + slot + "category").innerHTML = '\xa0';
    document.getElementById("m" + slot + "power").innerHTML = '\xa0';
    document.getElementById("m" + slot + "pp").innerHTML = '\xa0';
    document.getElementById("m" + slot + "accuracy").innerHTML = '\xa0';
    document.getElementById("m" + slot + "desc").innerHTML = '\xa0';
    }
  }
  lockMoveSlots();
}

function updateAbility() {
  var value = document.getElementById("ability").value;
  document.getElementById("abilityname").innerHTML = abilities[value].name;
  document.getElementById("abilitydesc").innerHTML = abilities[value].desc;
}
function updateItem() {
  var value = document.getElementById("item").value;
  document.getElementById("itemname").innerHTML = items[value].name;
  document.getElementById("itemdesc").innerHTML = items[value].desc;
}

function lockMoveSlots() {
  move1 = document.getElementById("move1");
  move2 = document.getElementById("move2");
  move3 = document.getElementById("move3");
  move4 = document.getElementById("move4");

  if (move1.value == "") {
    move2.disabled = true;
  } else {
    lastMove["1"] = move1.value;
    move2.disabled = false;
  }
  if (move2.value == "") {
    move3.disabled = true;
  } else {
    lastMove["2"] = move2.value;
    move3.disabled = false;
  }
  if (move3.value == "") {
    move4.disabled = true;
  } else {
    lastMove["3"] = move3.value;
    move4.disabled = false;
  }
  if (move4.value != "") {
    lastMove["4"] = move4.value;
  }
}

function validateForm() {
  var evTotal = 0;
  evTotal += parseInt(document.forms["pokemon"]["hp_ev"].value);
  evTotal += parseInt(document.forms["pokemon"]["atk_ev"].value);
  evTotal += parseInt(document.forms["pokemon"]["def_ev"].value);
  evTotal += parseInt(document.forms["pokemon"]["spa_ev"].value);
  evTotal += parseInt(document.forms["pokemon"]["spd_ev"].value);
  evTotal += parseInt(document.forms["pokemon"]["spe_ev"].value);
  var errorStr = "";
  var errors = false;
  if (evTotal > 510) {
    errorStr += "EVs must not exceed a total of 510!\n";
    errors = true;
  }
  if (document.forms["pokemon"]["move1"].value == "") {
    errorStr += "You must select at least one move!\n";
    errors = true;
  }
  if (document.forms["pokemon"]["ability"].value == "") {
    errorStr += "You must select an ability!";
    errors = true;
  }
  if (document.forms["pokemon"]["move1"].value == "") {
    errorStr += "You must select at least one move!";
    errors = true;
  }
  if (errors) {
    alert("Please correct the following issues:\n" + errorStr);
    errors = true;
  }
  return !errors;
}

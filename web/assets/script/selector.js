var menuStructure = {};
menuStructure.currentMenuElement = 0;
menuStructure.onScreenItems = 0;
menuStructure.offset = 0;

function initializeGBMenu(menuSize) {
  menuStructure.onScreenItems = menuSize;
  menuStructure.length = $("#menuContainer > p").length;
  var index = 0;
  $("#menuContainer > p").each( function() {
    if (this.firstChild.tagName == 'A') {
      menuStructure[index] = this.firstChild.id;
      index++;
    }
  });
  $("#menuContainer").scrollTop(0);
  //$("#a_button").on("click", clickSelectedLink());
  $("#up_button").on("click", function(e) {
    e.preventDefault();
    menuUp();
  });
  $("#down_button").on("click", function(e) {
    e.preventDefault();
    menuDown();
  });
  $(window).keypress( function(e) {
    if(e.keyCode == 38 || e.keyCode == 75) {
      e.preventDefault();
      menuUp();
    } else if (e.keyCode == 40 || e.keyCode == 74) {
      e.preventDefault();
      menuDown();
    } else if (e.keyCode == 13 || e.keyCode == 90) {
      e.preventDefault();
      clickSelectedLink();
    }
  });
}

function clickSelectedLink() {
  with(menuStructure) {
    $('#' + menuStructure[currentMenuElement])[0].click();
  }
}

function menuMouseOver(elementNumber) {
  with(menuStructure) {
    currentMenuElement = elementNumber;
    moveSelector(currentMenuElement - offset);
  }
}

function menuDown() {
  with(menuStructure) {
    if (currentMenuElement < length - 1) {
      var onScreenPosition = currentMenuElement - offset;
      if (onScreenPosition < menuStructure.onScreenItems - 1) {
        moveSelector((++currentMenuElement) - offset);
      } else {
        $("#menuContainer").scrollTop($("#menuContainer").scrollTop() + 32);
        currentMenuElement++;
        offset++;
      }
    } else {
      console.log("at bottom of menu");
    }
  }
}

function menuUp() {
  with(menuStructure) {
    if (currentMenuElement > 0) {
      var onScreenPosition = currentMenuElement - offset;
      if (onScreenPosition > 0) {
        moveSelector((--currentMenuElement) - offset);
      } else {
        $("#menuContainer").scrollTop($("#menuContainer").scrollTop() - 32);
        currentMenuElement--;
        offset--;
      }
    } else {
      console.log("at bottom of menu");
    }
  }
}

function moveSelector(y) {
  var selector = document.getElementById("selector");
  selector.style.top = 110 + (y * 32) + "px";
}

function showText(target, message, index, interval) {   
  if (index < message.length) {
    $(target).append(message[index++]);
    setTimeout(function () { showText(target, message, index, interval); }, interval);
  }
}

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

async function oak() {
    var oakWarning1 = "OAK: READER!";
    var oakWarning2 = "This isn't the";
    var oakWarning3 = "time to use that!";

    var index1 = 0;
    var index2 = 0;
    var index3 = 0;

    document.getElementById("dialogLine1").innerHTML = "";
    document.getElementById("dialogLine2").innerHTML = "";
    
    // Show Oak warning
    showText("#dialogLine1", oakWarning1, 0, 40);
    await sleep(480);
    showText("#dialogLine2", oakWarning2, 0, 40);

    await sleep(1000);

    document.getElementById("dialogLine1").innerHTML = oakWarning2;
    document.getElementById("dialogLine2").innerHTML = "";

    showText("#dialogLine2", oakWarning3, 0, 40);

    await sleep(2000);

    document.getElementById("dialogLine1").innerHTML = "Choose a page.";
    document.getElementById("dialogLine2").innerHTML = "";
}

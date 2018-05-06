var shouldWait = 0

function showText(target, message, index, interval) {   
  if (index < message.length) {
    shouldWait = 1;
    $(target).append(message[index++]);
    setTimeout(function () { showText(target, message, index, interval); }, interval);
  }
  shouldWait = 0;
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
    while (shouldWait != 0)
    {
        await sleep(50);
    }
    showText("#dialogLine2", oakWarning2, 0, 40);

    await sleep(1000);

    document.getElementById("dialogLine1").innerHTML = oakWarning2;
    document.getElementById("dialogLine2").innerHTML = "";

    showText("#dialogLine2", oakWarning3, 0, 40);

    await sleep(2000);

    document.getElementById("dialogLine1").innerHTML = "Choose a page.";
    document.getElementById("dialogLine2").innerHTML = "";
}
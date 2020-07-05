async function isValidData() {
  var form = document.forms["regForm"];
  var email = form["email"].value;
  var spot = form["spot"].value;
  var carNumber = form["carNumber"].value;

  var reg = "(^[a-zA-Z0-9]*$)";

  var isEmailValid = false;
  var isSpotValid = false;
  var isCarNumberValid = false;

  if (email == "" || spot == "" || carNumber == "") {
    document.getElementById("emptyFieldsError").style.display = "block";
  } else {
    document.getElementById("emptyFieldsError").style.display = "none";

    if (!/(.+)@(.+){2,}\.(.+){2,}/.test(email)) {
      document.getElementById("emailErr").style.display = "block";
    } else {
      isEmailValid = true;
      document.getElementById("emailErr").style.display = "none";
    }

    if (spot == "") {
      document.getElementById("spotErr").style.display = "block";
    } else {
      isSpotValid = true;
      document.getElementById("spotErr").style.display = "none";
    }

    if (carNumber == "") {
      document.getElementById("carErr").style.display = "block";
    } else {
      isCarNumberValid = true;
      document.getElementById("carErr").style.display = "none";
    }

    if (isEmailValid && isCarNumberValid && isSpotValid) {
      form["data"].value = JSON.stringify({
        email: email,
        spot: spot,
        carNumber: carNumber,
      });

      return true;
    }
  }

  return false;
}

function reserve() {
  if (isValidData()) {
    sendReservation();
  }
}

function sendReservation() {
  var data = JSON.parse(document.getElementById("data").value);

  var spotArr = sessionStorage.getItem("spot").match(/[a-zA-Z]+|[0-9]+/g);

  var spot = spotArr[1];
  var zone = spotArr[0];
  var user = data.email;
  var car = data.carNumber;

  console.log(spot + " " + zone + " " + user + " " + car);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "../utils/helper_php/reserveParkingSpot.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    // do something to response
    console.log(this.responseText);
    if (this.responseText == "1") {
      document.getElementById("successReservation").style.display = "block";
    } else {
      document.getElementById("alreadyExistingUserError").style.display =
        "block";
    }
  };

  xhr.send(
    "number=" +
      spot +
      "&zone=" +
      zone +
      "&userInSpot=" +
      user +
      "&carInSpot=" +
      car
  );
}

function setUp() {
  document.getElementById("spot").value = sessionStorage.getItem("spot");

  document.getElementById("email").value = sessionStorage.getItem("user_email");

  document.getElementById("typeSpot").value = sessionStorage.getItem(
    "typeSpot"
  );
}

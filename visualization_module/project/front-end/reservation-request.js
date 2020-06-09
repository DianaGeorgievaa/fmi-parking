
async function isValidData() {
  var form = document.forms["regForm"];
  var username = form["username"].value;
  var date = form["date"].value;
  var carNumber = form["carNumber"].value;

  var reg = "(^[a-zA-Z0-9]*$)";

  var isUsernameValid = false;
  var isDateValid = false;
  var isCarNumberValid = false;

  if (username == "" || date == "" || carNumber == "") {
    document.getElementById("emptyFieldsError").style.display = "block";
  } else {
    document.getElementById("emptyFieldsError").style.display = "none";

    if (username.length < 3 || username.length > 10) {
      document.get.getElementById("usernameErr").style.display = "block";
    } else {
      isUsernameValid = true;
      document.getElementById("usernameErr").style.display = "none";
    }

    if (date == "") {
      document.getElementById("dateErr").style.display = "block";
    } else {
      isDateValid = true;
      document.getElementById("dateErr").style.display = "none";
    }

    if (carNumber == "") {
      document.getElementById("carErr").style.display = "block";
    } else {
      isCarNumberValid = true;
      document.getElementById("carErr").style.display = "none";
    }

    if (isUsernameValid && isCarNumberValid && isDateValid) {
      form["data"].value = JSON.stringify({
        username: username,
        date: date,
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
var zone =  spotArr[0];
var user = data.username;
var car = data.carNumber;
//var date = data.date;

console.log(spot + " "+ zone + " " + user + " "+ car);


  var xhr = new XMLHttpRequest();
  xhr.open("POST", "http://localhost/project/helper_php/reserveParkingSpot.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    // do something to response
    console.log(this.responseText);
	if(this.responseText == "1"){
		document.getElementById("successReservation").style.display = "block";
	} else{
		document.getElementById("alreadyExistingUserError").style.display = "block";
	}
  };

  xhr.send("number="+spot+"&zone="+zone+"&userInSpot="+user+"&carInSpot="+car);
}

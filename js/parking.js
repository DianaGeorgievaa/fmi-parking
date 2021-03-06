var HttpClient = function () {
  this.get = function (aUrl, aCallback) {
    var anHttpRequest = new XMLHttpRequest();
    anHttpRequest.onreadystatechange = function () {
      if (anHttpRequest.readyState == 4 && anHttpRequest.status == 200)
        aCallback(anHttpRequest.responseText);
    };

    anHttpRequest.open("GET", aUrl, false); //true for asynchronous
    anHttpRequest.send(null);
  };
};

function getParkingSpots() {
  var client = new HttpClient();
  var parkingSpots;

  client.get("../utils/helper_php/getParkingSpots.php", function (response) {
    parkingSpots = JSON.parse(response);
    console.log(response);
    console.log(parkingSpots);
  });
}

function getAvailableParkingSpots() {
  var client = new HttpClient();
  var parkingSpots;

  client.get("../utils/helper_php/getAvailableParkingSpots.php", function (
    response
  ) {
    parkingSpots = JSON.parse(response);
    console.log(response);
    console.log(parkingSpots);
  });

  return parkingSpots.length;
}

function isSpotAvailable(spot) {
  var client = new HttpClient();
  var parkingSpots;

  var resp = false;

  if (spot == "build") {
    return true;
  }

  console.log(window.location.href);

  client.get("../utils/helper_php/getAvailableParkingSpots.php", function (
    response
  ) {
    parkingSpots = JSON.parse(response);
    for (parkingSpot of parkingSpots) {
      if (parkingSpot.zone + parkingSpot.number == spot) {
        resp = true;
      }
    }
  });

  return resp;
}

function getParkingSpotsPerZone(zone) {
  var client = new HttpClient();
  var parkingSpots;

  client.get(
    "../utils/helper_php/getParkingSpotsPerZone.php?zone=" + zone,
    function (response) {
      parkingSpots = JSON.parse(response);
      console.log(response);
      console.log(parkingSpots);
    }
  );

  return parkingSpots.length;
}

function setAvailable() {
  document.getElementById(
    "availableSpots"
  ).innerHTML = getAvailableParkingSpots();
}

function setAvailableZones() {
  document.getElementById("availableSpotsZoneA") != null
    ? (document.getElementById(
        "availableSpotsZoneA"
      ).innerHTML = getParkingSpotsPerZone("A"))
    : "";
  document.getElementById("availableSpotsZoneB") != null
    ? (document.getElementById(
        "availableSpotsZoneB"
      ).innerHTML = getParkingSpotsPerZone("B"))
    : "";
  document.getElementById("availableSpotsZoneC") != null
    ? (document.getElementById(
        "availableSpotsZoneC"
      ).innerHTML = getParkingSpotsPerZone("C"))
    : "";
}

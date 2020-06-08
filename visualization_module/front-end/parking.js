

var HttpClient = function () {
	this.get = function (aUrl, aCallback) {
		var anHttpRequest = new XMLHttpRequest();
		anHttpRequest.onreadystatechange = function () {
			if (anHttpRequest.readyState == 4 && anHttpRequest.status == 200)
				aCallback(anHttpRequest.responseText);
		}

		anHttpRequest.open("GET", aUrl, false); //true for asynchronous
		anHttpRequest.send(null);
	}
}

function getParkingSpots() {
	var client = new HttpClient();
	var parkingSpots;

	client.get('http://localhost/helper_php/getParkingSpots.php', function (response) {
		parkingSpots = JSON.parse(response);
		console.log(response);
		console.log(parkingSpots);
	});
}

function getAvailableParkingSpots() {
	var client = new HttpClient();
	var parkingSpots;

	client.get('http://localhost/helper_php/getAvailableParkingSpots.php', function (response) {
		parkingSpots = JSON.parse(response);
		console.log(response);
		console.log(parkingSpots);
	});
}

function getParkingSpotsPerZone() {
	var client = new HttpClient();
	var parkingSpots;

	client.get('http://localhost/helper_php/getParkingSpotsPerZone.php?zone=A', function (response) {
		parkingSpots = JSON.parse(response);
		console.log(response);
		console.log(parkingSpots);
	});
}

function reserveParkingSpot() {

	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'http://localhost/helper_php/reserveParkingSpot.php', true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.onload = function () {
		// do something to response
		console.log(this.responseText);
	};

	xhr.send('number=1&zone=A&userInSpot=rosi&carInSpot=pesho');
}
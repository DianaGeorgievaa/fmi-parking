<?php
include '../views/menu.php';

if (!isLoggedInUser()) {
    header('Location:' . '../views/index.php');
}

$user_email = (isset($_SESSION['email'])) ? $_SESSION['email'] : '';
$carNumber = (isset($_SESSION['carNumber'])) ? $_SESSION['carNumber'] : '';

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../styles/fmi-parking-style.css">
    <link rel="stylesheet" type="text/css" href="../styles/zoneA.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>
    <script src="../js/take-spot.js"></script>
    <script src="../js/parking.js"></script>
    <title>Zone A</title>
</head>

<body id="body" onload="setAvailableZones();">
    <div class="left">
        <div class="aLeft">
            <div class="spot">
                <svg width="75" height="50">
                    <rect id="A1" x="0" y="0" width="75" height="50" fill="green" onclick="reserveSpot('A1');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">A1</text>
                </svg>
            </div>
            <div class="spot">
                <svg width="75" height="50">
                    <rect id="A2" x="0" y="0" width="75" height="50" fill="green" onclick="reserveSpot('A2');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">A2</text>
                </svg>
            </div>
            <div class="spot">
                <svg width="75" height="50">
                    <rect id="A3" x="0" y="0" width="75" height="50" fill="green" onclick="reserveSpot('A3');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">A3</text>
                </svg>
            </div>
            <div class="spot">
                <svg width="75" height="50">
                    <rect id="A4" x="0" y="0" width="75" height="50" fill="green" onclick="reserveSpot('A4');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">A4</text>
                </svg>
            </div>

            <div class="spot">
                <svg width="75" height="50">
                    <rect id="A5" x="0" y="0" width="75" height="50" fill="green" onclick="reserveSpot('A5');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">A5</text>
                </svg>
            </div>

            <div class="spot">
                <svg width="75" height="50">
                    <rect id="A6" x="0" y="0" width="75" height="50" fill="green" onclick="reserveSpot('A6');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">A6</text>
                </svg>
            </div>
        </div>

        <div class="aRight">
            <div class="spot">
                <svg width="75" height="50">
                    <rect id="A7" x="0" y="0" width="75" height="50" fill="green" onclick="reserveSpot('A7');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">A7</text>
                </svg>
            </div>
            <div class="spot">
                <svg width="75" height="50">
                    <rect id="A8" x="0" y="0" width="75" height="50" fill="green" onclick="reserveSpot('A8');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">A8</text>
                </svg>
            </div>
            <div class="spot">
                <svg width="75" height="50">
                    <rect id="A9" x="0" y="0" width="75" height="50" fill="green" onclick="reserveSpot('A9');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">A9</text>
                </svg>
            </div>
            <div class="spot">
                <svg width="75" height="50">
                    <rect id="A10" x="0" y="0" width="75" height="50" fill="green" onclick="reserveSpot('A10');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">A10</text>
                </svg>
            </div>

            <div class="spot">
                <svg width="75" height="50">
                    <rect id="A11" x="0" y="0" width="75" height="50" fill="green" onclick="reserveSpot('A11');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">A11</text>
                </svg>
            </div>

            <div class="spot">
                <svg width="75" height="50">
                    <rect id="A12" x="0" y="0" width="75" height="50" fill="green" onclick="reserveSpot('A12');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">A12</text>
                </svg>
            </div>
        </div>

        <div id="building">
            <svg width="150" height="350">
                <rect id="build" x="0" y="0" width="150" height="350" fill="yellow" />
                <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">FMI</text>
            </svg>
        </div>

        <div id="availabilityInfo">Available spots:
            <span id="availableSpotsZoneA"></span>
        </div>

    </div>
    <div id="embedForm">
        <embed type="text/html" src="send-req-form.html" width="500" height="350">
    </div>


</body>

</html>

<script>
    var svgContainer = d3
        .select("body")
        .append("svg")
        .attr("width", 200)
        .attr("height", 200);

    var rectangleFMIBuilding = svgContainer
        .append("rect")
        .attr("x", 10)
        .attr("y", 10);

    function reserveSpot(spot) {
        console.log(spot);
        sessionStorage.setItem("spot", spot);

        var user_email = '<?php echo $user_email; ?>';
        console.log(user_email);
        sessionStorage.setItem("user_email", user_email);

        var car_number = '<?php echo $carNumber; ?>';
        console.log(car_number);
        sessionStorage.setItem("car_number", car_number);

        document.getElementById("embedForm").style.display = "block";
        d3.select("#" + spot).attr("fill", "red");

        sessionStorage.setItem("typeSpot", "sunny");

        document.getElementById("spot").value = sessionStorage.getItem("spot");

        document.getElementById("email").value = sessionStorage.getItem("user_email");
        document.getElementById("carNumber").value = sessionStorage.getItem("car_number");
    }

    function setAvailableZones() {
        document.getElementById("availableSpotsZoneA") != null ? document.getElementById("availableSpotsZoneA").innerHTML = getParkingSpotsPerZone("A") : "";
        document.getElementById("availableSpotsZoneB") != null ? document.getElementById("availableSpotsZoneB").innerHTML = getParkingSpotsPerZone("B") : "";
        document.getElementById("availableSpotsZoneC") != null ? document.getElementById("availableSpotsZoneC").innerHTML = getParkingSpotsPerZone("C") : "";

        var rects = d3.selectAll("svg").selectAll("rect")
            .style("fill", function(d) {
                if (this.id == "build") {
                    return "yellow";
                } else if (isSpotAvailable(this.id)) {
                    return "green";
                } else {
                    return "red";
                }
            });
    }
</script>
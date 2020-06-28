<?php
include '../views/main.php';

$user_email = (isset($_SESSION['email'])) ? $_SESSION['email'] : '';

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../styles/zoneB.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>
    <script src="../js/take-spot.js"></script>
    <script src="../js/parking.js"></script>
    <title>Zone B</title>
</head>

<body id="body" onload="setAvailableZones();">
    <div class="left">
        <div class="aRight">
            <div class="spot">
                <svg width="75" height="50">
                    <rect id="B1" x="0" y="0" width="75" height="50" fill="green" onclick="reserveSpot('B1');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">B1</text>
                </svg>
            </div>
            <div class="spot">
                <svg width="75" height="50">
                    <rect id="B2" x="0" y="0" width="75" height="50" fill="green" onclick="reserveSpot('B2');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">B2</text>
                </svg>
            </div>
            <div class="spot">
                <svg width="75" height="50">
                    <rect id="B3" x="0" y="0" width="75" height="50" fill="green" onclick="reserveSpot('B3');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">B3</text>
                </svg>
            </div>
            <div class="spot">
                <svg width="75" height="50">
                    <rect id="B4" x="0" y="0" width="75" height="50" fill="green" onclick="reserveSpot('B4');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">B4</text>
                </svg>
            </div>

        </div>
        <div id="building">
            <svg width="150" height="350">
                <rect id="build" x="0" y="0" width="150" height="350" fill="orange" />
                <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">FHF</text>
            </svg>
        </div>
    </div>
    <div id="availabilityInfo">Available spots:
        <span id="availableSpotsZoneB"></span>
    </div>
    </div>
    <div id="embedForm">
        <embed type="text/html" src="send-req-form.html" width="500" height="350">
    </div>
</body>

</html>

<script>
    var svgContainer = d3.select("body").append("svg")
        .attr("width", 200)
        .attr("height", 200);


    var rectangleFMIBuilding = svgContainer.append("rect")
        .attr("x", 10)
        .attr("y", 10);

    function reserveSpot(spot) {
        console.log(spot);
        sessionStorage.setItem("spot", spot);

        var user_email = '<?php echo $user_email; ?>';
        console.log(user_email);
        sessionStorage.setItem("user_email", user_email);

        sessionStorage.setItem("typeSpot", "sunny");

        document.getElementById("embedForm").style.display = "block";
        d3.select("#" + spot).attr("fill", "red");
    }

    function setAvailableZones() {
        document.getElementById("availableSpotsZoneA") != null ? document.getElementById("availableSpotsZoneA").innerHTML = getParkingSpotsPerZone("A") : "";
        document.getElementById("availableSpotsZoneB") != null ? document.getElementById("availableSpotsZoneB").innerHTML = getParkingSpotsPerZone("B") : "";
        document.getElementById("availableSpotsZoneC") != null ? document.getElementById("availableSpotsZoneC").innerHTML = getParkingSpotsPerZone("C") : "";

        var rects = d3.selectAll("svg").selectAll("rect")
            .style("fill", function(d) {
                if (this.id == "build") {
                    return "orange";
                } else if (isSpotAvailable(this.id)) {
                    return "green";
                } else {
                    return "red";
                }
            });
    }
</script>
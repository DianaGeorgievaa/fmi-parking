<?php
include '../views/main.php';

$user_email = (isset($_SESSION['email'])) ? $_SESSION['email'] : '';

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../styles/zoneC.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>
    <script src="../js/take-spot.js"></script>
    <script src="../js/parking.js"></script>
    <title>Zone C</title>
</head>

<body id="body" onload="setAvailableZones();">

    <div class="left">
        <div class="aLeft">

            <div class="spot">
                <svg width="50" height="75">
                    <rect id="C1" x="0" y="0" width="50" height="75" fill="green" onclick="reserveSpot('C1');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">C1</text>
                </svg>
            </div>
            <div class="spot">
                <svg width="50" height="75">
                    <rect id="C2" x="0" y="0" width="50" height="75" fill="green" onclick="reserveSpot('C2');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">C2</text>
                </svg>
            </div>
            <div class="spot">
                <svg width="50" height="75" transform="rotate(-20)" style="margin-top: 20px; margin-left: 14px;">
                    <rect id="C3" x="0" y="0" width="50" height="75" fill="green" onclick="reserveSpot('C3');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">C3</text>
                </svg>
            </div>
            <div class="spot">
                <svg width="50" height="75" transform="rotate(-45)" style="margin-left: 88px;">
                    <rect id="C4" x="0" y="0" width="50" height="75" fill="green" onclick="reserveSpot('C4');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">C4</text>
                </svg>
            </div>
        </div>

        <div id="building">
            <svg width="350" height="150">
                <rect id="build" x="0" y="0" width="350" height="150" fill="yellow" />
                <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">FMI</text>
            </svg>
        </div>

        <div class="aRight">
            <div class="spot">
                <svg width="50" height="75">
                    <rect id="C5" x="0" y="0" width="50" height="75" fill="green" onclick="reserveSpot('C5');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">C5</text>
                </svg>
            </div>
            <div class="spot">
                <svg width="50" height="75">
                    <rect id="C6" x="0" y="0" width="50" height="75" fill="green" onclick="reserveSpot('C6');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">C6</text>
                </svg>
            </div>
            <div class="spot">
                <svg width="50" height="75" transform="rotate(20)" style="margin-top: 20px; margin-left: -10px;">
                    <rect id="C7" x="0" y="0" width="50" height="75" fill="green" onclick="reserveSpot('C7');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">C7</text>
                </svg>
            </div>
            <div class="spot">
                <svg width="50" height="75" transform="rotate(45)" style="margin-left: -90px;">
                    <rect id="C8" x="0" y="0" width="50" height="75" fill="green" onclick="reserveSpot('C8');" />
                    <text x="20" y="20" font-family="sans-serif" font-size="20px" fill="black">C8</text>
                </svg>
            </div>
        </div>
    </div>
    <div id="availabilityInfo">Available spots:
        <span id="availableSpotsZoneC"></span>
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

        sessionStorage.setItem("typeSpot", "shady");

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
                    return "yellow";
                } else if (isSpotAvailable(this.id)) {
                    return "green";
                } else {
                    return "red";
                }
            });
    }
</script>
<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'database.php';
include_once 'parkingSpot.php';

class Parking
{

    private $database;
    private $db;

    private $countAvailableSpots;

    public function __construct()
    {
        $database = new Database();
        $db = $database->getConnection();
    }

    public function retrieveParkingSpots()
    {

        $parkingSpot = new ParkingSpot($this->db);

        $result = $parkingSpot->getSpots();

        $parkingSpots_arr = array();
        $current_arr = array();
        $index = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);

            $current_arr = array(
                "number" => $number,
                "zone" => $zone,
                "isAvailable" => $is_free,
                "type" => $type,
                "userInSpot" => $user_in_spot,
                "carInSpot" => $car_in_spot
            );

            $parkingSpots_arr[$index] = $current_arr;

            $index++;

        }

        http_response_code(200);

        echo json_encode($parkingSpots_arr);

    }

    function retrieveAvailableSpots()
    {

        $parkingSpot = new ParkingSpot($this->db);

        $result = $parkingSpot->getSpots();

        $parkingSpots_arr = array();
        $current_arr = array();
        $index = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);

            if ($row["is_free"] == 1)
            {

                $current_arr = array(
                    "number" => $number,
                    "zone" => $zone,
                    "isAvailable" => $is_free,
                    "type" => $type,
                    "userInSpot" => $user_in_spot,
                    "carInSpot" => $car_in_spot
                );

                $parkingSpots_arr[$index] = $current_arr;

                $index++;

            }

        }

        $this->countAvailableSpots = $index;

        http_response_code(200);

        echo json_encode($parkingSpots_arr);
    }

    function retrieveAvailableSpotsPerZone()
    {
        $parkingSpot = new ParkingSpot($this->db);

        $result = $parkingSpot->getSpots();

        $wantedZone = $_GET['zone'];

        $parkingSpots_arr = array();
        $current_arr = array();
        $index = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);

            if ($row["is_free"] == 1 and $row["zone"] == $wantedZone)
            {

                $current_arr = array(
                    "number" => $number,
                    "zone" => $zone,
                    "isAvailable" => $is_free,
                    "type" => $type,
                    "userInSpot" => $user_in_spot,
                    "carInSpot" => $car_in_spot
                );

                $parkingSpots_arr[$index] = $current_arr;

                $index++;

            }

        }

        http_response_code(200);

        echo json_encode($parkingSpots_arr);

    }

    function isSpotAvailable($wantedNumber, $wantedZone)
    {
        $parkingSpot = new ParkingSpot($this->db);
        $result = $parkingSpot->getSpots();

        while ($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);

            if ($row["is_free"] == 1 and $row["zone"] == $wantedZone and $row["number"] = $wantedNumber)
            {

                return true;
            }

        }

        return false;
    }

    function reserveParkingSpot()
    {

        $number = $_POST['number'];
        $zone = $_POST['zone'];
        $userInSpot = $_POST['userInSpot'];
        $carInSpot = $_POST['carInSpot'];

        if ($this->isSpotAvailable($number, $zone))
        {

            $parkingSpot = new ParkingSpot($this->db);
            $parkingSpot->number = $number;
            $parkingSpot->zone = $zone;
            $parkingSpot->userInSpot = $userInSpot;
            $parkingSpot->carInSpot = $carInSpot;

            echo $parkingSpot->reserveSpot();

            $this->countAvailableSpots--;
        }

    }
}

?>

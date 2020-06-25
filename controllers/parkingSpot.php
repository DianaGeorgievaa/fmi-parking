<?php

include_once 'database.php';

class ParkingSpot
{
    private $conn;
    private $table_name = "parking_spot";

    public $number; //parking spot identifyer
    public $zone;
    public $size;
    public $is_free; //state
    public $type; //sunny, shady
    public $userInSpot;
    public $carInSpot;

    public function __construct($dbConnection)
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // retrieve all parking spots
    function getSpots()
    {

        try
        {
            $query = "SELECT * " . "FROM	" . $this->table_name;
            $stmt = $this
                ->conn
                ->prepare($query);

            $stmt->execute();

        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }

        return $stmt;
    }

    public function reserveSpot()
    {

        $userInSpot = $this->userInSpot;
        $carInSpot = $this->carInSpot;
        $number = $this->number;
        $zone = $this->zone;

        $query = "UPDATE parking_spot SET is_free=false, user_in_spot='$userInSpot', car_in_spot='$carInSpot' WHERE number='$number' AND zone='$zone'";

        $stmt = $this
            ->conn
            ->prepare($query);

        // $this->number = htmlspecialchars(strip_tags($this->number));
        // $this->zone = htmlspecialchars(strip_tags($this->zone));

        if ($stmt->execute())
        {
            return true;
        }
        return false;
    }
}

?>

<?php
class User {
    private $firstName;
    private $lastName;
    private $email;
    private $password;
    private $status;
    private $photoName;
    private $points;
    private $qrCode;

    public function __construct($firstName, $lastName, $email, $password, $status, $photoName, $points, $qrCode)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->status = $status;
        $this->photoName = $photoName;
        $this->points = $points;
        $this->qrCode = $qrCode;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getPhotoName()
    {
        return $this->photoName;
    }

    public function setPhotoName($photoName)
    {
        $this->photoName = $photoName;
    }

    public function getPoints()
    {
        return $this->points;
    }

    public function setPoints($points)
    {
        $this->points = $points;
    }

    public function getQrCode()
    {
        return $this->qrCode;
    }

    public function setQrCode($qrCode)
    {
        $this->qrCode = $qrCode;
    }
}
?>
<?php
class WeeklyScheduler
{
   private $user;
   private $course;

   // public function __construct($user, $course)
   // {
   //  $this->user = $user;
   //  $this->course = $course;
   // }

   public function getUser()
   {
      return $this->user;
   }

   public function setUser($user)
   {
      $this->user = $user;

      return $this;
   }

   public function getCourse()
   {
      return $this->course;
   }

   public function setCourse($course)
   {
      $this->course = $course;
   }
}
?>
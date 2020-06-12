<?php
class Course {
    private $courseId;
    private $courseTitle;
    private $courseDay;
    private $startTime;
    private $endTime;

    public function __construct($courseTitle, $courseDay, $startTime, $endTime)
    {
        $this->courseTitle = $courseTitle;
        $this->courseDay = $courseDay;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }

    public function getCourseId()
    {
        return $this->courseId;
    }

    public function setCourseId($courseId)
    {
        $this->courseId = $courseId;
    }

    function getCourseTitle()
    {
        return $this->courseTitle;
    }

    function setCourseTitle($courseTitle)
    {
        $this->courseTitle = $courseTitle;
    }
    
    public function getCourseDay()
    {
        return $this->courseDay;
    }

    public function setCourseDay($courseDay)
    {
        $this->courseDay = $courseDay;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    public function getEndTime()
    {
        return $this->endTime;
    }

    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }
}

?>
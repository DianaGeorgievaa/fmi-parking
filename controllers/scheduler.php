<?php

define("SCHEDULER_FIELDNAME", "scheduler");

$scheduler_formats = array("json");

if ($_POST)
{
    validateSchedulerFormat();
    $scheduler = $_POST[SCHEDULER_FIELDNAME];
    constructScheduler();
}

function validateSchedulerFormat() 
{
    $schedulerErrors = $_FILES[SCHEDULER_FIELDNAME]["error"];
    if ($schedulerErrors == UPLOAD_ERR_NO_FILE) 
    {
        die("No scheduler chose. Please select the scheduler!");
    }

    $info = pathinfo($_FILES[SCHEDULER_FIELDNAME]['name']);
    $extension = $info['extension'];
    global $scheduler_formats;
    if (!in_array($extension, $scheduler_formats)) 
    {
        die("The uploaded scheduler is not in valid format!");
    }
}

function constructScheduler() 
{
    $weeklyScheduler = new WeeklyScheduler();
    $a = json_decode($_POST[SCHEDULER_FIELDNAME], true);
    echo $a;
}

function saveScheduler($weeklyScheduler) 
{
   
}

?>
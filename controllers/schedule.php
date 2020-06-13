<?php
include '../utils/databaseQueriesUtils.php';

define("NAME_PATTERN", '/^[A-Z][a-z]+$/', true);
define("COURSE_PATTERN", '/^[a-zA-Z0-9 ]+$/', true);
define("TIME_PATTERN", '/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/', true);

define("SCHEDULE_FIELDNAME", "schedule");

define("FIRSTNAME_FIELDNAME", "firstname", true);
define("LASTNAME_FIELDNAME", "lastname", true);
define("COURS_TITLE_FIELDNAME", "courseTitle", true);
define("COURS_DAY_FIELDNAME", "courseDay", true);
define("COURS_START_TIME_FIELDNAME", "startTime", true);
define("COURS_END_TIME_FIELDNAME", "endTime", true);

$dayOfWeeks = Utils::DAYS_OF_WEEK;
$schedule_formats = array("json");
$invalidFieldMessages = array(
    FIRSTNAME_FIELDNAME => "Please insert correct firstname!",
    LASTNAME_FIELDNAME => "Please insert correct lastname!",
    COURS_TITLE_FIELDNAME => "Please insert correct course title!",
    COURS_DAY_FIELDNAME => "Please insert correct day!",
    COURS_START_TIME_FIELDNAME => "Please insert correct start time!",
    COURS_END_TIME_FIELDNAME => "Please insert correct end time!"
);

if (!isset($_SESSION)) {
    session_start();
}

if ($_POST) {
    if (isLoggedInAdmin()) {
        validateSchedulerFormat();
        $scheduler = $_FILES[SCHEDULE_FIELDNAME];
        constructScheduler();
        Utils::showMessage(MessageUtils::SUCCESSFUL_UPLOADED_SCHEDULER_MESSAGE, true);
    } else {
        Utils::showMessage(MessageUtils::UPLOADING_SCHEDULER_ERROR_MESSAGE, false);
    }
}

function isLoggedInAdmin()
{
    return $_SESSION['status'] == 'ADMIN';
}

function validateSchedulerFormat()
{
    if(!isset($_FILES[SCHEDULE_FIELDNAME])){
        Utils::showMessage(MessageUtils::NOT_SELECTED_SCHEDULER_ERROR_MESSAGE, false);
    }
    $schedulerErrors = $_FILES[SCHEDULE_FIELDNAME]["error"];
    if ($schedulerErrors == UPLOAD_ERR_NO_FILE) {
        Utils::showMessage(MessageUtils::NOT_SELECTED_SCHEDULER_ERROR_MESSAGE, false);
    }

    $info = pathinfo($_FILES[SCHEDULE_FIELDNAME]['name']);
    $extension = $info['extension'];
    global $schedule_formats;
    if (!in_array($extension, $schedule_formats)) {
        Utils::showMessage(MessageUtils::INVALID_SCHEDULER_FORMAT_ERROR_MESSAGE, false);
    }
}

function constructScheduler()
{
    $json_data = file_get_contents($_FILES[SCHEDULE_FIELDNAME]['name']);
    $decodedJson = json_decode($json_data);
    for ($i = 0; $i < sizeof($decodedJson->user); $i++) {
        $errors = array();
        $course = null;
        $lecture = null;
        $areValidLecturerNames = false;
        $areValidCourseFields = false;

        $firstName = $decodedJson->user[$i]->firstName;
        $lastName = $decodedJson->user[$i]->lastName;
        $areValidLecturerNames = areValidLecturerNames($firstName, $lastName, $errors);
        if (!$areValidLecturerNames) {
            continue;
        }

        $lecture = new Lecturer($firstName, $lastName);
        for ($j = 0; $j < sizeof($decodedJson->user[$i]->course); $j++) {
            $courseTitle = $decodedJson->user[$i]->course[$j]->courseTitle;
            $courseDay = $decodedJson->user[$i]->course[$j]->courseDay;
            $startTime = $decodedJson->user[$i]->course[$j]->startTime;
            $endTime = $decodedJson->user[$i]->course[$j]->endTime;

            $areValidCourseFields = areValidCourseFields($courseTitle, $courseDay, $startTime, $endTime, $errors);
            if ($areValidLecturerNames && $areValidCourseFields) {
                $course = new Course($courseTitle, $courseDay, $startTime, $endTime);
                saveScheduler($lecture, $course);
            }
        }
        if (!$areValidLecturerNames || !$areValidCourseFields) {
            echo "Invalid record in the shedule, it will be skipped!";
        }
    }
}

function saveScheduler(Lecturer $lecture, Course $course)
{
    $firstNamelecture = $lecture->getFirstName();
    $lastNamelecture = $lecture->getLastName();
    $lectureId = DatabaseQueriesUtils::getLectureIdByNames($firstNamelecture, $lastNamelecture);
    if ($lectureId == null) {
        echo "The lecturer $firstNamelecture $lastNamelecture is not registred in the system!";
        return;
    }

    DatabaseQueriesUtils::saveScheduler($course, $lectureId);
}

function areValidLecturerNames($firstName, $lastName, &$errors)
{
    validateField($firstName, NAME_PATTERN, FIRSTNAME_FIELDNAME, $errors);
    validateField($lastName, NAME_PATTERN, LASTNAME_FIELDNAME, $errors);
    if (count($errors) != 0) {
        return false;
    }
    return true;
}

function areValidCourseFields($courseTitle, $courseDay, $startTime, $endTime, &$errors)
{
    if (!in_array(strtoupper($courseDay), Utils::DAYS_OF_WEEK)) {
        global $invalidFieldMessages;
        $courseDayFieldName = COURS_DAY_FIELDNAME;
        $errors[$courseDayFieldName] = $invalidFieldMessages[COURS_DAY_FIELDNAME];
        echo "$errors[$courseDayFieldName] <br>";
    }
    validateField($courseTitle, COURSE_PATTERN, COURS_TITLE_FIELDNAME, $errors);
    validateField($startTime, TIME_PATTERN, COURS_START_TIME_FIELDNAME, $errors);
    validateField($endTime, TIME_PATTERN, COURS_END_TIME_FIELDNAME, $errors);
    if (count($errors) != 0) {
        return false;
    }
    return true;
}

function validateField($fieldValue, $fieldPattern, $fieldName, &$errors)
{
    if (empty($fieldValue)) {
        $errors["$fieldName"] = "The field $fieldName is required!";
    } elseif (!preg_match($fieldPattern, $fieldValue)) {
        global $invalidFieldMessages;
        $errors[$fieldName] = $invalidFieldMessages[$fieldName];
    }

    if (array_key_exists($fieldName, $errors)) {
        echo "$errors[$fieldName] <br>";
    }
}
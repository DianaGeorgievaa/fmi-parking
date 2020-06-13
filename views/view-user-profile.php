<?php
include '../utils/databaseQueriesUtils.php';
include '../views/menu.php';

if (isLoggedInUser()) {
    $viewedUserId = $_REQUEST['viewedUserId'];
    $user = DatabaseQueriesUtils::getUserByUserId($viewedUserId);

    $userId = $user['user_id'];
    $firstname = $user['first_name'];
    $lastname = $user['last_name'];
    $email = $user['email'];
    $userPoints = $user['points'];
    $userStatus = $user['status'];
    $userStatusLowerCase = ucfirst(strtolower($userStatus));
    $userPhotoName = $user['photo_name'];
    $userPhotoPath = Utils::USER_PHOTO_FOLDER_PATH . $userPhotoName;

    DatabaseQueriesUtils::saveViewer($viewedUserId);

    $userParkingInfo = DatabaseQueriesUtils::getUserParkingInfo($viewedUserId);
    // $parkingSpot = null;
    // if ($userParkingInfo != null) {
    //     $parkingSpotId = $userParkingInfo['parking_spot_id'];
    //     $parkingSpot = DatabaseQueriesUtils::getParkingSpotById($parkingSpotId);
    // }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <link rel="stylesheet" type="text/css" href="../styles/fmi-parking-style.css">
    <link rel="stylesheet" type="text/css" href="../styles/view-user-profile.css">
    <title>User profile</title>
</head>

<body>
    <div class="user-wrapper">
        <div class="user-wrapper-info">
            <img id="user-image" src="<?php echo $userPhotoPath ?>">
            <div class="wrapper-info">
                <h2>User info</h2>
                <label><?php echo "Name: $firstname $lastname" ?></label>
                <label><?php echo "Email: $email" ?></label>
                <label><?php echo "Parking points: $userPoints" ?></label>
                <label><?php echo "Status: $userStatusLowerCase" ?></label>
                <label><?php if ($userParkingInfo != null) {
                            // $zone = $parkingSpot['zone'];
                            // $parkNumber = $parkingSpot['number'];
                            // echo "The user is parked on parking spot: $zone $parkNumber";
                            echo "<b> The user is in the parking now!</b>";
                        } else {
                            echo "<b> The user is not in the parking now! </b>";
                        } ?></label>
            </div>
        </div>
        <table class="table-style">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Day</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $courseIds = DatabaseQueriesUtils::getUserCourseIds($userId);
                if($courseIds == null){
                    return;
                }
                $courses = DatabaseQueriesUtils::getUserCourses($courseIds);
                foreach ($courses as $course) { 
                    $courseDay = $course['course_day'];
                    $courseDayLowerCase = ucfirst(strtolower($courseDay));
                    ?>
                    <tr>
                        <td><?= $course['course_title'] ?></td>
                        <td><?= $courseDayLowerCase . ', ' . $course['start_time'] . '-' . $course['end_time'] . ' h' ?></td>
                    </tr>
                <?php } ?>
    </div>
</body>

</html>
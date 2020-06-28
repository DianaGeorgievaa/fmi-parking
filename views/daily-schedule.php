<?php
include '../utils/databaseQueriesUtils.php';
include '../views/menu.php';
?>

<!DOCTYPE html>
<html>

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <link rel="stylesheet" type="text/css" href="../styles/fmi-parking-style.css">
    <title>Daily schedule</title>
</head>

<body>
    <?php
    if (isLoggedInUser()) {
    ?>
        <div class="user-wrapper">
            <h2>Daily schedule</h2>
            <table class="table-style">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Teacher</th>
                        <th>Day</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $users = DatabaseQueriesUtils::getAllUsers();
                    if ($users == null) {
                        return;
                    }
                    $currentDay = strtoupper(date('l'));
                    foreach ($users as $user) {
                        $currentUserNames = $user['first_name'] . ' ' . $user['last_name'];
                        $currentUserId = $user['user_id'];
                        $currentUserCourseIds = DatabaseQueriesUtils::getUserCourseIds($currentUserId);
                        if ($currentUserCourseIds == null) {
                            continue;
                        }
                        $currentUserCourses = DatabaseQueriesUtils::getUserCourses($currentUserCourseIds);
                        foreach ($currentUserCourses as $course) {
                            $courseDay = $course['course_day'];
                            if ($currentDay != $courseDay) {
                                continue;
                            }
                            $courseDayLowerCase = ucfirst(strtolower($currentDay));
                    ?>
                            <tr>
                                <td><?= $course['course_title'] ?></td>
                                <td><?= $currentUserNames ?></td>
                                <td><?= $courseDayLowerCase . ', ' . $course['start_time'] . '-' . $course['end_time'] . ' h' ?></td>
                            </tr>
                    <?php }
                    } ?>
        </div>
    <?php } else {  ?>
        <label><?php echo "You should be logged in!" ?></label>
    <?php } ?>
</body>

</html>
<?php
include '../utils/databaseQueriesUtils.php';
include '../views/menu.php';

if (!isLoggedInUser()) {
    return;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <link rel="stylesheet" type="text/css" href="../styles/fmi-parking-style.css">
    <title>Weekly schedule</title>
</head>

<body>
    <div class="user-wrapper">
        <h2>Weekly schedule</h2>
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
                        $courseDayLowerCase = ucfirst(strtolower($courseDay));
                ?>
                        <tr>
                            <td><?= $course['course_title'] ?></td>
                            <td><?= $currentUserNames ?></td>
                            <td><?= $courseDayLowerCase . ', ' . $course['start_time'] . '-' . $course['end_time'] . ' h' ?></td>
                        </tr>
                <?php }
                } ?>
    </div>
</body>

</html>
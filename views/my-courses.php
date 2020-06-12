<?php
include '../utils/databaseQueriesUtils.php';
include '../views/menu.php';

if (isLoggedInUser()) {
    $email = $_SESSION["email"];
    $user = DatabaseQueriesUtils::getUserByEmail($email);

    $userId = $user['user_id'];
    $courseIds = DatabaseQueriesUtils::getUserCourseIds($userId);
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
        <link rel="stylesheet" type="text/css" href="../styles/fmi-parking-style.css">
        <title>My courses</title>
    </head>

    <body>
        <h4>My courses</h4>
        <?php
        if ($courseIds == "") { ?>
            <p>You don't have any courses in the system</p>
        <?php return; } ?>
        <table class="table-style">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Day</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $courses = DatabaseQueriesUtils::getUserCourses($courseIds);
                foreach ($courses as $course) { ?>
                    <tr>
                        <td><?= $course['course_title'] ?></td>
                        <td><?= $course['course_day'] . ', ' . $course['start_time'] . '-' . $course['end_time'] ?></td>
                    </tr>
                <?php }
            } else { ?>
                <label><?php echo "You should be logged in!" ?></label>
            <?php } ?>
    </body>

    </html>
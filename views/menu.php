<?php
if (!isset($_SESSION)) {
    session_start();
}

function isLoggedInUser()
{
    return isset($_SESSION['email']);
}

function isLoggedInAdmin()
{
    return $_SESSION['status'] == 'ADMIN';
}

function getGreetingMessage()
{
    return $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../styles/menu.css">
    <title>FMI Parking</title>
</head>

<body>
    <header>
        <div class="navbar">
            <?php if (isLoggedInUser()) { ?>
                <a href="../views/main.php">FMI Parking</a>
            <?php } ?>
            <?php if (isLoggedInUser()) { ?>
                <?php if (isLoggedInAdmin()) { ?>
                    <a href="../views/schedule.php">
                        Semester schedule
                    </a>
                    <a href="../views/top-users-by-points.php">
                        Top 3 users
                    </a>
                    </li>
                <?php } else { ?>
                    <a class="nav-link" href="../views/parking-schema.php">View parking spots</a>
                    <a class="nav-link" href="../views/scancode.php">Scan code</a>
                <?php } ?>
                <a href="../views/view-all-users.php">View users</a>
                <a class="nav-link" href="../views/weekly-schedule.php">Weekly schedule</a>
                <a class="nav-link" href="../views/daily-schedule.php">Daily schedule</a>
            <?php } ?>

            <?php if (isLoggedInUser()) { ?>
                <div class="dropdown topnav-right">
                    <button class="dropdown-button-username"><?php echo getGreetingMessage(); ?>
                        <i>&#9660;</i>
                    </button>
                    <div class="dropdown-content">
                        <?php if (!isLoggedInAdmin()) { ?>
                            <a href="../views/my-profile.php">My profile</a>
                            <a href="../views/my-courses.php">My courses</a>
                        <?php } ?>
                        <a href="../views/logout.php">Logout</a>
                    </div>
                </div>
            <?php } else { ?>
                <div class="topnav-right">
                    <a href="../views/login.html">Login</a>
                    <a href="../views/signup.html">Sign up</a>
                </div>
            <?php } ?>
        </div>
        </div>
    </header>
</body>

</html>
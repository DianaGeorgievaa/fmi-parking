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

    <title>FMI Parking</title>

    <link rel="stylesheet" href="/styles/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <?php if (isLoggedInUser()) { ?>
                <a class="navbar-brand" href="/index.php">FMI Parking</a>
            <?php } ?>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav mr-auto">
                    <?php if (isLoggedInUser()) { ?>
                        <?php if (isLoggedInAdmin()) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../views/scheduler.html" id="admin" role="button" aria-haspopup="true" aria-expanded="false">
                                    Add semester schedule
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../views/top-users-by-points.php" id="admin" role="button" aria-haspopup="true" aria-expanded="false">
                                    Top 3 users
                                </a>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../views/parking-spot.php">View parking spots</a>
                            </li>
                        <?php } ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../views/view-all-users.php">View users</a>
                        </li>
                    <?php } ?>
                </ul>

                <ul class="navbar-nav">
                    <?php if (isLoggedInUser()) { ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php echo getGreetingMessage(); ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
                                <a class="dropdown-item" href="../views/my-profile.php">My profile</a>
                                <a class="dropdown-item" href="../views/logout.php">Logout</a>
                            </div>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../views/login.html">Login</a>
                        </li>
                        <li class="nav-item mr-1">
                            <a class="nav-link" href="../views/signup.html">Sign up</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </nav>
    </header>
    <main>
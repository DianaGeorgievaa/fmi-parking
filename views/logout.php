<!DOCTYPE html>
<html lang="en">

<head>
    <title>Logout</title>
</head>

<body>
    <?php
    session_start();
    session_unset();
    session_destroy();
    header("Location:" . '../views/index.php');    
    ?>
</body>

</html> 
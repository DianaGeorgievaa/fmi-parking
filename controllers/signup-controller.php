    <?php
    include '../configuration/db_config.php';

    define("EMAIL_FIELD", "email", true);
    define("FIRST_NAME_FIELD", "firstname", true);
    define("LAST_NAME_FIELD", "lastname", true);
    define("PASSWORD_FIELD", "password", true);
    define("STATUS_FIELD", "status", true);
    define("PHOTO_FIELD", "photo", true);

    $invalidFieldMessages = array(
        FIRST_NAME_FIELD => "Please insert correct firstname without any special signs.",
        LAST_NAME_FIELD => "Please insert correct lastname without any special signs.",
        EMAIL_FIELD => "Please insert correct email.",
        PASSWORD_FIELD => "Please insert password with min length 10 symbols.",
        STATUS_FIELD => "Please select the most suitable for you status."
    );

    if ($_POST) {
        validateFormFields();
        $email = $_POST[EMAIL_FIELD];
        $firstname = $_POST[FIRST_NAME_FIELD];
        $lastname = $_POST[LAST_NAME_FIELD];
        $password = $_POST[PASSWORD_FIELD];
        $status = strtoupper($_POST[STATUS_FIELD]);
        $points = 0;
        $photo = getUploadedPhoto();
        // TODO generate QR code

        if (!isExistingEmail($email)) {

            $connection = getDatabaseConnection();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $table = "users";
            $sql = "INSERT INTO $table (first_name, last_name, email, password, status, photo_name, points) 
                            VALUES (:firstname, :lastname, :email, :hashedPassword, :status, :photo, :points);";

            $preparedSql = $connection->prepare($sql) or die("Error description: " . $connnection->error);
            $preparedSql->bindParam(':firstname', $firstname);
            $preparedSql->bindParam(':lastname', $lastname);
            $preparedSql->bindParam(':email', $email);
            $preparedSql->bindParam(':hashedPassword', $hashedPassword);
            $preparedSql->bindParam(':status', $status);
            $preparedSql->bindParam(':photo', $photo);
            $preparedSql->bindParam(':points', $points);
            $preparedSql->execute() or die("Failed to save to DB!");

            echo ("Success! You are registered with $email with status: $status");
        } else {
            echo ("The email is already existing.");
        }
    }

    function validateFormFields()
    {
        $errors = array();
        validateFormField(FIRST_NAME_FIELD, '/^[A-Z][a-z]{1,20}/', $errors);
        validateFormField(LAST_NAME_FIELD, '/^[A-Z][a-z-]{3,25}/', $errors);
        validateFormField(EMAIL_FIELD, '/[^@]+@[^\.]+\..+/', $errors);
        validateFormField(PASSWORD_FIELD, '/^.{10,}/', $errors);
        // TODO validate status
        //TODO check the two passwords

        if (count($errors) !== 0) {
            foreach ($errors as $value) {
                echo "$value <br>";
            }
            die();
        }
    }

    function validateFormField($formField, $fieldPattern, &$errors)
    {
        $inputValue = $_POST["$formField"];
        if (!$inputValue) {
            $errors[$formField] = "The field $formField is required!";
        } elseif (!preg_match($fieldPattern, $inputValue)) {
            global $invalidFieldMessages;
            $errors[$formField] = $invalidFieldMessages[$formField];
        }
    }

    function isExistingEmail($email)
    {
        $connection = getDatabaseConnection();
        $table = "users";
        $sql = "SELECT * FROM  $table WHERE email = :email;";
        $preparedSql = $connection->prepare($sql);
        $preparedSql->bindParam(':email', $email);
        $preparedSql->execute() or die("Failed to check if email exist.");

        $result = $preparedSql->fetchAll();

        return count($result) !== 0;
    }

    function getUploadedPhoto()
    {
        $photosDirectory = "userPhotos/";
        if (!is_dir($photosDirectory)) {
            mkdir($photosDirectory, 0755);
        }

        $photoName = basename($_FILES[PHOTO_FIELD]['name']);
        $photoTarget = $photosDirectory . $photoName;
        $photoType = strtolower(pathinfo($photoTarget, PATHINFO_EXTENSION));;
        $photoUploadErrors = $_FILES[PHOTO_FIELD]["error"];

        checkUploadedPhoto($photoUploadErrors, $photoType);
        savePhoto($photoUploadErrors, $photoTarget);

        return basename($photoTarget);
    }

    function checkUploadedPhoto($photoUploadErrors, $photoType)
    {
        $photoFormats = array("png", "jpeg", "jpg");

        if ($photoUploadErrors == UPLOAD_ERR_NO_FILE) {
            die("The photo is required!");
        }
        if ($photoUploadErrors == UPLOAD_ERR_NO_TMP_DIR || $photoUploadErrors == UPLOAD_ERR_CANT_WRITE) {
            die("Error occured while uploading the image! Please try again later!");
        }
        if (!in_array($photoType, $photoFormats)) {
            die("The image is in wrong format! The allowed formats are: png, jpg and jpeg!");
        }
    }

    function savePhoto($photoUploadErrors, &$photoTarget)
    {
        if ($photoUploadErrors == UPLOAD_ERR_OK) {
            if (!move_uploaded_file($_FILES[PHOTO_FIELD]["tmp_name"], $photoTarget)) {
                die("Error occured while uploading the image! Please try again!");
            }
        }
    }

    ?>
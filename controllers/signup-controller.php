    <?php
    include '../utils/databaseQueriesUtils.php';
    include '../lib/phpqrcode-2010100721_1.1.4/phpqrcode/qrlib.php';

    define("QR_CODES_FOLDER_PATH", "../QRCodes/", true);
    define("USER_PHOTOS_FOLDER_PATH", "../userPhotos/", true);
    define("EMAIL_FIELD", "email", true);
    define("FIRST_NAME_FIELD", "firstname", true);
    define("LAST_NAME_FIELD", "lastname", true);
    define("PASSWORD_FIELD", "password", true);
    define("STATUS_FIELD", "status", true);
    define("PHOTO_FIELD", "photo", true);
    define("CONFIRMED_PASSWORD_FIELD", "confirmedpassword", true);
    define("CAR_NUMBER_FIELD", "carnumber", true);

    $invalidFieldMessages = array(
        FIRST_NAME_FIELD => "Please insert correct firstname without any special signs.",
        LAST_NAME_FIELD => "Please insert correct lastname without any special signs.",
        EMAIL_FIELD => "Please insert correct email.",
        PASSWORD_FIELD => "Please insert password with min length 10 symbols.",
        CONFIRMED_PASSWORD_FIELD => "The two passwords should be the same.",
        STATUS_FIELD => "Please select the most suitable for you status.",
        CAR_NUMBER_FIELD => "The car number should be in valid format - X(X)1111Y(Y)"
    );

    if ($_POST) {
        validateFormFields();
        $email = $_POST[EMAIL_FIELD];
        $firstname = $_POST[FIRST_NAME_FIELD];
        $lastname = $_POST[LAST_NAME_FIELD];
        $password = $_POST[PASSWORD_FIELD];
        $carNumber = $_POST[CAR_NUMBER_FIELD];
        $status = strtoupper($_POST[STATUS_FIELD]);
        $points = 0;
        $photo = getUploadedPhoto();

        createFolder(QR_CODES_FOLDER_PATH);
        $qrCodeNameValue = null;
        if ($status != 'ADMIN') {
            $qrCodeNameValue = $firstname . $lastname . '.png';
        }

        if (!DatabaseQueriesUtils::isExistingEmail($email)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $user = new User($firstname, $lastname, $email, $hashedPassword, $status, $photo, $points, $qrCodeNameValue, $carNumber);
            DatabaseQueriesUtils::saveUser($user);

            if ($status != 'ADMIN') {
                $qrFilePath = QR_CODES_FOLDER_PATH . $qrCodeNameValue;
                QRcode::png($qrCodeNameValue, $qrFilePath);
            }
            Utils::showMessage(MessageUtils::SUCCESSFUL_REGISTRATION_MESSAGE, true);
        } else {
            Utils::showMessage(MessageUtils::UNSUCCESSFUL_REGISTRATION_MESSAGE, false);
        }
    }

    function validateFormFields()
    {
        $errors = array();
        validateFormField(FIRST_NAME_FIELD, '/^[A-Z][a-z]{1,20}/', $errors);
        validateFormField(LAST_NAME_FIELD, '/^[A-Z][a-z-]{3,25}/', $errors);
        validateFormField(EMAIL_FIELD, '/[^@]+@[^\.]+\..+/', $errors);
        validateFormField(PASSWORD_FIELD, '/^.{10,}/', $errors);
        validateFormField(CAR_NUMBER_FIELD,'/^[A-Z]{1,2}[0-9]{4}[A-Z]{1,2}/', $errors);
        validatePasswords($errors);
        validateStatus($errors);

        if (count($errors) !== 0) {
            foreach ($errors as $value) {
                echo "$value <br>";
            }
            die();
        }
    }

    function validateStatus(&$errors)
    {
        $status = $_POST[STATUS_FIELD];
        if (!in_array(strtoupper($status), Utils::STATUS)) {
            global $invalidFieldMessages;
            $errors[STATUS_FIELD] = $invalidFieldMessages[STATUS_FIELD];
        }
    }

    function validatePasswords(&$errors)
    {
        $password = $_POST[PASSWORD_FIELD];
        $confirmedPassword = $_POST[CONFIRMED_PASSWORD_FIELD];
        if (!$confirmedPassword) {
            $errors[CONFIRMED_PASSWORD_FIELD] = "The field confirmed password is required!";
        } else if ($password != $confirmedPassword) {
            if (!array_key_exists(PASSWORD_FIELD, $errors)) {
                global $invalidFieldMessages;
                $errors[CONFIRMED_PASSWORD_FIELD] = $invalidFieldMessages[CONFIRMED_PASSWORD_FIELD];
            }
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

    function getUploadedPhoto()
    {
        createFolder(USER_PHOTOS_FOLDER_PATH);
        $info = pathinfo($_FILES[PHOTO_FIELD]['name']);
        $extension = $info['extension'];
        $photoName = uniqid() . "." . $extension;
        $photoTarget = USER_PHOTOS_FOLDER_PATH . $photoName;
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
            Utils::showMessage(MessageUtils::REQUIRED_PHOTO_MESSAGE, false);
        }
        if ($photoUploadErrors == UPLOAD_ERR_NO_TMP_DIR || $photoUploadErrors == UPLOAD_ERR_CANT_WRITE) {
            Utils::showMessage(MessageUtils::UPLOADING_PHOTO_ERROR_MESSAGE, false);
        }
        if (!in_array($photoType, $photoFormats)) {
            Utils::showMessage(MessageUtils::WRONG_FORMAT_PHOTO_ERROR_MESSAGE, false);
        }
    }

    function savePhoto($photoUploadErrors, &$photoTarget)
    {
        if ($photoUploadErrors == UPLOAD_ERR_OK) {
            if (!move_uploaded_file($_FILES[PHOTO_FIELD]["tmp_name"], $photoTarget)) {
                Utils::showMessage(MessageUtils::UPLOADING_PHOTO_ERROR_MESSAGE, false);
            }
        }
    }

    function createFolder($folderPath)
    {
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 777);
        }
    }
    ?>
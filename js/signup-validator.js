const FIRSTNAME_ID = "firstname";
const LASTNAME_ID = "lastname";
const EMAIL_ID = "email";
const PASSWORD_ID = "password";
const CONFIRM_PASSWORD_ID = "confirmedpassword";
const CAR_NUMBER_ID = "carnumber";

const INVALID_FIRSTNAME_ID = "firstnameError";
const INVALID_LASTNAME_ID = "lastnameError";
const INVALID_EMAIL_ID = "emailError";
const INVALID_PASSWORD_ID = "passwordError";
const INVALID_CONFIRMED_PASSWORD_ID = "confirmedPasswordError";
const INVALID_CAR_NUMBER_ID = "carNumberError";

const FIRSTNAME_REGEX = /^[A-Z][a-z]{1,20}/;
const LASTNAME_REGEX = /^[A-Z][a-z-]{3,25}/;
const EMAIL_REGEX = /[^@]+@[^.]+..+/;
const PASSWORD_REGEX = /^.{10,}/;
const CAR_NUMBER_REGEX = /^[A-Z]{1,2}[0-9]{4}[A-Z]{1,2}/;

const INVALID_FIRSTNAME_MESSAGE =
    "Please insert correct first name without any special signs!";
const INVALID_LASTNAME_MESSAGE =
    "Please insert correct last name without any special signs!";
const INVALID_EMAIL_MESSAGE = "Please insert valid email!";
const INVALID_PASSWORD_MESSAGE = "The password should be at least 10 symbols!";
const INVALID_CONFIRMED_PASSWORD_MESSAGE =
    "The two passwords should be the same!";
const INVALID_CAR_NUMBER_MESSAGE =
    "The car number should be in valid format - X(X)1111Y(Y)";

function isFormValid() {
    return (
        isFirstnameValid() &&
        isLastnameValid() &&
        isEmailValid() &&
        isPasswordValid() &&
        isConfirmPasswordValid() &&
        isCarNumberValid()
    );
}

function isFirstnameValid() {
    if (!document.getElementById(FIRSTNAME_ID).value.match(FIRSTNAME_REGEX)) {
        document.getElementById(
            INVALID_FIRSTNAME_ID
        ).innerHTML = INVALID_FIRSTNAME_MESSAGE;
        return false;
    }
    document.getElementById(INVALID_FIRSTNAME_ID).innerHTML = "";
    return true;
}

function isLastnameValid() {
    if (!document.getElementById(LASTNAME_ID).value.match(LASTNAME_REGEX)) {
        document.getElementById(
            INVALID_LASTNAME_ID
        ).innerHTML = INVALID_LASTNAME_MESSAGE;
        return false;
    }
    document.getElementById(INVALID_LASTNAME_ID).innerHTML = "";
    return true;
}

function isEmailValid() {
    if (!document.getElementById(EMAIL_ID).value.match(EMAIL_REGEX)) {
        document.getElementById(INVALID_EMAIL_ID).innerHTML = INVALID_EMAIL_MESSAGE;
        return false;
    }
    document.getElementById(INVALID_EMAIL_ID).innerHTML = "";
    return true;
}

function isPasswordValid() {
    if (!document.getElementById(PASSWORD_ID).value.match(PASSWORD_REGEX)) {
        document.getElementById(
            INVALID_PASSWORD_ID
        ).innerHTML = INVALID_PASSWORD_MESSAGE;
        return false;
    }
    document.getElementById(INVALID_PASSWORD_ID).innerHTML = "";
    return true;
}

function isConfirmPasswordValid() {
    let password = document.getElementById(PASSWORD_ID).value;
    let confirmedPassword = document.getElementById(CONFIRM_PASSWORD_ID).value;
    if (password !== confirmedPassword) {
        document.getElementById(
            INVALID_CONFIRMED_PASSWORD_ID
        ).innerHTML = INVALID_CONFIRMED_PASSWORD_MESSAGE;
        return false;
    }
    document.getElementById(INVALID_CONFIRMED_PASSWORD_ID).innerHTML = "";
    return true;
}

function isCarNumberValid() {
    if (!document.getElementById(CAR_NUMBER_ID).value.match(CAR_NUMBER_REGEX)) {
        document.getElementById(
            INVALID_CAR_NUMBER_ID
        ).innerHTML = INVALID_CAR_NUMBER_MESSAGE;
        return false;
    }
    document.getElementById(INVALID_CAR_NUMBER_ID).innerHTML = "";
    return true;
}

function checkIfIsAdminStatus() {
    let status = document.getElementById("status");
    let statusValue = status.options[status.selectedIndex].value;
    let carNumberField = document.getElementById("carnumber");
    if (statusValue === "admin") {
        carNumberField.classList.add('hide-car-number');
    } else {
        carNumberField.classList.remove('hide-car-number');
    }
}
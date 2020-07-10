<?php

class MessageUtils
{
    const SUCCESSFUL_REGISTRATION_MESSAGE = "You are registered! Now you can loggin!";
    const UNSUCCESSFUL_REGISTRATION_MESSAGE = "The email is already existing!";
    const INVALID_CREDENTIALS_MESSAGE = "Invalid credentials!";
    const REQUIRED_PHOTO_MESSAGE = "The photo is required!";
    const UPLOADING_PHOTO_ERROR_MESSAGE = "Error occured while uploading the photo! Please try again later!";
    const WRONG_FORMAT_PHOTO_ERROR_MESSAGE = "The photo is in wrong format! The allowed formats are: png, jpg and jpeg!";
    const NOT_SELECTED_SCHEDULER_ERROR_MESSAGE = "No scheduler choose. Please select the scheduler!";
    const UPLOADING_SCHEDULER_ERROR_MESSAGE = "You should be logged in as admin in order to upload scheduler!";
    const INVALID_SCHEDULER_FORMAT_ERROR_MESSAGE = "The uploaded scheduler is not in valid format!";
    const SUCCESSFUL_UPLOADED_SCHEDULER_MESSAGE = "The scheduler uploaded successfully!";
    const SUCCESSFUL_DELETED_SCHEDULER_MESSAGE = "The scheduler was deleted successfully!";
    const BLOCKED_ENTRANCE_MESSAGE = "Your entrance is blocked!";
    const NOT_FREE_PAKING_SPOTS_MESSAGE = "There are no free parking spots!";
    const NOT_ENOUGHT_PAKING_SPOTS_MESSAGE = "There are not enough parking spots!";
    const PARKING_ENTRANCE_WARNING_MESSAGE = "You don't have lectures in the next 30 minutes, but you are allowed to enter the parking! Notification will be send if you have to leave!";
    const NO_USERS_WITHOUT_LECTURES_ERROR_MESSAGE = "There are no users that use the parking without having lectures!";
    const INVALID_REQUEST_ERROR_MESSAGE = "Invalid request!";
    const NOT_ESTABLISHED_DATABASE_MESSAGE = "The connection with the database was not established!";

    const DATABASE_SAVE_INFORMATION_ERROR_MESSAGE = "Failed to save information in Database! Please try again later!";
    const DATABASE_GET_INFORMATION_ERROR_MESSAGE = "Failed to get information from Database! Please try again later!";
    const DATABASE_UPDATE_INFORMATION_ERROR_MESSAGE = "Failed to update information in Database! Please try again later!";
    const DATABASE_DELETE_INFORMATION_ERROR_MESSAGE = "Failed to delete information from Database! Please try again later!";

    const GET_USER_ERROR_MESSAGE = "Failed to get user!";
    const GET_USER_POINTS_ERROR_MESSAGE = "Failed to get user points!";
    const GET_USERS_ERROR_MESSAGE = "Failed to get users!";
    const GET_COURSES_ERROR_MESSAGE = "Failed to get courses!";
    const GET_VIEWERS_ERROR_MESSAGE = "Failed to get viewers!";
    const GET_TOP_USERS_ERROR_MESSAGE = "Failed to get top users!";
    const GET_USER_PARKING_INFO_ERROR_MESSAGE = "Failed to get user parking info!";
    const GET_USER_PARKING_SPOT_ERROR_MESSAGE = "Failed to get user parking spot!";
}

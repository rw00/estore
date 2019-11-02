<?php
require_once("php/classes/InfoManager.php");

if (isset($_POST["email"], $_POST["password"], $_POST["remember"])) {
    logIn();
    if ($_POST["remember"] == "remember") {
        rememberEmail();
    } else {
        forgetEmail();
    }
}

if (isset($_POST["user_email"], $_POST["confirm_email"])) {
    forgotPassword();
}

if (isset($_POST["user_email"], $_POST["user_password"], $_POST["first_name"], $_POST["last_name"], $_POST["phone_number"])) {
    signUpNew();
}

function logIn()
{
    $login = getUserId($_POST["email"], $_POST["password"]);
    if (is_int($login)) {
        session_start();
        $_SESSION["user_id"] = $login;
    } else {
        echo $login;
    }
}

function rememberEmail()
{
    $email = fixEmail($_POST["email"]);
    setcookie("email", $email);
}

function forgetEmail()
{
    setcookie("email", "", time() - 3600);
    unset($_COOKIE["email"]);
}

function forgotPassword()
{
    $email = fixEmail($_POST["user_email"]);
    if ($email != fixEmail($_POST["confirm_email"]) || !isValidEmail($email)) {
        echo "Please enter your email address correctly.";
    } else {
        echo resetPassword($email);
    }
}

function signUpNew()
{
    $signup = signUp($_POST);
    echo $signup;
}

?>

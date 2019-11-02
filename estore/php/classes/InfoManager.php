<?php
require_once("php/classes/Helper.php");

define("INVALID_EMAIL_ERR", "Invalid email address! Please enter your correct email address.");
define("INVALID_NAME_ERR", "Invalid name! Please enter your real name in English letters only.");
define("INVALID_PHONE_NUM_ERR", "Invalid phone number! Please enter your correct mobile phone number.");
define("INVALID_PASSWORD_ERR", "Invalid password! Please enter a password of at least " . MIN_PASS_LEN . " characters long without any spaces.");
define("INVALID_CONFIRM_PASS_ERR", "Invalid confirm password. The input password and confirm password don't match.");
define("INVALID_CURRENT_PASS_ERR", "Invalid current password. Please enter your password correctly to apply changes.");

define("INVALID_LOGIN_ERR", "Invalid login. Please enter your email and password correctly.");
define("INACTIVE_ACCOUNT_ERR", "Your account isn't activated yet!");

define("ACCOUNT_ALREADY_EXISTS_ERR", "This email already exists in our database.");
define("ACCOUNT_NOT_FOUND_ERR", "This email doesn't exist in our database. Click <a href='#' onclick='changeView(\"#loginbox\", \"#signupbox\"); changeView(\"#forgotpasswordbox\", \"#signupbox\");'>here to sign up</a> now for free!");

define("INVALID_INFO", "You submitted invalid information!");
define("UNKNOWN_ERR", "An unexpected error occurred.");

define("UPDATE_FAIL_ERR", UNKNOWN_ERR . " Could not update information!");

require_once("php/classes/UserManager.php");
require_once("php/classes/Mailing.php");
require_once("php/classes/Messaging.php");

$redirect_script = "<script>
function redirecting() {
    window.location = 'index';
}
setTimeout(redirecting, 5000);

document.writeln(' You will be redirected to home page in 5 seconds...');
</script>";

function getUserId($email, $password)
{
    $email = fixEmail($email);
    $password = trim($password);
    if (userExists($email) === true) {
        $u = getFullUser($email, $password); # full user
        if (!$u) { # wrong password
            return INVALID_LOGIN_ERR;
        } elseif ($u["activation_code"] !== "0") { # account not verified
            return INACTIVE_ACCOUNT_ERR .
                " Please check your inbox for an email from us. Or click the button below to request another verification link.<br />
            <form action='index' method='post' class='form-horizontal'>
                <input type='hidden' name='user_id' value='{$u["user_id"]}' />
                <div style='text-align: center'>
                <input type='submit' name='activate' value='Resend' class='btn btn-default' /></div>
            </form>";
        } else {
            return $u["user_id"];
        }
    } else { # user doesn't exist
        return ACCOUNT_NOT_FOUND_ERR;
    }
}


function signUp($user)
{
    $user = validateFixProfile($user);
    if (is_string($user)) { # error msg: invalid info
        return $user;
    }
    if (userExists($user["user_email"])) {
        return ACCOUNT_ALREADY_EXISTS_ERR;
    }
    $user["user_password"] = trim($user["user_password"]);
    $checkPassword = checkPassword($user["user_password"], $user["confirm_password"]);
    if (is_string($checkPassword)) { # invalid info
        return $checkPassword;
    }
    $user["user_password"] = hashPassword($user["user_password"]);
    $user_id = insertUser($user);
    if (isNum($user_id)) {
        $u = getFullUserById($user_id);
        if (sendActivationMail($u["user_email"], $user_id, $u["activation_code"]) === true) {
            return true;
        } else {
            return "Account successfully created but could not send you a verification email. Please request another one.";
        }
    } else {
        return "An error occured trying to create an account for you. Please try again later.";
    }
}

function updateProfile($user_id, $user)
{
    $user = validateFixProfile($user);
    if (is_string($user)) { # error msg
        return $user;
    }
    $user["current_password"] = hashPassword(trim($user["current_password"]));
    if (getFullUserById($user_id)["user_password"] !== $user["current_password"]) {
        return INVALID_CURRENT_PASS_ERR;
    }
    $user["user_password"] = trim($user["user_password"]);
    if ($user["user_password"] !== "") {
        $checkPassword = checkPassword($user["user_password"], $user["confirm_password"]);
        if (is_string($checkPassword)) {
            return $checkPassword;
        }
        $user["user_password"] = hashPassword($user["user_password"]);
    } else {
        $user["user_password"] = $user["current_password"];
    }
    $newEmail = false;
    if (getFullUserById($user_id)["user_email"] !== $user["user_email"]) { # user changed email
        if (userExists($user["user_email"]) === true) {
            return ACCOUNT_ALREADY_EXISTS_ERR . " Could not update your information.";
        }
        $newEmail = true;
    }
    if (updateUser($user_id, $user) === true) {
        if ($newEmail === true) {
            $code = updateCode($user_id);
            if (is_string($code)) {
                sendActivationMail($user["user_email"], $user_id, $code);
            }
        }
        return true;
    } else {
        return false;
    }
}

function validateFixProfile($user)
{ # with passwords
    extract($user, EXTR_PREFIX_SAME, "r");
    $user_email = fixEmail($user_email);
    if (!isValidEmail($user_email)) {
        return INVALID_EMAIL_ERR;
    }
    $phone_number = trim($phone_number);
    if ($phone_number != "" && !isValidPhoneNumber($phone_number)) { # phone number is not required
        return INVALID_PHONE_NUM_ERR;
    }

    $first_name = fixName($first_name);
    $last_name = fixName($last_name);
    if (!isValidName($first_name) || !isValidName($last_name)) {
        return INVALID_NAME_ERR;
    }

    $user["user_email"] = $user_email;
    $user["first_name"] = $first_name;
    $user["last_name"] = $last_name;
    $user["phone_number"] = $phone_number;
    return $user;
}

function deleteAccount($user_id, $password)
{
    $password = trim($password);
    if (checkPasswordById($user_id, $password) === true) {
        if (deleteUser($user_id) === true) {
            return true;
        } else {
            return UNKNOWN_ERR;
        }
    } else {
        return INVALID_CURRENT_PASS_ERR;
    }
}

function checkPasswordById($user_id, $password)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT user_password FROM user WHERE user_id = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $user_password = $stmt->execute();
    if ($stmt->fetch()["user_password"] === hashPassword($password)) {
        return true;
    } else {
        return false;
    }
}

function checkPassword($user_password, $confirm_password)
{
    $user_password = trim($user_password);
    if (!isValidPassword($user_password)) {
        return INVALID_PASSWORD_ERR;
    }

    $confirm_password = trim($confirm_password);
    if ($confirm_password != $user_password) {
        return INVALID_CONFIRM_PASS_ERR;
    }
    return true;
}

function updateCode($user_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("UPDATE user SET activation_code = :activation_code WHERE user_id = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $code = getCode();
    $stmt->bindValue(":activation_code", $code);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return $code;
    } else {
        return false;
    }
}

function accountActivation($user_id)
{
    $user = getFullUserById($user_id);
    return sendActivationMail($user["user_email"], $user_id, updateCode($user_id));
}

function activateAccount($id)
{
    $conn = getConn();
    $stmt = $conn->prepare("UPDATE user SET activation_code = '0' WHERE user_id = :id");
    $stmt->bindValue(":id", $id);
    return $stmt->execute();
}

function resetPassword($email)
{
    $email = fixEmail($email);
    if (!userExists($email)) {
        return ACCOUNT_NOT_FOUND_ERR;
    }
    $u = getUserIdByEmail($email);
    $user_id = $u["user_id"];
    $code = updateCode($user_id);
    return sendResetMail($email, $user_id, $code);
}

function changePassword($user_id, $user_password)
{
    $conn = getConn();
    $stmt = $conn->prepare("UPDATE user SET user_password = :user_password WHERE user_id = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->bindValue(":user_password", hashPassword($user_password));
    return $stmt->execute();
}

function checkCodeAndId($id, $code)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT user_id FROM user WHERE user_id = :id AND activation_code = :code");
    $stmt->bindValue(":id", $id);
    $stmt->bindValue(":code", $code);
    $stmt->execute();
    return $stmt->rowCount() == 1;
}

function isLoggedIn()
{
    session_start();
    if (isset($_SESSION["user_id"])) {
        return true;
    }
    return false;
}

function getContactsDetails($user_id)
{
    $contacts_ids = getContactsIds($user_id);
    $contacts_details = array();
    foreach ($contacts_ids as $oid) {
        $oid = $oid["other_user_id"];
        $contact_name = getNameById($oid);
        $contact = array("oid" => $oid, "oname" => $contact_name);
        $contacts_details[] = $contact;
    }
    return $contacts_details;
}

# improve func later
function escapeChars($str)
{
    return addslashes(trim($str));
}

# localhost/store/reset_password?id=x&code=abc
#  return   reset_password?id=x&code=abc
function getPageURI()
{
    $uri = $_SERVER["REQUEST_URI"];
    return substr($uri, strpos($uri, '/', strpos($uri, '/') + 1) + 1);
}

###################################################################################################
###################################################################################################
###################################################################################################

function remembered()
{
    if (isset($_COOKIE["email"])) {
        return "checked";
    }
}

function getRememberedEmail()
{
    if (remembered() == "checked") {
        return $_COOKIE["email"];
    } else {
        return "";
    }
}

?>

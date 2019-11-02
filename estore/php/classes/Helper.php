<?php
define("MIN_PASS_LEN", 8);

function fixName($name)
{
    return ucfirst(strtolower(trim($name)));
}

function fixEmail($email)
{
    return strtolower(trim($email));
}

function fixInitials($fname, $lname)
{
    return strtoupper(substr(trim($fname), 0, 1) . "" . substr(trim($lname), 0, 1));
}

function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function isValidName($name)
{
    return preg_match("/^[A-Za-z]+$/", $name);
}

function isValidPhoneNumber($phone_number)
{
    return preg_match("/^[\d]{8,}$/", $phone_number);
}

function isValidPassword($user_password)
{
    if (strlen($user_password) >= MIN_PASS_LEN && strpos($user_password, " ") === false) {
        return true;
    }
    return false;
}

# change func later
function isNum($s)
{
    return is_numeric($s);
}

function isValidCode($code)
{
    return $code != "0" && $code != "-1";
}

# in case we decide to change the algo
function getCode()
{
    return md5(uniqid(rand(), true));
}

function hashPassword($password)
{
    return sha1($password);
}

?>

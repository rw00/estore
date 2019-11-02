<?php
require_once("php/classes/db.php");
require_once("php/classes/Helper.php");

function getFullUser($email, $password)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT * FROM user WHERE user_email = :email AND user_password = :password");
    $stmt->bindValue(":email", $email);
    $stmt->bindValue(":password", hashPassword($password));
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return $stmt->fetch();
    } elseif ($stmt->rowCount() === 0) {
        return null;
    }
}

function getFullUserById($user_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT * FROM user WHERE user_id = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return $stmt->fetch();
    } elseif ($stmt->rowCount() === 0) {
        return null;
    }
}

###################################################################################################

function getUserById($user_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT first_name, last_name, user_email, phone_number FROM user WHERE user_id = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return $stmt->fetch();
    } elseif ($stmt->rowCount() === 0) {
        return null;
    }
}

function getUser($email, $password)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT first_name, last_name, user_email, phone_number FROM user WHERE user_email = :email AND user_password = :password");
    $stmt->bindValue(":email", $email);
    $stmt->bindValue(":password", hashPassword($password));
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return $stmt->fetch();
    } elseif ($stmt->rowCount() === 0) {
        return null;
    }
}

function getUserIdByEmail($email)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT user_id FROM user WHERE user_email = :email");
    $stmt->bindValue(":email", $email);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return $stmt->fetch();
    } elseif ($stmt->rowCount() === 0) {
        return null;
    }
}

###################################################################################################

function userExists($email)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT user_id FROM user WHERE user_email = :user_email");
    $stmt->bindValue(":user_email", $email);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return true;
    } elseif ($stmt->rowCount() === 0) {
        return false;
    }
}

function userExistsById($user_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT user_id FROM user WHERE user_id = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return true;
    } elseif ($stmt->rowCount() === 0) {
        return false;
    }
}

function getNameById($user_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT first_name, last_name FROM user WHERE user_id = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        $info = $stmt->fetch();
        return $info["first_name"] . " " . $info["last_name"];
    } else {
        return "";
    }
}

function getInitialsById($user_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT first_name, last_name FROM user WHERE user_id = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        $info = $stmt->fetch();
        return fixInitials($info["first_name"], $info["last_name"]);
    } else {
        return "";
    }
}

function activeUser($user_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT activation_code FROM user WHERE user_id = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        $info = $stmt->fetch();
        if ($info["activation_code"] === "0") {
            return true;
        } else {
            return false;
        }
    } elseif ($stmt->rowCount() === 0) {
        return false;
    }
}

###################################################################################################

function insertUser($user)
{
    $conn = getConn();
    $stmt = $conn->prepare("INSERT INTO user (user_email, user_password, first_name, last_name, phone_number, activation_code) VALUES (:user_email, :user_password, :first_name, :last_name, :phone_number, :activation_code)");
    $stmt->bindValue(":user_email", $user["user_email"]);
    $stmt->bindValue(":user_password", $user["user_password"]);
    $stmt->bindValue(":first_name", $user["first_name"]);
    $stmt->bindValue(":last_name", $user["last_name"]);
    $stmt->bindValue(":phone_number", $user["phone_number"]);
    $stmt->bindValue(":activation_code", getCode());
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return $conn->lastInsertId();
    } else {
        return false;
    }
}

function updateUser($user_id, $user)
{
    $conn = getConn();
    $stmt = $conn->prepare("UPDATE user SET user_email = :user_email, user_password = :user_password, first_name = :first_name, last_name = :last_name, phone_number = :phone_number WHERE user_id = :user_id");
    $stmt->bindValue(":user_email", $user["user_email"]);
    $stmt->bindValue(":user_password", $user["user_password"]);
    $stmt->bindValue(":first_name", $user["first_name"]);
    $stmt->bindValue(":last_name", $user["last_name"]);
    $stmt->bindValue(":phone_number", $user["phone_number"]);
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return true;
    } else {
        return false;
    }
}

function deleteUser($user_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("DELETE FROM user WHERE user_id = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    if ($stmt->rowCount() === 1) {
        return true;
    } else {
        return false;
    }
}

?>

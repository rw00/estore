<?php
require_once("php/classes/db.php");

function sendMessage($user_id, $other_user_id, $msg)
{
    $message_id = insertMsg($msg);
    if (is_int($message_id)) {
        insertUserMessage($message_id, $user_id, $other_user_id, "Sent");
        insertUserMessage($message_id, $other_user_id, $user_id, "Inbox");
    }
}

function getMsgsWithOtherUser($user_id, $other_user_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT * FROM user_messages NATURAL JOIN message WHERE user_id = :user_id AND other_user_id = :other_user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->bindValue(":other_user_id", $other_user_id);
    $stmt->execute();
    return $stmt->fetchAll();
}

function setReadByOtherId($user_id, $other_user_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("UPDATE user_messages SET unread = :unread WHERE user_id = :user_id AND other_user_id = :other_user_id AND folder = 'Inbox'");
    $stmt->bindValue(":unread", 0);
    $stmt->bindValue(":user_id", $user_id);
    $stmt->bindValue(":other_user_id", $other_user_id);
    $stmt->execute();
}

function getContactsIds($user_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT DISTINCT other_user_id FROM user_messages WHERE user_id = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    return $stmt->fetchAll();
}

function deleteMsg($user_id, $message_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("UPDATE user_messages SET deleted = 'Deleted' WHERE user_id = :user_id AND message_id = :message_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->bindValue(":message_id", $message_id);
    $stmt->execute();

    $stmt = $conn->prepare("SELECT COUNT(message_id) FROM user_messages WHERE message_id = :message_id AND deleted = 'Deleted'");
    $stmt->bindValue(":message_id", $message_id);
    $stmt->execute();
    $r = $stmt->fetch();
    if ($r["COUNT(message_id)"] === 2) {
        $stmt = $conn->prepare("DELETE FROM message WHERE message_id = :message_id");
        $stmt->bindValue(":message_id", $message_id);
        $stmt->execute();
    }
}

function setRead($message_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("UPDATE user_messages SET unread = :unread WHERE message_id = :message_id AND folder = 'Inbox'");
    $stmt->bindValue(":unread", false);
    $stmt->bindValue(":message_id", $message_id);
    $stmt->execute();
}

function getMsgText($message_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT msg_text FROM user_messages userMsg NATURAL JOIN message WHERE userMsg.message_id = :message_id");
    $stmt->bindValue(":message_id", $message_id);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result["msg_text"];
}

function getInboxMsgs($user_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT msg.message_id, msg_text, first_name, last_name, userMsg.other_user_id, unread FROM message msg JOIN user_messages userMsg JOIN user u
    ON msg.message_id = userMsg.message_id AND u.user_id = userMsg.other_user_id WHERE userMsg.deleted = 'None' AND userMsg.folder = 'Inbox' AND userMsg.user_id = :user_id ORDER BY unread DESC, msg_date DESC");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    $info = $stmt->fetchAll();
    $msgs = array();
    foreach ($info as $i) {
        $msgs[] = array("msg_id" => $i["message_id"], "first_name" => $i["first_name"], "last_name" => $i["last_name"], "msg_text" => $i["msg_text"], "other_user_id" => $i["other_user_id"], "unread" => $i["unread"]);
    }
    return $msgs;
}

function getSentMsgs($user_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT msg.message_id, msg_text, first_name, last_name, other_user_id FROM message msg JOIN user_messages userMsg JOIN user u
    ON msg.message_id = userMsg.message_id AND u.user_id = userMsg.other_user_id WHERE userMsg.deleted = 'None' AND userMsg.folder = 'Sent' AND userMsg.user_id = :user_id ORDER BY msg_date DESC");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    $info = $stmt->fetchAll();
    $msgs = array();
    foreach ($info as $i) {
        $msgs[] = array("message_id" => $i["message_id"], "first_name" => $i["first_name"], "last_name" => $i["last_name"], "msg_text" => $i["msg_text"], "other_user_id" => $i["other_user_id"]);
    }
    return $msgs;
}

function getNumberOfUnreadMsgs($user_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT COUNT(msg.message_id) FROM message msg JOIN user_messages userMsg 
    ON msg.message_id = userMsg.message_id WHERE unread = :unread AND userMsg.deleted = 'None' AND userMsg.folder = 'Inbox' AND userMsg.user_id = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->bindValue(":unread", true);
    $stmt->execute();
    $info = $stmt->fetch();
    $numberOfUnreadMsgs = $info["COUNT(msg.message_id)"];
    return $numberOfUnreadMsgs;
}

function insertMsg($msg)
{
    $conn = getConn();
    $stmt = $conn->prepare("INSERT INTO message (msg_text, msg_date) VALUES (:msg, NOW())");
    $stmt->bindValue(":msg", $msg);
    $stmt->execute();
    $message_id = $conn->lastInsertId();
    return (int)$message_id;
}

function insertUserMessage($message_id, $uId, $oId, $folder)
{
    $conn = getConn();
    $stmt = $conn->prepare("INSERT INTO user_messages (message_id, user_id, other_user_id, folder) VALUES (:message_id, :uId, :oId, :folder)");
    $stmt->bindValue(":message_id", $message_id);
    $stmt->bindValue(":uId", $uId);
    $stmt->bindValue(":oId", $oId);
    $stmt->bindValue(":folder", $folder);
    $stmt->execute();
}

?>

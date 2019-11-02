<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8"/>
    <title>Messages</title>
    <link rel="icon" href="img/favicon.ico"/>
    <link href="css/default.css" rel="stylesheet"/>
    <script src="js/jquery.min.js"></script>
    <script src="js/main.js"></script>
</head>

<body>
<?php
require_once("php/classes/InfoManager.php");
if (!isLoggedIn()) {
    header("Location: index");
    exit;
}
$user_id = $_SESSION["user_id"];
require_once("php/classes/Messaging.php");
$oid = isset($_POST["oid"]) ? $_POST["oid"] : null; # change to null
$contacts = getContactsDetails($user_id);
if ($oid == null && $contacts != null) {
    $oid = $contacts[0]["oid"];
}
if ($oid == null || userExistsById($oid) !== true) {
    header("Location: home");
    exit;
}
if (isset($_POST["send"])) {
    if (isset($_POST["msg"])) {
        $msg = $_POST["msg"];
        if (strlen(trim($msg)) > 0) {
            sendMessage($user_id, $oid, $msg);
            header("Location: messages");
        }
    }
}
$msgs = json_encode(getMsgsWithOtherUser($user_id, $oid));
$msgs = escapeChars($msgs);

$contacts = json_encode($contacts);
$contacts = escapeChars($contacts);

setReadByOtherId($user_id, $oid);
?>
<script>
    $(function () {
        displayMessages('<?= $msgs ?>');
        displayContacts('<?= $contacts ?>');
    });
</script>
<div class="wrapper">
    <div class="header">
        <div class="header_top">
            <div class="logo">
                <a href="home"><img src="img/logo.png" alt="eStore"/></a>
            </div>
            <div class="cart">
                <h3>Welcome to our Online Store!</h3>
            </div>
            <div class="clear"></div>
        </div>
        <div class="header_bottom">
            <div class="menu">
                <ul>
                    <li><a href="home">Home</a></li>
                    <li class="active"><a href="messages">Messages</a></li>
                    <li><a href="add_item">New Item</a></li>
                    <li><a href="profile">Profile</a></li>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="main">
        <div class="content">
            <div class="window-title-div">
                <div class="window-title">
                    <h2>MESSAGES</h2>
                </div>
                <div class="clear"></div>
            </div>
            <div id="contacts-area" class="window-widget widget-contact">
                <div class="window-widget contact">
                    <div class="contact-circle"></div>
                    <h4>RW</h4>
                </div>
            </div>
            <div class="window-widget widget-chat">
                <div class="title">
                    <h4 id="contact-name"><?= getNameById($oid); ?></h4>
                </div>
                <div id="msgs-area" class="messages">
                    <p class="msg me"></p>
                    <p class="msg other"></p>
                </div>
                <div class="typingarea">
                    <form action="messages" method="post">
                        <input id="message" class="message-box" name="msg" type="text" placeholder="Type Your Message Here"/>
                        <input id="sendbtn" class="text-box-btn" type="submit" name="send" value="SEND"/>
                        <input type="hidden" name="oid" value="<?= $oid ?>"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once("views/footer.html"); ?>
</body>

</html>

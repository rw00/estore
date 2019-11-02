<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8"/>
    <title>Account Activation</title>
    <link rel="icon" href="img/favicon.ico"/>
    <link href="css/default.css" rel="stylesheet"/>
</head>

<body>
<div class="redirection_page">
    <?php
    require_once("php/classes/InfoManager.php");
    if (isset($_GET["id"], $_GET["code"])) {
        if (checkCodeAndId($_GET["id"], $_GET["code"])) {
            activateAccount($_GET["id"]);
            ?>
            <h2 class="redirection_message"><?= "Your account is now successfully activated!" . $redirect_script ?></h2>
            <?php
        } else {
            ?>
            <h2 class="redirection_message"><?= "You submitted invalid information!" ?></h2>
            <?php
        }
    } ?>
</div>
</body>

</html>

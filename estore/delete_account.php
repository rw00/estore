<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8"/>
    <title>Delete Account</title>
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
?>
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
                    <li><a href="messages">Messages</a></li>
                    <li><a href="add_item">New Item</a></li>
                    <li class="active"><a href="profile">Profile</a></li>
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
                    <h2>Delete Account</h2>
                </div>
                <div class="clear"></div>
            </div>
            <div class="window widget">
                <p>
                    <?php
                    if (isset($_POST["delete_account"], $_POST["password"])) {
                        $del = deleteAccount($_SESSION["user_id"], $_POST["password"]);
                        if ($del === true) {
                            header("Location: logout");
                            exit;
                        } else {
                            echo $del;
                        }
                    }
                    ?>
                </p>
                <form onsubmit="return confirmDeleteAccount();" action="delete_account" method="post">
                    <div>
                        <div class="left">
                            <h4>Password: </h4>
                        </div>
                        <div class="right">
                            <input class="text-box" type="password" name="password" placeholder="Password" required="required"/>
                        </div>
                    </div>
                    <br/>
                    <div class="clear"></div>
                    <div>
                        <h4>This action is irreversible. All your data will be permanently deleted from our server. Press "Delete Account" to proceed.</h4>
                        <div class="clear"></div>
                        <div class="center">

                            <input class="text-box-btn btn" type="submit" name="delete_account" value="Delete Account"/>
                        </div>
                    </div>
                </form>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>

<?php require_once("views/footer.html"); ?>
</body>

</html>

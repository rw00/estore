<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8"/>
    <title>Profile</title>
    <link rel="icon" href="img/favicon.ico"/>
    <link href="css/default.css" rel="stylesheet"/>
    <script src="js/jquery.min.js"></script>
</head>

<body>
<?php require_once("php/classes/InfoManager.php");
if (!isLoggedIn()) {
    header("Location: index");
    exit;
}
$user_id = $_SESSION["user_id"];
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
                    <h2>Profile Info</h2>
                </div>
                <div class="clear"></div>
            </div>
            <div class="window widget">
                <p><?php
                    if (isset($_POST["first_name"], $_POST["last_name"], $_POST["user_email"], $_POST["phone_number"], $_POST["user_password"], $_POST["confirm_password"], $_POST["current_password"])) {
                        $update_profile = updateProfile($user_id, $_POST);
                        if (is_string($update_profile)) { # invalid info
                            echo $update_profile;
                        } else {
                            echo "Successfully updated your profile information!";
                            header("Refresh: 5; url=profile");
                        }
                    }

                    $user = getUserById($user_id);
                    ?></p>
                <br/>
                <div class="circle">
                    <?= getInitialsById($user_id); ?>
                </div>
                <form action="profile" method="post">
                    <div>
                        <div class="left">
                            <h4>First Name*: </h4>
                        </div>
                        <div class="right">
                            <input class="text-box" type="text" name="first_name" placeholder="First Name" value="<?= $user["first_name"] ?>"/>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div>
                        <div class="left">
                            <h4>Last Name*: </h4>
                        </div>
                        <div class="right">
                            <input class="text-box" type="text" name="last_name" value="<?= $user["last_name"] ?>" placeholder="Last Name"/>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div>
                        <div class="left">
                            <h4>Email*: </h4>
                        </div>
                        <div class="right">
                            <input class="text-box" type="email" name="user_email" value="<?= $user["user_email"] ?>" placeholder="Email"/>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div>
                        <div class="left">
                            <h4>Phone Number: </h4>
                        </div>
                        <div class="right">
                            <input class="text-box" type="text" name="phone_number" value="<?= $user["phone_number"] ?>" placeholder="Phone Number"/>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div>
                        <div class="left">
                            <h4>New Password: </h4>
                        </div>
                        <div class="right">
                            <input class="text-box" type="password" name="user_password" placeholder="New Password"/>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div>
                        <div class="left">
                            <h4>Confirm Password: </h4>
                        </div>
                        <div class="right">
                            <input class="text-box" type="password" name="confirm_password" placeholder="Confirm New Password"/>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <br/>
                    <div>
                        <div class="left">
                            <h4>Current Password*: </h4>
                        </div>
                        <div class="right">
                            <input class="text-box" type="password" name="current_password" placeholder="Current Password"/>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div>
                        <div class="center">
                            <input class="text-box-btn btn" type="submit" value="Submit Changes"/>
                        </div>
                    </div>
                </form>
                <br/>
                <div class="clear"></div>
                <p>If you no longer want to use our service, click the link below to proceed in deleting your profile:</p>
                <div class="center">
                    <a href="delete_account" class="btn-link">Delete Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once("views/footer.html"); ?>
</body>

</html>

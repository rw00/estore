<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8"/>
    <title>Buy Item</title>
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
if (!isset($_GET["pid"])) {
    header("Location: home");
    exit;
}
require_once("php/classes/ItemManager.php");
$pid = $_GET["pid"];
$item_info = getOneItem($pid);
if ($item_info === null) {
    header("Location: home");
    exit;
}
if ($_SESSION["user_id"] === $item_info["user_id"]) {
    header("Location: my_items");
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
                    <h2>Item Info</h2>
                </div>
                <div class="clear"></div>
            </div>
            <div class="window widget">
                <div id="one-item-div" class="grid">
                    <h3><?php echo $item_info["brand"] . " " . $item_info["type"] ?></h3>
                    <h4><?= $item_info["model"] ?></h4>
                    <h4><?= $item_info["first_name"] ?></h4>
                    <img src="<?= $item_info['item_img'] ?>" alt="Item Image"/>
                    <h4><?= $item_info["description"] ?></h4>
                    <div class="price-details">
                        <div class="price-number">
                            <p>$
                                <?= $item_info["price"] ?>
                            </p>
                        </div>
                        <div>
                            <form action="messages" method="post">
                                <input class="btn1" type="submit" value="MESSAGE"/>
                                <input type="hidden" value="<?= $item_info['user_id'] ?>" name="oid"/>
                            </form>
                        </div>
                        <div>
                            <?php $phone_num = $item_info['phone_number'];
                            if ($phone_num !== '') { ?>
                                <a class="btn2" href="tel:<?= $phone_num ?>">
                                    <?= $phone_num ?>
                                </a>
                            <?php } ?>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once("views/footer.html"); ?>
</body>

</html>

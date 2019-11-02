<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8"/>
    <title>My Items</title>
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
require_once("php/classes/ItemManager.php");
$user_id = $_SESSION["user_id"];
if (isset($_POST["pid"])) { # delete item
    deleteItem($_POST["pid"]);
    header("Location: my_items");
    exit;
}
$my_items = getMyItems($user_id);
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
                    <h2>My Items</h2>
                </div>
                <div class="clear"></div>
            </div>
            <div id="my-items" class="window widget">
                <?php
                if ($my_items === null): ?>
                    <p>No Items Found! Click <a class='btn-link' href='add_item'>here</a> to add a new item for sale!</p>
                <?php else:
                    foreach ($my_items as $item): ?>
                        <div class="grid_1_of_4 images_1_of_4">
                            <h3><?= $item["brand"] ?> <?= $item["type"] ?></h3>
                            <h4><?= $item["model"] ?></h4>
                            <img src="<?= $item['item_img'] ?>" alt="Item Image"/>
                            <h4 class="product-info"><?= $item["description"] ?></h4>
                            <div class="price-details">
                                <div class="price-number">
                                    <p>
                                        $<?= $item["price"] ?>
                                    </p>
                                </div>
                                <div>
                                    <form method="post" action="my_items">
                                        <input type="hidden" name="pid" value="<?= $item['item_id'] ?>"/>
                                        <input type="submit" value="DELETE" class="btn1"/>
                                    </form>
                                    <form method="get" action="edit_item">
                                        <input type="hidden" name="pid" value="<?= $item['item_id'] ?>"/>
                                        <input type="submit" value="EDIT" class="btn2"/>
                                    </form>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    <?php endforeach;
                endif;
                ?>
            </div>
        </div>
    </div>
</div>

<?php require_once("views/footer.html"); ?>
</body>

</html>

<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8"/>
    <title>eStore | Buy and Sell eDevices</title>
    <link rel="icon" href="img/favicon.ico"/>
    <link href="css/default.css" rel="stylesheet"/>
    <script src="js/jquery.min.js"></script>
    <script src="js/main.js"></script>
</head>

<body>
<?php require_once("php/classes/InfoManager.php");
if (!isLoggedIn()) {
    header("Location: index");
    exit;
}

require_once("php/classes/ItemManager.php");
$title = "FEATURED";
$by = "FEATURED";
if (isset($_GET["search"])) {
    $title = trim($_GET["search"]);
    $by = "SEARCH";
} else if (isset($_GET["brand"])) {
    $title = $_GET["brand"];
    if (checkInfoBy("brand", $title) !== true) {
        header("Location: home");
        exit;
    }
    $by = "BRAND";
} else if (isset($_GET["category"])) {
    $title = $_GET["category"];
    if (checkInfoBy("type", $title) !== true) {
        header("Location: home");
        exit;
    }
    $by = "TYPE";
}

$user_id = $_SESSION["user_id"];
$items = null;
if ($by === "SEARCH") {
    $items = searchItems($title);
    $title = "SEARCH RESULTS FOR: " . $title;
} else if ($by === "FEATURED") {
    $items = getRecentAds($user_id);
    # $items = json_decode($items, true);
} else if ($by === "BRAND") {
    $items = getItemsBy("brand", $title);
    # $items = json_decode($items, true);
} elseif ($by === "TYPE") {
    $items = getItemsBy("type", $title);
    # $items = json_decode($items, true);
}

require_once("php/classes/Messaging.php");
# var_dump($items);
$title = strtoupper($title);
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
                    <li class="active"><a href="home">Home</a></li>
                    <li><a href="messages">Messages <span class="badge"><?= getNumberOfUnreadMsgs($user_id) ?></span></a></li>
                    <li><a href="add_item">New Item</a></li>
                    <li><a href="profile">Profile</a></li>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="search_box">
                <form action="home" method="get">
                    <input type="text" name="search" placeholder="Search"/>
                    <input type="submit" value=""/>
                </form>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="main">
        <div class="content">
            <div class="RR">
                <div class="categories">
                    <h3>Categories</h3>
                    <ul id="categories">
                        <li><a href="home">FEATURED</a></li>
                        <?php
                        $types = getTypes();
                        foreach ($types as $type) : ?>
                            <li><a><?= $type ?></a></li>
                        <?php
                        endforeach; ?>
                    </ul>
                </div>
                <br/>
                <div class="categories">
                    <h3>Brands</h3>
                    <ul id="brands">
                        <?php
                        $brands = getBrands();
                        foreach ($brands as $brand) : ?>
                            <li><a><?= $brand ?></a></li>
                        <?php
                        endforeach; ?>
                    </ul>
                </div>
                <br/>
            </div>
            <div class="LL">
                <div class="content_top">
                    <div class="heading">
                        <h2><?= $title ?></h2>
                    </div>
                    <div class="clear"></div>
                </div>
                <div id="items-area" class="section group">
                    <?php
                    if ($items != null) {
                        foreach ($items as $item) : ?>
                            <div class="grid_1_of_4 images_1_of_4">
                                <h3><?php echo $item["brand"] . " " . $item["type"]; ?></h3>
                                <h4><?= $item["model"] ?></h4>
                                <img src="<?= $item['item_img'] ?>" alt="Item Image"/>
                                <h4 class="product-info"><?= $item["description"] ?></h4>
                                <div class="price-details">
                                    <div class="price-number">
                                        <p>$
                                            <?= $item["price"] ?>
                                        </p>
                                    </div>
                                    <div class="add-cart">
                                        <form action="buy_item" method="get">
                                            <input type="hidden" name="pid" value="<?= $item['item_id'] ?>"/>
                                            <input class="btn" type="submit" value="BUY"/>
                                        </form>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        <?php
                        endforeach;
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once("views/footer.html"); ?>
</body>

</html>

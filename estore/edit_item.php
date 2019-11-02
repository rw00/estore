<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8"/>
    <title>Edit Item</title>
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
$user_id = $_SESSION["user_id"];
require_once("php/classes/ItemManager.php");
$pid = $_GET["pid"];
if (!isMyItem($user_id, $pid)) {
    header("Location: my_items");
    exit;
}

require_once("php/classes/ItemManager.php");
if (isset($_POST["brand"], $_POST["type"], $_POST["model"], $_POST["price"], $_POST["description"], $_FILES["item_img"])) {
    $item = editItem($pid, $_POST);
    if ($item !== true) {
        echo $item;
    } else {
        header("Location: edit_item?pid=" . $pid);
        exit;
    }
}

$item_info = getOneItem($pid);
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
                    <h2>Edit Item</h2>
                </div>
                <div class="clear"></div>
            </div>
            <div class="window widget">
                <h3>Product Specifications</h3>
                <br/>

                <form action="edit_item?pid=<?= $pid ?>" method="post" enctype="multipart/form-data">
                    <div>
                        <div class="left">
                            <h4>Brand: </h4>
                        </div>
                        <div class="right">
                            <select name="brand" class="select-menu" required="required">
                                <?php
                                $brands = getBrands();
                                foreach ($brands as $brand) : ?>
                                    <option value="<?= $brand ?>" <?php if ($brand === $item_info["brand"]) {
                                        echo "selected";
                                    } ?>><?= $brand ?></option>
                                <?php
                                endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div>
                        <div class="left">
                            <h4>Type: </h4>
                        </div>
                        <div class="right">
                            <select name="type" class="select-menu" required="required">
                                <?php
                                $types = getTypes();
                                foreach ($types as $type) : ?>
                                    <option value="<?= $type ?>" <?php if ($type === $item_info["type"]) {
                                        echo "selected";
                                    } ?>><?= $type ?></option>
                                <?php
                                endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div>
                        <div class="left">
                            <h4>Model: </h4>
                        </div>
                        <div class="right">
                            <input class="text-box" type="text" name="model" value="<?= $item_info['model'] ?>" placeholder="Model Name" required="required"/>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div>
                        <div class="left">
                            <h4>Description: </h4>
                        </div>
                        <div class="right">
                            <textarea class="text-area" name="description" placeholder="Condition, Specifications, Quality, etc..." required="required"><?= $item_info['description'] ?></textarea>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div>
                        <div class="left">
                            <h4>Price: </h4>
                        </div>
                        <div class="right">
                            <input class="text-box" type="number" name="price" value="<?= $item_info['price'] ?>" placeholder="Price in USD" required="required"/>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div>
                        <div class="left">
                            <h4>Photo: </h4>
                        </div>
                        <div class="right">
                            <label class="text-box-btn upload-btn">
                                <input type="file" name="item_img" id="file-btn" required="required"/> Upload Photo
                            </label>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div>
                        <div class="center">
                            <input class="btn text-box-btn" type="submit" value="Update Item Info"/>
                        </div>
                    </div>
                    <div class="clear"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once("views/footer.html"); ?>
</body>

</html>

<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8"/>
    <title>Add Item</title>
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
                    <li class="active"><a href="add_item">New Item</a></li>
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
                    <h2>Add New Item</h2>
                </div>
                <div class="clear"></div>
            </div>
            <div class="window widget">
                <h3>Product Specifications</h3>
                <br/>
                <?php
                require_once("php/classes/ItemManager.php");
                if (isset($_POST["brand"], $_POST["type"], $_POST["model"], $_POST["price"], $_POST["description"], $_FILES["item_img"])) {
                    $item = addItem($_SESSION["user_id"], $_POST);
                    if ($item !== true) {
                        echo $item;
                    } else {
                        echo "New item was successfully added to your list.";
                        header("Refresh: 5; url=add_item");
                    }
                }
                ?>

                <form action="add_item" method="post" enctype="multipart/form-data">
                    <div>
                        <div class="left">
                            <h4>Brand: </h4>
                        </div>
                        <div class="right">
                            <select name="brand" class="select-menu" required="required">
                                <option value="" selected="selected">--Select a Brand--</option>
                                <?php
                                $brands = getBrands();
                                foreach ($brands as $brand) : ?>
                                    <option value="<?= $brand ?>"><?= $brand ?></option>
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
                                <option value="" selected="selected">--Select a Device Type--</option>
                                <?php
                                $types = getTypes();
                                foreach ($types as $type) : ?>
                                    <option value="<?= $type ?>"><?= $type ?></option>
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
                            <input class="text-box" type="text" name="model" placeholder="Model Name" required="required"/>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div>
                        <div class="left">
                            <h4>Description: </h4>
                        </div>
                        <div class="right">
                            <textarea class="text-area" name="description" placeholder="Condition, Specifications, Quality, etc..." required="required"></textarea>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div>
                        <div class="left">
                            <h4>Price: </h4>
                        </div>
                        <div class="right">
                            <input class="text-box" type="number" name="price" placeholder="Price in USD" required="required"/>
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
                            <input class="btn text-box-btn" type="submit" value="Add Item"/>
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

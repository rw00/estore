<?php
require_once("php/classes/db.php");
define("ITEM_IMG_PATH", "user_data/item_img/");
define("DEFAULT_ITEM_IMG_PATH", ITEM_IMG_PATH . "default_img.png");
define("INVALID_ITEM_INFO", "Please select OTHER.");

function addItem($user_id, $item)
{
    $item = validateItem($item);
    if (is_string($item)) { # error msg
        return $item;
    }
    if (!is_uploaded_file($_FILES["item_img"]["tmp_name"])) {
        return "Could not upload file! Please try again later.";
    }
    if (!isValidImg()) {
        return "Invalid image file. We only allow jpg, png and gif files! Sorry!";
    }
    $item_id = insertItem($user_id, $item);
    $item_img = getImgPath($item_id);
    updateItemImg($item_id, $item_img);
    moveImgFile($item_img);
    return true;
}

function editItem($item_id, $item)
{
    $item = validateItem($item);
    if (is_string($item)) { # error msg
        return $item;
    }
    if (!is_uploaded_file($_FILES["item_img"]["tmp_name"])) {
        return "Could not upload file! Please try again later.";
    }
    if (!isValidImg()) {
        return "Invalid image file. We only allow jpg, png and gif files! Sorry!";
    }
    updateItem($item_id, $item);
    $item_img = getImgPath($item_id);
    updateItemImg($item_id, $item_img);
    moveImgFile($item_img);
    return true;
}

function searchItems($q)
{
    $conn = getConn();
    $q = $conn->quote("%{$q}%");
    $stmt = $conn->prepare("SELECT * FROM item NATURAL JOIN brands NATURAL JOIN types WHERE CONCAT(brand, '', type, '', model) LIKE {$q}");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getImgPath($item_id)
{
    return ITEM_IMG_PATH . $item_id . ".png";
}

function moveImgFile($item_img_path)
{
    move_uploaded_file($_FILES["item_img"]["tmp_name"], $item_img_path);
}

# improve function later..
function isValidImg()
{
    $f = $_FILES["item_img"]["name"];
    $ext = strtolower(substr($f, strrpos($f, ".") + 1));
    return $ext === "jpg" || $ext === "png" || $ext === "gif" || $ext === "jpeg";
    # $_FILES["item_img"]["name"];
    # return true;
}

function validateItem($item)
{
    extract($item);
    $brand = fixStr($brand);
    if (checkInfoBy("brand", $brand) !== true) {
        return "This brand does not exist in our system. " . INVALID_ITEM_INFO;
    }
    $type = fixStr($type);
    if (checkInfoBy("type", $type) !== true) {
        return "This type does not exist in  our system. " . INVALID_ITEM_INFO;
    }
    $model = fixStr($model);
    if ($model === "") {
        return "Please enter a valid model name for your item.";
    }

    if (!is_numeric($price)) {
        return "Please enter a valid price in USD.";
    }
    $item["brand"] = $brand;
    $item["type"] = $type;
    $item["model"] = $model;
    $item["description"] = ucwords($description);
    $item["price"] = floatval($price);
    return $item;
}

function fixStr($s)
{
    return strtoupper(trim($s));
}

# checks whether brand or device type exists from the db
function checkInfoBy($by, $s)
{
    $s = strtoupper(trim($s));
    $vals;
    if ($by === "brand") {
        $vals = getBrands();
    } else {
        $vals = getTypes();
    }
    foreach ($vals as $val) {
        if ($s === $val || rtrim($s, "S") === $val)
            return true;
    }
    return false;
}

function getItemsBy($by, $s)
{
    $s = rtrim(strtoupper($s), "S");
    $conn = getConn();
    $stmt = $conn->prepare("SELECT * FROM item NATURAL JOIN types NATURAL JOIN brands WHERE {$by} = :s");
    $stmt->bindValue(":s", $s);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll();
    } else {
        return null;
    }
}

function getMyItems($user_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT * FROM item NATURAL JOIN types NATURAL JOIN brands WHERE user_id = :user_id ORDER BY post_date DESC");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll();
    } else {
        return null;
    }
}

function getRecentAds($user_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT * FROM item NATURAL JOIN types NATURAL JOIN brands WHERE user_id <> :user_id ORDER BY post_date DESC");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $allItems = $stmt->fetchAll();
        # lel, not the best way but kay
        foreach ($allItems as $item) {
            if (!file_exists($item["item_img"])) {
                updateItemImg($item["item_id"], DEFAULT_ITEM_IMG_PATH);
                $item["item_img"] = DEFAULT_ITEM_IMG_PATH;
            }
        }
        return $allItems;
    } else {
        return null;
    }
}

function getOneItem($item_id)
{ # for buying item
    $conn = getConn();
    $stmt = $conn->prepare("SELECT item_id, type, brand, model, description, item_img, price, post_date, user_id, first_name, last_name, phone_number FROM item NATURAL JOIN user NATURAL JOIN brands NATURAL JOIN types WHERE item_id = :item_id");
    $stmt->bindValue(":item_id", $item_id);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return $stmt->fetch();
    } else {
        return null;
    }
}

function isMyItem($user_id, $item_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("SELECT * FROM item WHERE user_id = :user_id AND item_id = :item_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->bindValue(":item_id", $item_id);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function updateItemImg($item_id, $item_img)
{
    $conn = getConn();
    $stmt = $conn->prepare("UPDATE item SET item_img = :item_img WHERE item_id = :item_id");
    $stmt->bindValue(":item_id", $item_id);
    $stmt->bindValue(":item_img", $item_img);
    $stmt->execute();
}

function insertItem($user_id, $item)
{
    $conn = getConn();
    $stmt = $conn->prepare("INSERT INTO item (user_id, type_id, brand_id, model, price, description) VALUES (:user_id, :type_id, :brand_id, :model, :price, :description)");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->bindValue(":type_id", getTypeId($item["type"]));
    $stmt->bindValue(":brand_id", getBrandId($item["brand"]));
    $stmt->bindValue(":model", $item["model"]);
    $stmt->bindValue(":price", $item["price"]);
    $stmt->bindValue(":description", $item["description"]);
    $stmt->execute();
    return $conn->lastInsertId();
}

function updateItem($item_id, $item)
{
    $conn = getConn();
    $stmt = $conn->prepare("UPDATE item SET type_id = :type_id, brand_id = :brand_id, model = :model, price = :price, description = :description WHERE item_id = :item_id");
    $stmt->bindValue(":item_id", $item_id);
    $stmt->bindValue(":type_id", getTypeId($item["type"]));
    $stmt->bindValue(":brand_id", getBrandId($item["brand"]));
    $stmt->bindValue(":model", $item["model"]);
    $stmt->bindValue(":price", $item["price"]);
    $stmt->bindValue(":description", $item["description"]);
    $stmt->execute();
    return true;
}

function deleteItem($item_id)
{
    $conn = getConn();
    $stmt = $conn->prepare("DELETE FROM item WHERE item_id = :item_id");
    $stmt->bindValue(":item_id", $item_id);
    return $stmt->execute();
}

function getBrandId($brand)
{
    $brand = strtoupper($brand);
    $conn = getConn();
    $stmt = $conn->prepare("SELECT brand_id FROM brands WHERE brand = :brand");
    $stmt->bindValue(":brand", $brand);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_COLUMN, 0);
}

function getTypeId($type)
{
    $type = strtoupper($type);
    $conn = getConn();
    $stmt = $conn->prepare("SELECT type_id FROM types WHERE type = :type");
    $stmt->bindValue(":type", $type);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_COLUMN, 0);
}

function getBrands()
{
    $conn = getConn();
    $stmt = $conn->query("SELECT brand FROM brands");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
}

function getTypes()
{
    $conn = getConn();
    $stmt = $conn->query("SELECT type FROM types");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
}

/*
 */
?>

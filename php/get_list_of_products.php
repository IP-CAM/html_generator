<?php
/**
 * Getting all title and link of the products
 * @return array
 */
function getProducts()
{
    require_once 'store_db_connect.php';
    $db = new DB_CONNECT();
    $con = $db->connect();
    $response["products"] = array();
    $result = getAllowedProducts($con);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $product = array();
//            $product["guid"] = getLinkToProduct($con, $row["product_id"]);
            $product["post_title"] = getNameOfProduct($con, $row["product_id"]);
            array_push($response["products"], $product);
        }
    } else {
        echo "0 results";
    }
    $con->close();
    return $response["products"];
}

/**
 * Getting id's all allowed products (stock_status_id = 7)
 * @param $con
 * @return mixed
 */
function getAllowedProducts($con)
{
    $sql = "SELECT product_id FROM op_product WHERE stock_status_id=7";
    $result = $con->query($sql);
    return $result;
}

/**
 * Getting link to product by id
 * @param $con
 * @param $id
 * @return null|string
 */
function getLinkToProduct($con, $id)
{
    $query = 'product_id=' . $id;
    $sql = "SELECT keyword FROM op_url_alias WHERE query='$query'";
    $result = $con->query($sql);
    $link = null;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $link = 'https://baza.biz.ua/' . $row["keyword"];
        }
    }
    return $link;
}

/**
 * Getting name of product by id
 * @param $con
 * @param $id
 * @return null
 */
function getNameOfProduct($con, $id)
{
    $sql = "SELECT name FROM op_product_description WHERE product_id='$id'";
    $result = $con->query($sql);
    $name = null;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $name = $row["name"];
        }
    }
    return $name;
}

getProducts();
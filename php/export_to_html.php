<?php
if(isset($_GET['v'])){
    $req = $_GET['v'];
    $arr=getFieldsOfProducts($req);

    $hdr = readFromFile("../html_template/header.html");
    $prp = prepareData($arr,$req);
    $foo = readFromFile("../html_template/footer.html");

    writeToFile($hdr.$prp.$foo);
}

/**
 * Writing text data to file
 * @param $req
 */
function writeToFile($req){
    clearstatcache();
    $fileName = "../../store/promo/offer.html";
    clearstatcache(true, $fileName);
    file_put_contents($fileName, $req."\r\n", FILE_TEXT);
}

/**
 * Preparing data for table
 * @param $arr
 * @param $req
 * @return string
 */
function prepareData($arr,$req){
    $begin_table='<table align="center" border="0" cellpadding="20" cellspacing="0" style="width:660px;">
        <tbody>';
    $text = $begin_table;
    $count_cells=1;
    for($i=0;$i<count($req);$i++){
        if($count_cells == 1){//is this a first row -> open tr tag
            $text .='<tr style="background-color:#E8EAF6; display: -webkit-grid; ">';
        }
        
        //&#34; <- double quotes
        $text .= '<td><div class="cell"><a href="';
        $text .= $arr[$req[$i]]['guid'];
        $text .= '"><img class="img-product" src="';
        $text .= $arr[$req[$i]]['img'];
        $text .= '"/></a>
            <p class="name-product">';
            $text .= $arr[$req[$i]]['post_title'];
            $text .= '</p>
            <p class="regular-price">';
            $text .= $arr[$req[$i]]['regular_price'];
            $text .= 'грн.</p>
            <p class="sale-price">';
            $text .= $arr[$req[$i]]['sale_price'];
            $text .= 'грн.</p>
            <a class="buy-button" href="';
            $text .= $arr[$req[$i]]['guid'];
            $text .= '">ПОДРОБНЕЕ</a> </div></td>'."\r\n";
        
        if($count_cells == 3){//is this a last cell -> close tr tag
            $text .='</tr>'."\r\n";
            $count_cells = 0;
        }
        $count_cells++;
    }
    $end_table='<tr style="background-color:white; height:20px;"><td></td></tr>
        </tbody>
    </table>'."\r\n";
    $text .= $end_table;
    return $text;
}

/**
 * Reading text from file
 * @param $fileName
 * @return string
 */
function readFromFile($fileName){
    $fp = fopen($fileName, "r");
    $text = '';
    if ($fp){
        while (!feof($fp)){
            $text .= fgets($fp);
        }
    }
    fclose($fp);
    clearstatcache();
    clearstatcache(true, $fileName);
    return $text;
}

/**
 * Getting all params of the products
 * @param $req
 * @return array
 */
function getFieldsOfProducts($req){
    require_once 'store_db_connect.php';
    $db = new DB_CONNECT();
    $con = $db->connect();
    $arrFieldsOfProducts = array();

    for($i=0; $i<count($req); $i++) {
        $product["post_title"] = $req[$i];
        $id = getIdByName($con, $req[$i]);
        $product["guid"] = getLinkToProduct($con, $id);
        $product["img"] = getImageById($con, $id);
        $product["regular_price"] = getPriceById($con, $id);
        $product["sale_price"] = getSalePriceById($con, $id);

        $arrFieldsOfProducts[$req[$i]] = $product;
    }
    return $arrFieldsOfProducts;
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
 * Getting id of product by his name
 * @param $con
 * @param $name
 * @return $id
 */
function getIdByName($con, $name){
    $sql = "SELECT 	product_id FROM op_product_description WHERE name='$name'";
    $result = $con->query($sql);
    $id = null;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row["product_id"];
        }
    }
    return $id;
}

/**
 * Getting link to image, by id of product
 * @param $con
 * @param $id
 * @return $image
 */
function getImageById($con, $id){
    $sql = "SELECT image FROM op_product WHERE product_id='$id'";
    $result = $con->query($sql);
    $image = null;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $image = 'https://baza.biz.ua/image/'.$row["image"];
        }
    }
    return $image;
}

/**
 * Getting price of product by id
 * @param $con
 * @param $id
 * @return int
 */
function getPriceById($con, $id){
    $sql = "SELECT price FROM op_product WHERE product_id='$id'";
    $result = $con->query($sql);
    $price = 0;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $price = (int)$row["price"];
        }
    }
    return $price;
}

/**
 * Getting sale price of product by id
 * @param $con
 * @param $id
 * @return int
 */
function getSalePriceById($con, $id){
    $sql = "SELECT price FROM op_product_special WHERE product_id='$id'";
    $result = $con->query($sql);
    $price = 0;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $price = (int)$row["price"];
        }
    }
    return $price;
}
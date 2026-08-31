<?php

$host = "localhost";
$port = "3306";
$db   = "db";
$user = "root";
$pass = "";

$conn = mysqli_connect($host, $user, $pass, $db, $port);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$sql = "SELECT product_id, product_name, price, img_path, description
        FROM product
        WHERE category_id = 2";

$result = mysqli_query($conn, $sql);

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}


$selected_product = $products[0];

if (isset($_GET['product'])) {
    $id = intval($_GET['product']);
    foreach ($products as $p) {
        if ($p['product_id'] == $id) {
            $selected_product = $p;
            break;
        }
    }
}


$selected_product_id = $selected_product['product_id'];

$sql_flavour = "SELECT flaver_id, flavour_img_path, flavour_type, new_price
                FROM flavour
                WHERE product_id = $selected_product_id";

$result_flavour = mysqli_query($conn, $sql_flavour);

$flavours = [];
while ($row = mysqli_fetch_assoc($result_flavour)) {
    $flavours[] = $row;
}
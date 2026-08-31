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
        WHERE category_id = 1"; 

$result = mysqli_query($conn, $sql);

$drinks = [];
while ($row = mysqli_fetch_assoc($result)) {
    $drinks[] = $row;
}

if (empty($drinks)) {
    die("No drinks found");
}

$selected_drink = $drinks[0];

if (isset($_GET['drink'])) {
    $id = (int)$_GET['drink'];
    foreach ($drinks as $d) {
        if ($d['product_id'] == $id) {
            $selected_drink = $d;
            break;
        }
    }
}

/* ---------- FLAVOURS ---------- */
$selected_drink_id = $selected_drink['product_id'];

$sql_flavour = "SELECT flavour_img_path, flavour_type, new_price
                FROM flavour
                WHERE product_id = $selected_drink_id";

$result_flavour = mysqli_query($conn, $sql_flavour);

$flavours = [];
while ($row = mysqli_fetch_assoc($result_flavour)) {
    $flavours[] = $row;
}

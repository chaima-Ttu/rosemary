<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

$host = "localhost";
$port = "3306";
$db   = "db";
$user = "root";
$pass = "";

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error_msg = '';

if (isset($_POST['product_id'], $_POST['user_id'], $_POST['rate_num'])) {

    $product_id = (int) $_POST['product_id'];
    $user_id    = (int) $_POST['user_id'];
    $rate_num   = (int) $_POST['rate_num'];
    $cmnt       = $_POST['cmnt'] ?? '';

    $sql = "
        INSERT INTO rate (product_id, user_id, rate_num, cmnt, rate_date)
        VALUES (?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE
            rate_num = VALUES(rate_num),
            cmnt = VALUES(cmnt),
            rate_date = NOW()
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iiis", $product_id, $user_id, $rate_num, $cmnt);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "Success"; // return success to fetch
    } else {
        http_response_code(500);
        echo "Database error: " . mysqli_error($conn);
    }
}
?>

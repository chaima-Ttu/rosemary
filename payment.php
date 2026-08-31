<?php
session_start();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = "localhost";
$port = "3306";
$db   = "db";
$user = "root";
$pass = "";


$conn = mysqli_connect($host, $user, $pass, $db, $port);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Include loyalty logic
include 'loyalty.php';

// ------------------------ 1. Check POST ------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('INVALID_REQUEST');
}

// ------------------------ 2. Get form data ------------------------
$phone     = trim($_POST['phone'] ?? '');
$street    = trim($_POST['street'] ?? '');
$building  = trim($_POST['building'] ?? '');
$apartment = $_POST['apartment'] ?? '';

if ($phone === '' || $street === '' || $building === '') {
    header("Location: payment.html?error=missing");
    exit;
}

// ------------------------ 3. Session validation ------------------------
if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ------------------------ 4. Filter cart ------------------------
$cart = array_filter($_SESSION['cart'], fn($item) => isset($item['user_id']) && $item['user_id'] == $user_id);
if (empty($cart)) {
    header("Location: cart.php");
    exit;
}

mysqli_begin_transaction($conn);

try {
    // ------------------------ 5. Check if free loyalty order ------------------------
    $stmt = mysqli_prepare($conn, "SELECT loylaty_crredit FROM users WHERE users_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $loyalty_credit);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $isFreeOrder = (int)$loyalty_credit === 1;

    // ------------------------ 6. Insert address ------------------------
    $stmt = mysqli_prepare($conn,
        "INSERT INTO user_addresse (user_id, phone_number, street_name, bulding_number, apt_number)
         VALUES (?, ?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, "issss", $user_id, $phone, $street, $building, $apartment);
    mysqli_stmt_execute($stmt);
    $address_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    $_SESSION['last_address_id'] = $address_id;

    // ------------------------ 7. Calculate total ------------------------
    $total_price = 0;
    foreach ($cart as $item) {
        $stmtPrice = mysqli_prepare($conn, "SELECT price FROM product WHERE product_id = ?");
        mysqli_stmt_bind_param($stmtPrice, "i", $item['product_id']);
        mysqli_stmt_execute($stmtPrice);
        mysqli_stmt_bind_result($stmtPrice, $dbPrice);
        mysqli_stmt_fetch($stmtPrice);
        mysqli_stmt_close($stmtPrice);

        if ($dbPrice === null) throw new Exception("Invalid product");
        $total_price += $dbPrice * $item['quantity'];
    }

    if ($isFreeOrder) $total_price = 0;

    // ------------------------ 8. Insert order ------------------------
    $payment_method = $isFreeOrder ? 'loyalty' : 'card';
    $stmt = mysqli_prepare($conn,
        "INSERT INTO ordere (user_id, addresse_id, payment_method, total_price, order_statues, order_date)
         VALUES (?, ?, ?, ?, 'paid', NOW())"
    );
    mysqli_stmt_bind_param($stmt, "iisd", $user_id, $address_id, $payment_method, $total_price);
    mysqli_stmt_execute($stmt);
    $order_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    // ------------------------ 9. Insert order items ------------------------
    $stmtItem = mysqli_prepare($conn,
        "INSERT INTO order_item (order_id, product_id, quentity, suger_qentity, flaver_id, size_id, item_price)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    foreach ($cart as $item) {
        $item_price = $isFreeOrder ? 0 : $item['price'];

        $sugar_qty  = $item['sugar_qty'] ?? 0;
        $flavour_id = $item['flavour_id'] ?? null;
        $size_id    = $item['size_id'] ?? null;

        mysqli_stmt_bind_param($stmtItem, "iiiiiid",
            $order_id,
            $item['product_id'],
            $item['quantity'],
            $sugar_qty,
            $flavour_id,
            $size_id,
            $item_price
        );
        mysqli_stmt_execute($stmtItem);

        // Update product total_sold
        $stmtUpdate = mysqli_prepare($conn, "UPDATE product SET total_sold = total_sold + ? WHERE product_id = ?");
        mysqli_stmt_bind_param($stmtUpdate, "ii", $item['quantity'], $item['product_id']);
        mysqli_stmt_execute($stmtUpdate);
        mysqli_stmt_close($stmtUpdate);
    }
    mysqli_stmt_close($stmtItem);

    // ------------------------ 10. Update total spent ------------------------
    if (!$isFreeOrder) {
        $stmt = mysqli_prepare($conn,
            "UPDATE users SET total_spent = total_spent + ? WHERE users_id = ?"
        );
        mysqli_stmt_bind_param($stmt, "di", $total_price, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Check and unlock loyalty
        checkAndUnlockLoyalty($conn, $user_id);
    } else {
        // Reset free order
        $stmt = mysqli_prepare($conn,
            "UPDATE users SET loylaty_crredit = 0, total_spent = 0 WHERE users_id = ?"
        );
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // ------------------------ 11. Commit ------------------------
    mysqli_commit($conn);

    // ------------------------ 12. Clear cart ------------------------
    $_SESSION['cart'] = array_filter($_SESSION['cart'], fn($item) => $item['user_id'] != $user_id);

    header("Location: //localhost/coffee/index.php");
    exit;

} catch (Throwable $e) {
    mysqli_rollback($conn);
    die("ERROR: " . $e->getMessage());
}
?>
<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Create cart if not exist 
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Clean up old cart items without user_id
foreach ($_SESSION['cart'] as $key => $item) {
    if (!isset($item['user_id'])) {
        unset($_SESSION['cart'][$key]);
    }
}
$_SESSION['cart'] = array_values($_SESSION['cart']);

// Only logged in users can add to cart
if (isset($_POST['product_id']) && !isset($_SESSION['user_id'])) {
    // Redirect to login or show error
    header("Location: index.php"); // Change to your login page
    exit;
}

// Get user_id if logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Add item to cart 
if (isset($_POST['product_id'], $_POST['quantity'], $_POST['price'], $_POST['flavour_name'], $_POST['img_path'])) {

    $product_id = (int) $_POST['product_id'];
    $qty        = (int) $_POST['quantity'];
    $price      = (float) $_POST['price'];
    $name       = $_POST['flavour_name'];
    $img        = $_POST['img_path'];

    if ($product_id > 0 && $qty > 0 && $user_id) {
        $found = false;

        // Check if product exists, if yes increase the quantity
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $product_id && $item['name'] == $name && $item['user_id'] == $user_id) {
                $item['quantity'] += $qty; 
                $found = true;
                break;
            }
        }
        unset($item);

        // Add new product if not found
        if (!$found) {
            $_SESSION['cart'][] = [
                'user_id'    => $user_id,
                'product_id' => $product_id,
                'name'       => $name,
                'price'      => $price,
                'img_path'   => $img,
                'quantity'   => $qty
            ];
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

/* Update quantity in cart */
if (isset($_POST['update_index'], $_POST['action'])) {
    $i = (int) $_POST['update_index'];

    if (isset($_SESSION['cart'][$i]) && $_SESSION['cart'][$i]['user_id'] == $user_id) {
        if ($_POST['action'] === 'plus') {
            $_SESSION['cart'][$i]['quantity']++;
        } else {
            $_SESSION['cart'][$i]['quantity']--;
        }

        // Remove if quantity <= 0
        if ($_SESSION['cart'][$i]['quantity'] <= 0) {
            unset($_SESSION['cart'][$i]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // reindex
        }
    }

    header("Location: cart.php");
    exit;
}

/* Remove item */
if (isset($_POST['remove_index'])) {
    $i = (int) $_POST['remove_index'];

    if (isset($_SESSION['cart'][$i]) && $_SESSION['cart'][$i]['user_id'] == $user_id) {
        unset($_SESSION['cart'][$i]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }

    header("Location: cart.php");
    exit;
}

// Filter cart to show only current user's items
$cart = [];
if ($user_id) {
    foreach ($_SESSION['cart'] as $item) {
        if ($item['user_id'] == $user_id) {
            $cart[] = $item;
        }
    }
}
?>
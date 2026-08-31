<?php
    session_start();
    include("cartlogic.php");
    $host = "localhost";
    $port = "3306";
    $db   = "db";
    $user = "root";
    $pass = "";


    $conn = mysqli_connect($host, $user, $pass, $db, $port);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $cart_total = 0;
    foreach ($cart as $item) {
        $cart_total += $item['price'] * $item['quantity'];
    }

        $recomended_products = [];
    $sql = "SELECT product_id, product_name, price, img_path 
        FROM product 
        WHERE product_id IN (1,2) 
        LIMIT 2";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $recommended_products[] = $row;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cart.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="darkmode.css">
   <title>Shopping Cart</title>
</head>
<body class="true-diamond-background">
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="limg">
                <a href="index.html">Rosemary</a>
            </div>
            <div class="navbox">
                <ul class="nav-menu" id="navMenu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="menu.html">Menu</a></li>
                </ul>
            </div>
            <!-- carte icon -->
            <div class="nav-icons">
                <a href="cart.php" class="shop-link">
                    <img src="img/shopp.png" alt="Shop">
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="cart-container">
            <div class="cart-header">
                <h2>Shopping Cart</h2>

                <?php if (!empty($cart)): ?>
                    <div class="total-price">
                        $<?= number_format($cart_total, 2); ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($cart)): ?>
            <div class="cart-items">
                <?php foreach ($cart as $index => $item): ?>
                    <div class="cart-item">
                        <div class="item-image">
                            <img src="<?= htmlspecialchars($item['img_path']); ?>">
                        </div>
                        <div class="item-details">
                            <div class="item-name">
                                <?= htmlspecialchars($item['name']); ?>
                            </div>
                            <div class="item-price">$<?= number_format($item['price'], 2); ?>
                            </div>
                        </div>
                        <div class="item-quantity">
                            <form method="post">
                                <input type="hidden" name="update_index" value="<?= $index; ?>">
                                <input type="hidden" name="action" value="plus">
                                <button class="quantity-btn">+</button>
                            </form>
                            <span class="quantity-value"><?= $item['quantity']; ?></span>
                            <form method="post">
                                <input type="hidden" name="update_index" value="<?= $index; ?>">
                                <input type="hidden" name="action" value="minus">
                                <button class="quantity-btn">-</button>
                            </form>
                        </div>
                        <form method="post">
                            <input type="hidden" name="remove_index" value="<?= $index; ?>">
                            <button class="remove-item">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>  

            <div class="cart-actions">
                <a href="menu.html" class="btn btn-continue">Continue Shopping</a>
                <form method="post" action="paymentt.php">
                    <?php foreach($cart as $item): ?>
                        <input type="hidden" name="product_ids[]" value="<?= $item['product_id']; ?>">
                        <input type="hidden" name="quantities[]" value="<?= $item['quantity']; ?>">
                    <?php endforeach; ?>
                    <button type="submit" class="btn-checkout">Order Now</button>
                </form>
            </div>
            <?php else: ?>

            <div class="empty-cart">
                <p>Your cart is empty</p>
                <a href="menu.html" class="btn-continue">Start Shopping</a>
            </div>
            <?php endif; ?>

        </div>

        <!-- recomndation -->
        <div class="recommendations">
            <h3 class="recommendations-title">Our customers also buy...</h3>
            <div class="recommended-items">
                <?php if (!empty($recommended_products)): ?>
                <?php foreach ($recommended_products as $rec): ?>
                    <div class="recommended-item">
                        <div class="shape">
                            <img src="<?= htmlspecialchars($rec['img_path']); ?>" style="width:50%;">
                            <h2><?= htmlspecialchars($rec['product_name']); ?></h2>
                        </div>
                        <form method="post">
                            <input type="hidden" name="product_id" value="<?= $rec['product_id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="price" value="<?= $rec['price']; ?>">
                            <input type="hidden" name="flavour_name" value="<?= htmlspecialchars($rec['product_name']); ?>">
                            <input type="hidden" name="img_path" value="<?= $rec['img_path']; ?>">
                            <button class="add-to-cart-btn">Add</button>
                        </form>
                    </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
    <footer class="footerr">
        <p>Rosemary &copy; 2025. All rights reserved.</p>
    </footer>
    <script src="cart.js?v=<?php echo time(); ?>"></script>
    <script src="darkmode.js"></script>
</body>
</html>
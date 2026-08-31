<?php
    session_start();
    if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
        header("Location: cart.php");
        exit;
    }
    $cart_total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $cart_total += $item['price'] * $item['quantity'];   
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="paystyle.css">
    <link rel="stylesheet" href="darkmode.css">
    <title>Rosemary -payment-</title>
</head>
<body class="true-diamond-background">
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="limg">
                <a href="index.php">Rosemary</a>
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

    <div class="holder">
        <div id="userInfoOverlay" class="user-info-overlay">
            <div class="user-info">
                <h3 class="user-info-title">Delivery Information</h3>
                <fieldset>
                    <legend>Shipping Details</legend>
                    
                    <form id="userInfoForm"  class="user-info-form"  method="POST"  action="payment.php"  novalidate>
                        <div class="form-group">
                            <label for="userPhone" class="required">Phone Number</label>
                              <div class="phone-input-container">
                                   <input type="tel" name="phone"  id="userPhone" placeholder="+1 XXX XXX XXX" required>
                               </div>
                            <div class="error-message" id="userPhoneError">Please enter a valid phone number</div>
                        </div>

                          <div class="form-group">
                            <label for="street" class="required">Street Name</label>
                            <input type="text" name="street" id="street" placeholder="Enter street name" required>
                            <div class="error-message" id="streetError">Street name is required</div>
                        </div>
 
                        <div class="form-group">
                            <label for="userBuilding" class="required">Building Number</label>
                            <input type="text"  name="building" id="userBuilding" placeholder="Building number"  required>
                            <div class="error-message" id="userBuildingError">Building number is required</div>
                        </div>

                        <div class="form-group">
                            <label for="appartement">Apartment/Unit (Optional)</label>
                            <input type="text"  name="apartment" id="appartement" placeholder="Apartment, floor, etc.">
                        </div>

                        <div class="form-group">
                            <label for="creditCard" class="required">Credit Card Number</label>
                            <input type="text"  name="creditCard" id="creditCard"  placeholder="1234 5678 9012 3456" pattern="[0-9\s]{13,19}" inputmode="numeric" required>
                            <div class="error-message" id="creditCardError">Please enter a valid credit card number</div>
                        </div>

                        <div class="form-group">
                            <label for="Delivery">
                                <input type="radio"  name="delivery"id="Delivery" value="home" checked required> Home Delivery
                            </label>
                        </div>

                        <div class="summary-item total">
                            <span>Total Cost</span>
                            <span class="price" id="totalCost"><?= number_format($cart_total, 2); ?> $</span>
                        </div>

                        <div class="form-buttons">
                            <button type="submit" class="btn btn-submit" id="submitBtn">Confirm Order</button>
                            <button type="button" class="btn btn-cancel" id="cancelBtn">Cancel</button>
                        </div>
                    </form>
                </fieldset>
            </div>
        </div>
    </div>
    <script src="payment.js"></script>
    <script src="darkmode.js"></script>
</body>
</html>
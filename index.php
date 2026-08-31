<?php
    session_start();
    include("cartlogic.php");
    error_reporting(E_ALL);

    $host = "localhost";
    $port = "3306";
    $db   = "db";
    $user = "root";
    $pass = "";



    $conn = mysqli_connect($host, $user, $pass, $db, $port);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql="SELECT product_id, product_name, total_sold ,img_path,price
        FROM product
        ORDER BY total_sold DESC
        LIMIT 3";

    $result = mysqli_query($conn, $sql);
    $products = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="istyle.css">
    <link rel="stylesheet" href="darkmode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Rosemary</title>
    <script>
        const isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;
    </script>
</head>
<body class="true-diamond-background">
    <?php if (!empty($_SESSION['loyalty_unlocked'])): ?>
    <div class="custom-alert success show">
      <div class="alert-content">
        <span class="alert-message">🎉 Congrats! You got a free loyalty treat!</span>
      </div>
    </div>
    <?php unset($_SESSION['loyalty_unlocked']); ?>
  <?php endif; ?>

  <script>
    // remove after 5s
    setTimeout(() => {
        const alert = document.querySelector('.custom-alert');
        if(alert){
            alert.classList.remove('show');
            setTimeout(()=> alert.remove(), 300);
        }
    }, 5000);
  </script>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="limg">
                <a href="#sec1">Rosemary</a>
            </div>

            <div class="navbox">
                <ul class="nav-menu" id="navMenu">
                    <li><a href="#sec1">Home</a></li>
                    <li><a href="#sec2">About</a></li>
                    <li><a href="menu.html">Menu</a></li>
                    <li><a href="#sec3">Contact</a></li>
                </ul>
            </div>

            <!-- carte icon -->
            <div class="nav-icons">
                <a href="//localhost/coffee/cart.php" class="shop-link">
                    <img src="img/shopp.png" alt="Shop">
                </a>
            </div>

            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- LOGOUT LINK -->
                <a href="logout.php" class="logout-link">Logout</a>
            <?php else: ?>
                <!-- LOGIN BUTTON -->
                <a href="#" onclick="showLogin()"></a>
            <?php endif; ?>
        </div>
    </nav>
    <button id="mobtn" class="mode"><img class="mode-img" src="img/Untitled_design__14_-removebg-preview (1).png" width="25px"></button>
    

    <div class="content-container">

        <!--1st_title-->
        <section class="page1" id="sec1">
            <div class="center-page1">
                <h1>Rosemary</h1>
                <p>Where Every Cup Tells A Story</p>
                <a href="#" class="shop-btn" onclick="handleShopNow(event)">Shop now</a>
            </div>
        </section>
    
        <!--2nd_the most selllers-->
        <section class="sellers">
            <h2>The Most Sellers</h2>
            <div class="best">
                <?php foreach ($products as $p): ?>
                <div class="shape">
                    <img src="<?= htmlspecialchars($p['img_path']); ?>" alt="<?= htmlspecialchars($p['product_name']); ?>">
                    <h2><?= htmlspecialchars($p['product_name']); ?></h2>
                
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <form method="post" action="cartlogic.php">
                        <input type="hidden" name="product_id" value="<?= $p['product_id']; ?>">
                        <input type="hidden" name="flavour_name" value="<?= htmlspecialchars($p['product_name']); ?>">
                        <input type="hidden" name="price" value="<?= $p['price']; ?>">
                        <input type="hidden" name="img_path" value="<?= htmlspecialchars($p['img_path']); ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="shop-btn1">Add to Cart</button>
                    </form>
                    <?php else: ?>
                    <button class="shop-btn1" onclick="showLogin()">Add to Cart</button>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!--3rd_comments-->
        <section class="what">
            <h2>What Our Customer Says?</h2>
            <div class="customers">
                <div class="comments">
                    <h3>Liz</h3>
                    <p>"Such a beautiful atmosphere. Everything looks aesthetic, and the pastries taste even better than they look."</p>
                </div>
                <div class="comments">
                    <h3>James</h3>
                    <p>"The chocolate chip cookies are the perfect balance of chewy and crispy."</p>
                </div>
                <div class="comments">
                    <h3>Martin</h3>
                    <p>"Worth every penny! Quality you can taste in every product."</p>
                </div>
                <div class="comments">
                    <h3>Sofia</h3>
                    <p>"The coziest café in town! Their croissants literally melt in your mouth. I come here every morning before work."</p>
                </div>
                <div class="comments">
                    <h3>Amy</h3>
                    <p>"Beautiful interior design and always so clean. My happy place!"</p>
                </div>
                <div class="comments">
                    <h3>Liz</h3>
                    <p>"Such a beautiful atmosphere. Everything looks aesthetic, and the pastries taste even better than they look."</p>
                </div>
                <div class="comments">
                    <h3>James</h3>
                    <p>"The chocolate chip cookies are the perfect balance of chewy and crispy."</p>
                </div>
                <div class="comments">
                    <h3>Martin</h3>
                    <p>"Worth every penny! Quality you can taste in every product."</p>
                </div>
                <div class="comments">
                    <h3>Sofia</h3>
                    <p>"The coziest café in town! Their croissants literally melt in your mouth. I come here every morning before work."</p>
                </div>
                <div class="comments">
                    <h3>Amy</h3>
                    <p>"Beautiful interior design and always so clean. My happy place!"</p>
                </div>
            </div>
        </section>

        <!-- 4th_Description + 5th_Footer section -->
        <section class="description" id="sec2">
            <div>
                <div class="colomn">
                    <div class="logo">
                        <img class="theme-img" src="img/R__2_-removebg-preview.png" alt="Rosemary Logo">
                    </div>
                </div>
                <div class="colomn">
                    <div class="text">
                        <h2>Who We Are?</h2>
                        <p>Welcome to Rosemary,<br> a cafe and bakery built on quality, care, and warmth.<br> We serve great coffee and freshly baked pastries made with homemade recipes and fresh ingredients.<br> Our goal is to create a cozy place where people can relax, connect, and enjoy simple moments of comfort.</p>
                    </div>
                </div>
            </div>

            <!-- Footer section -->
            <div class="footer" id="sec3">
                <div class="pic">
                    <img class="theme-pic" src="img/R__3_-removebg-preview.png" alt="Rosemary Logo">
                </div>
                <div class="infos">
                    <div class="adr">
                        <h4>ADDRESS</h4>
                        <p>3540 South San Francisco Street</p>
                        <p>San Francisco</p>
                    </div>
                    <div class="time">
                        <h4>OPENING HOURS</h4>
                        <p>Mon - Fri 6am - 10pm</p>
                        <p>Sat - Sun 6am - 12pm</p>
                    </div>
                    <div class="contact">
                        <h4>CONTACT</h4>
                        <a href="tel:+1234562200">123-456-2200</a><br>
                        <a href="mailto:RosemaryCafe@gamil.com">RosemaryCafe@gamil.com</a>
                    </div>
                    <div class="icons">
                        <h4>FOLLOW</h4>
                        <i class="fab fa-facebook"></i>
                        <i class="fab fa-instagram"></i>
                        <i class="fab fa-tiktok"></i>
                    </div>
                </div>
            </div>

            <script src="cafe.js"></script>
            <script src="login.js"></script>
            <script src="darkmode.js"></script>
        </section>
    </div>

    <?php if (!isset($_SESSION['user_id'])): ?>
        <template id="loginTemplate"><!--Anything inside <template> is ignored by the browser until you explicitly instantiate it using JavaScript.<template> = hidden HTML for later use.-->
            <div id="loginOverlay">
                <link rel="stylesheet" href="login.css">
                <div class="main">
                    <div class="box2" id="register-box">
            
                        <!-- Error Message Display -->
                        <div id="error-message" style="display: none; background: #ff4444; color: white; padding: 10px; margin: 10px 0; border-radius: 5px; text-align: center;">
                        </div>
            
                        <form action="signup.php" method="post" id="authForm">
                            <h1 class="Title" id="Title">Sign Up</h1>
                            <span class="close-btn" id="closeBtn">&times;</span>
                
                            <!-- USERNAME FIELD (Only for signup) -->
                            <div class="input-box" id="namefeild">
                                <img src="img/user.png" id="userimg" style="width:7.8%; margin-left: -4px; margin-top: 10px;" alt="user icon">
                                <input type="text" id="username" placeholder="Username" name="username" pattern="[A-Za-z]{0,20}" required>
                            </div>

                            <!-- EMAIL FIELD -->
                            <div class="input-box" id="emailField">
                                <img src="img/mail.png" style="width: 4.9%; margin-left: 0px; margin-top: 15px;" alt="email icon">
                                <input type="email" id="email" placeholder="Email" required name="email">
                            </div>

                            <!-- PASSWORD FIELD -->
                            <div class="input-box" id="passwdfeild">
                                <img src="img/lock.png" id="lockimg" style="width:13%; margin-left: -15px; margin-top: 2px;" alt="lock icon">
                                <input type="password" id="password" placeholder="Password" name="password" required>
                            </div>

                            <div class="remmemb-fogot">
                                <label><input type="checkbox" name="remember">Remember me</label>
                                <a href="#" id="forget">Forgot password</a>
                            </div>
                
                            <div class="reg-link">
                                <button type="submit" class="but" id="submitBtn" name="signup">Sign Up</button>
                                <div class="reg-link">
                                    <p><span id="parse">Already have an account?</span> 
                                        <button type="button" id="go-register">Login</button>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    <?php endif; ?>
</body>
</html>
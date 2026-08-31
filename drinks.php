<?php
    session_start();
    include("getdrink.php"); 
    include("cartlogic.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dristyle.css">
    <link rel="stylesheet" href="darkmode.css">
    <title>Rosemary -drinks-</title>
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

    <div class="everything">
        <div class="left-side">
            <h1>Drinks</h1>
            <button class="scroll-btn">&#9650;</button>

            <?php foreach ($drinks as $p): ?>
                <div class="item">
                    <a href="?drink=<?= $p['product_id']; ?>">
                        <div class="ilabel"><?= $p['product_name']; ?></div>
                        <img class="iimg" src="<?= $p['img_path']; ?>">
                    </a>
                </div>
            <?php endforeach; ?>
            
            <button class="scroll-btn scroll-down">&#9660;</button>
        </div>

        <div class="right-side">
            <div class="chosen">
                <div class="img-container">
                    <img class="cimg" id="mainImg" src="<?= $selected_drink['img_path']; ?>">

                   <form id="ratingForm" method="post" action="//localhost/coffee/rate.php">
                      <div class="stars">
                          <input type="radio" name="rate_num" id="star1" value="1" />
                          <label for="star1" class="star"></label>

                          <input type="radio" name="rate_num" id="star2"  value="2" />
                          <label for="star2" class="star"></label>

                          <input type="radio" name="rate_num" id="star3"  value="3" />
                          <label for="star3" class="star"></label>

                          <input type="hidden" name="product_id" value="1">
                          <input type="hidden" name="user_id" value="2">

                      </div>
                    </form>
                </div>
                <script>
                document.addEventListener('DOMContentLoaded', () => {
                  const ratingForm = document.getElementById('ratingForm');

                  document.querySelectorAll('.stars input[type="radio"]').forEach(radio => {
                    radio.addEventListener('change', () => {
                      const formData = new FormData(ratingForm);
            
                      fetch(ratingForm.action, {
                         method: 'POST',
                         body: formData
                       })
                      .then(res => res.text())
                      .then(data => {
                          const alert = document.createElement('div');
                          alert.className = 'custom-alert success';
                          alert.innerHTML =  `<div class="alert-content"> 
                                                   <span class="alert-message">Thank you for your rating! 😊</span>
                                              </div>`;
                          document.body.appendChild(alert);
                          setTimeout(() => {
                            alert.classList.add('show');
                          },50)
                          setTimeout(() => {
                            alert.classList.remove('show');
                            setTimeout(() => alert.remove(), 300);
                          },5000)
                            
                          console.log('Rating submitted', data);
                        })
                       .catch(err => console.error('Error submitting rating', err));
                    });
                 });
               });
               </script>

                <div class="infr">
                    <div class="name">
                        <h2 id="productName"><?= $selected_drink['product_name']; ?></h2>
                        <h4>$<span id="productPrice"><?= $selected_drink['price']; ?></span></h4>
                    </div>
                    <p id="productDesc"><?= $selected_drink['description']; ?></p>

                    <form id="drinkform" method="post">
                        <input type="hidden" name="product_id" id="productId" value="<?= $selected_drink['product_id']; ?>">
                        <input type="hidden" name="flavour_name" id="flavourName" value="<?= $selected_drink['product_name']; ?>">
                        <input type="hidden" name="price" id="priceInput" value="<?= $selected_drink['price']; ?>">
                        <input type="hidden" name="img_path" id="imgPath" value="<?= $selected_drink['img_path']; ?>">
                        <input type="hidden" name="sugar" id="sugarInput" value="1">
                        <input type="hidden" name="size" id="sizeInput" value="1">
                        <input type="hidden" name="quantity" id="quantityInput" value="1">

                        <div class="selection">
                            <div class="sugar">
                                <h4>Sugar</h4>
                                <div class="s-cont">
                                    <button  type="button" class="s-btn" onclick="changeValue('sugar', -1)">-</button>
                                    <span class="value"id="sugarValue">1</span>
                                    <button  type="button" class="s-btn" onclick="changeValue('sugar', 1)">+</button>
                                </div>
                            </div>

                            <div class="size">
                                <h4>Size</h4>
                                <div class="z-cont">
                                    <button type="button" class="s-btn" onclick="changeValue('size', -1)">-</button>
                                    <span class="value" id="sizeValue">1</span>
                                    <button type="button" class="s-btn" onclick="changeValue('size', 1)">+</button>
                                </div>
                            </div>

                            <div class="quantity">
                                <h4>Quantity</h4>
                                <div class="q-cont">
                                    <button type="button" class="s-btn" onclick="changeValue('quantity', -1)">-</button>
                                    <span class="value" id="quantityValue">1</span>
                                    <button type="button" class="s-btn" onclick="changeValue('quantity', 1)">+</button>
                                </div>
                            </div>
                        </div>
                        <!-- hidden inputs -->
                        <input type="hidden" id="sugarInput" name="sugar" value="1">
                        <input type="hidden" id="sizeInput" name="size" value="M">
                        <input type="hidden" id="quantityInput" name="quantity" value="1">
                        <button class="add-btn" onclick="showStyledAlert()">Add To Cart</button>

                    </div>
                </div>

                <!-- FLAVOURS -->
                <div class="flavours">
                    <h4>Flavours</h4>
                    <div class="grid">
                        <?php if (!empty($flavours)): ?>
                            <?php foreach ($flavours as $f): ?>
                                <div class="fs" onclick="selectFlavour(
                                    '<?= $f['flavour_img_path']; ?>',
                                    '<?= $f['flavour_type']; ?>',
                                    '<?= $f['new_price']; ?>'
                                    )">
                                    <img src="<?= $f['flavour_img_path']; ?>" alt="<?= $f['flavour_type']; ?>">
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No flavours available</p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
            <button class="cmnt"><img src="img/cmnt.png" width="40px"></button>
        </div>
    </div>
    <script src="drinks.js"></script>
    <script src="darkmode.js"></script>
</body>
</html>
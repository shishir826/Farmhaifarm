<?php
session_start();
?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="UTF-8" />

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    />

    <title>Farm Fresh</title>

    <link rel="stylesheet" href="var.css" />
    <link rel="stylesheet" href="style.css" />

</head>

<body>

    <!-- Header -->
    <header>


        <div id="mainheader">

            <div class="logo">

                <a href="index.php">
                    🌱 FarmFresh
                </a>
            </div>


            <div class="search-bar">
                <input
                    type="text"
                    placeholder="Search fresh fruits & vegetables"
                />
                <button>
                    🔍
                </button>

            </div>


            <div class="header-icons">

                <a href="cart.html">
                    🛒 Cart
                </a>


                <?php if (isset($_SESSION["user_id"])): ?>

                    <span class="welcome-user">
                        👋 Welcome,
                        <?php
                        echo htmlspecialchars($_SESSION["user_name"]);
                        ?>
                    </span>

                    <a href="logout.php">
                        Logout
                    </a>


                <?php else: ?>

                    <a href="login.php">
                        👤 Login
                    </a>

                    <a href="register.php">
                        👤 SignUp
                    </a>

                <?php endif; ?>

            </div>

        </div>


        <!-- Navigation -->
        <nav id="main-nav">

            <div class="nav-container">

                <ul class="nav-menu">

                    <li>
                        <a href="index.php" class="active">
                            Home
                        </a>
                    </li>

                    <li>
                        <a href="shop.html">
                            Shop
                        </a>
                    </li>

                    <li>
                        <a href="about.html">
                            About Us
                        </a>
                    </li>

                    <li>
                        <a href="contact.html">
                            Contact
                        </a>
                    </li>

                </ul>

            </div>

        </nav>

    </header>

<!-- Banner Section -->
    <section class="banner" id="index">
      <div class="content">
        <h3>Skip the market! Choose <span>Fresh!</span></h3>

        <p>Get farm-fresh goodness delivered right at your doorstep.</p>

        <a href="shop.html" class="btn">
          <i
            class="fa-solid fa-cart-shopping"
            style="color: rgb(255, 255, 255)"
          ></i>
          Shop Now
        </a>
      </div>
    </section>
       <section class="picked" id="picked">
      <h3 class="today">Picked Today</h3>

      <div class="box-container">
        <!-- Product 1 -->
        <div class="box1">
          <img src="tasbir/today1.png" alt="Fresh cabbage" />

          <h3>Cabbage (बन्दा)</h3>

          <p>
            रु60/-
            <span>Express Delivery</span>
          </p>

          <h5>700kg sold this season</h5>
          <br />
          <a href="shop.html" class="bttn">Place Order</a>
        </div>

        <!-- Product 2 -->
        <div class="box2">
          <img src="tasbir/today2.png" alt="Fresh cabbage" />

          <h3>Cabbage (बन्दा)</h3>

          <p>
            रु60/-
            <span>Express Delivery</span>
          </p>

          <h5>700kg sold this season</h5>
          <br />

          <a href="shop.html" class="bttn">Place Order</a>
        </div>

        <!-- Product 3 -->
        <div class="box3">
          <img src="tasbir/today3.png" alt="Fresh cabbage" />

          <h3>Cabbage (बन्दा)</h3>

          <p>
            रु60/-
            <span>Express Delivery</span>
          </p>

          <h5>700kg sold this season</h5>
          <br />

          <a href="shop.html" class="bttn">Place Order</a>
        </div>

        <!-- Product 4 -->
        <div class="box4">
          <img src="tasbir/today4.png" alt="Fresh cabbage" />

          <h3>Cabbage (बन्दा)</h3>

          <p>
            रु60/-
            <span>Express Delivery</span>
          </p>

          <h5>700kg sold this season</h5>
          <br />

          <a href="shop.html" class="bttn">Place Order</a>
        </div>

        <!-- Product 5 -->
        <div class="box5">
          <img src="tasbir/today5.png" alt="Fresh cabbage" />

          <h3>Cabbage (बन्दा)</h3>

          <p>
            रु60/-
            <span>Express Delivery</span>
          </p>

          <h5>700kg sold this season</h5>
          <br />

          <a href="shop.html" class="bttn">Place Order</a>
        </div>

        <!-- Product 6 -->
        <div class="box6">
          <img src="tasbir/today6.png" alt="Fresh cabbage" />

          <h3>Cabbage (बन्दा)</h3>

          <p>
            रु60/-
            <span>Express Delivery</span>
          </p>

          <h5>700kg sold this season</h5>
          <br />

          <a href="shop.html" class="bttn">Place Order</a>
        </div>
      </div>
    </section>


    <!-- Footer -->
    <footer class="footer">
      <div class="footer-container">
        <!-- Brand -->
        <div class="footer-column footer-brand">
          <h2>🌱 FarmFresh</h2>

          <p>Fresh products directly from farmers to your door.</p>

          <p>📞 +977 9812397426</p>

          <p>✉ support@farmfresh.com</p>
        </div>

        <!-- Customer Service -->
        <div class="footer-column">
          <h3>Customer Service</h3>

          <a href="#">🚚 Track Order</a>
          <a href="#">📦 Delivery Information</a>
          <a href="#">💳 Payment Information</a>
        </div>

        <!-- Follow Us -->
        <div class="footer-column">
          <h3>Follow Us</h3>

          <a href="#">Facebook</a>
          <a href="#">Instagram</a>
          <a href="#">TikTok</a>
        </div>
      </div>

      <!-- Footer Bottom -->
      <div class="footer-bottom">
        <p>© 2026 FarmFresh. All Rights Reserved.</p>

        <p>Farm Fresh Natural 🌱</p>
        <p>hora daju here and ther handfuld sdf</p>
      </div>
    </footer>

    <!-- Font Awesome -->
    <script
      src="https://kit.fontawesome.com/2810143202.js"
      crossorigin="anonymous"
    ></script>
  </body>
</html>

<?php
    session_start();
    require_once '../include/dbcon.php';
    
    // Check if the user is logged in
    if(!isset($_SESSION["Email"])){
        header("location:../login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../customercss/homeCustomer.css">
    <link rel="stylesheet" href="../customercss/product_catalog.css">

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Customer Dashboard</title>
</head>
<body>
    <!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<img src="../admin/Picsart_24-03-29_12-28-55-400.png">
			<span class="text">Clothing</span>
		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="#">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="customers.php">
					<i class='bx bxs-group' ></i>
					<span class="text">Customers</span>
				</a>
			</li>
			<li>
				<a href="purchase.php">
					<i class='bx bxs-shopping-bag-alt' ></i>
					<span class="text">Purchase History</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			<li>
				<a href="#">
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="logout.php" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->
    <!-- CONTENT -->
    <section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<form action="#">
			</form>
			<input type="checkbox" id="switch-mode" hidden>
            <?php echo $_SESSION["Email"]; ?></h2>
			<a href="#" class="profile">
				<img src="../image/default_profile.png">
			</a>
		</nav>
		<!-- NAVBAR -->

        <!-- MAIN -->
        <main>
    <!-- Product Catalog Section -->
    <div class="product-catalog">
			<?php if(isset($message)) echo "<p>$message</p>"; ?>

        <h2>Product Catalog</h2>
        <div class="products">  
            <?php
                $pdoQuery = "SELECT * FROM products";
                $pdoResult = $pdoConnect->query($pdoQuery);
                if ($pdoResult) {
                    while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div class='product'>";
                        echo "<img src='" . $row['productImage'] . "' alt='" . $row['productName'] . "'>";
                        echo "<h3>" . $row['productName'] . "</h3>";
                        echo "<p>" . $row['productDescription'] . "</p>";
						echo '<form method= "post">';
						echo '<input name="productPrice" value="' . $row['productPrice'] . '" readonly>';
                        echo '<br><br><button type ="submit" name="buy">Buy</button>';
						echo '</form>';
                        echo "</div>";
                    }
                } else {
                    echo "No products found.";
                }
            ?>
        </div>
    </div>
</main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
<?php

	if(isset($_POST["buy"])){
        try {
			$customerName = $_SESSION["Email"]; 
            $purchaseDate = date("Y-m-d H:i:s");
            $totalPurchaseAmount = $_POST['productPrice'];
			$purchaseName = $_POST['productPrice'];
			$loyaltycomputation = $totalPurchaseAmount / 100;

            // Insert purchase record into the database
            $pdoQuery = "INSERT INTO purchase_history (customerName, customerPdate, customerPtotal) VALUES (:customerName, :customerPdate, :customerPtotal)";
            $pdoStatement = $pdoConnect->prepare($pdoQuery);
            $pdoStatement->execute(array(':customerName' => $customerName, ':customerPdate' => $purchaseDate, ':customerPtotal' => $totalPurchaseAmount));
			//update purchase
			$pdoQuery = "UPDATE usertb_account SET purchaseTotal = purchaseTotal + :purchaseAmount, LoyaltyPoints = $loyaltycomputation WHERE Email = :email";
            $pdoResult = $pdoConnect->prepare($pdoQuery);
            $pdoResult->execute(array(':purchaseAmount' => $totalPurchaseAmount, ':email' => $customerName));

            // sweet alert message for succesfull purchased
			echo '<script>';
            echo 'Swal.fire("Purchase Successful!", "Thank you for your purchase.", "success");';
            echo '</script>';
        } catch(PDOException $error) {
			$message = $error->getMessage();
			echo '<script>';
			echo 'Swal.fire("Error!", "'. $message .'", "error");';
			echo '</script>';
        }
    }
?>
</body>
</html>

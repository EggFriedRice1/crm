<?php
require_once '../include/dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validation
    if(empty($_POST["productName"]) || empty($_POST["productDescription"]) || empty($_POST["productPrice"])) {
        echo "Please fill in all fields.";
        exit;
    }

    $productName = $_POST["productName"];
    $productDescription = $_POST["productDescription"];
    $productPrice = $_POST["productPrice"];

    // Image Upload
    $uploadDir = '../image/product_uploaded/';
    $uploadedFile = $uploadDir . basename($_FILES['productImage']['name']);
    $imageFileType = strtolower(pathinfo($uploadedFile, PATHINFO_EXTENSION));
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');

    if (in_array($imageFileType, $allowedExtensions)) {
        if (move_uploaded_file($_FILES['productImage']['tmp_name'], $uploadedFile)) {
            try {
                $pdoQuery = "INSERT INTO products (productName, productDescription, productPrice, productImage) 
                             VALUES (:productName, :productDescription, :productPrice, :productImage)";
                $pdoResult = $pdoConnect->prepare($pdoQuery);
                $pdoResult->execute([
                    'productName' => $productName,
                    'productDescription' => $productDescription,
                    'productPrice' => $productPrice,
                    'productImage' => $uploadedFile
                ]);
                echo "Product added successfully.";
            } catch (PDOException $error) {
                echo "Error: " . $error->getMessage();
            }
        } else {
            echo "Error uploading image.";
        }
    } else {
        echo "Invalid file format. Allowed formats: JPG, JPEG, PNG, GIF.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    
    <link rel="stylesheet" href="../css/addProduct.css">

</head>
<body>
<section id="sidebar">
		<a href="#" class="brand">
			<img src="../admin/Picsart_24-03-29_12-28-55-400.png">
			<span class="text">Clothing</span>
		</a>
		<ul class="side-menu top">
			<li>
				<a href="../admin/dashboard.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="../admin/customers.php">
					<i class='bx bxs-group' ></i>
					<span class="text">Customers</span>
				</a>
			</li>
			<li class="active">
				<a href="../crud/addproducts.php.">
					<i class='bx bxs-group' ></i>
					<span class="text">Products</span>
				</a>
			</li>
			<li>
				<a href="../admin/purchase.php">
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
				<a href="../admin/logout.php" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>

    	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<form action="#">
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<a href="#" class="profile">
				<img src="../image/default_profile.png">
			</a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="table-data">
				<div class="order">
					<div class="head">
                    <h2>Add Product</h2>

                    <div class="container">
                        <form method="POST" enctype="multipart/form-data">
                            <label for="productName">Product Name:</label><br>
                            <input type="text" id="productName" name="productName" required><br>
                            
                            <label for="productDescription">Product Description:</label><br>
                            <textarea id="productDescription" name="productDescription" required></textarea><br>
                            
                            <label for="productPrice">Product Price ($):</label><br>
                            <input type="number" id="productPrice" name="productPrice" min="0" step="0.01" required><br>
                            
                            <label for="productImage">Product Image:</label><br>
                            <input type="file" id="productImage" name="productImage" accept="image/*" required><br>
                            
                            <button type="submit">Add Product</button>
                        </form>
                    </div>

					</div>

				</div>
                
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->


   
</body>
</html>


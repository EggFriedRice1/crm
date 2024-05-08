<?php
session_start();
require_once '../include/dbcon.php';
require_once '../customer_barcode/vendor/autoload.php'; // Include the Composer autoload file

 use Picqer\Barcode\BarcodeGeneratorPNG;
try {
        $pdoConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        if (isset($_POST["addCustomer"])) {
                $FullName = $_POST['regFullname'];
                $Email = $_POST['regEmail'];
                $Contact = $_POST['regContact'];
                $Barcode = uniqid(); // Generate unique ID for barcode
                $BarcodeImagePath = '../uploaded_image/barcodes_img/' . $Barcode . '.png'; // Path to store barcode image
                $Address = $_POST['regAddress'];
                $usertype = '1';
                
                // Generate barcode image
                $generator = new BarcodeGeneratorPNG();
                file_put_contents($BarcodeImagePath, $generator->getBarcode($Barcode, $generator::TYPE_CODE_128));
                
                // Insert user data into database
                $pdoQuery = "INSERT INTO `usertb_account`(`FullName`,`Email`,`Contact`, `Address`, `barcode_image`, `usertype`) 
                             VALUES (:customerFullName, :customerEmail, :customerContact, :customerAddress, :Barcode, :usertype)";
                $pdoResult = $pdoConnect->prepare($pdoQuery);
                $pdoExec = $pdoResult->execute([
                    ":customerFullName" => $FullName,
                    ":customerEmail" => $Email,
                    ":customerContact" => $Contact,
                    ":customerAddress" => $Address,
                    ":Barcode" => $BarcodeImagePath,
                    ":usertype" => $usertype,
                ]);
                
                if ($pdoExec) {
                    
                    header("location:../admin/customers.php");
                } else {
                    $message = 'Failed to register user.';
                }
            }
        
    } catch (PDOException $error) {
        $message = $error->getMessage();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="../admincss/styles.css">
    <link rel="stylesheet" href="../admincss/addCustomer.css">
	<!-- Chart -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<title>AdminHub</title>
</head>
<body>


	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<img src="Picsart_24-03-29_12-28-55-400.png">
			<span class="text">Banana</span>
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
			<a href="#" class="profile">
				<img src="../image/default_profile.png">
			</a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<ul class="add-customer">
				<li>
                <div class="login">
                    <div class="flex">
                    <form method="post">
                        <label for="chk" aria-hidden="true">Add Customer</label>
                        <input type="text" name="regFullname" placeholder="Fullname">
                        <input type="email" name="regEmail" placeholder="Email" required="">
                        <input type="number" name="regContact" placeholder="Contact" required="">
                        <input type="text" name="regAddress" placeholder="Address" required="">
                        <input type="submit" name="addCustomer" class ="buttonn" value="Add">
                    </form>
                    </div>
                
				</li>
		</main>

		<!-- MAIN -->
	</section>
	<!-- CONTENT -->


	

	<script src="script.js"></script>

	<!-- Script -->
</body>
</html>
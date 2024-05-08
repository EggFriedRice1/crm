<?php
require_once '../include/dbcon.php';
session_start();

if (!isset($_SESSION["Email"])) {
    header("location:../login.php");
    exit; 
}

if (!isset($_GET['id'])) {
    header("location: customers.php");
    exit;
}

$customerId = $_GET['id'];

try {

    $pdoQuery = "SELECT * FROM usertb_account WHERE id=:id";
    $pdoResult = $pdoConnect->prepare($pdoQuery);
    $pdoResult->execute(['id' => $customerId]);
    $loyaltyCard = $pdoResult->fetch();
    

    if (!$loyaltyCard) {

        header("location: customers.php");
        exit;
    }
} catch (PDOException $error) {
    echo $error->getMessage();
    exit; 
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
	<link rel="stylesheet" href="../admincss/customers.css">
    <link rel="stylesheet" href="../admincss/loyalty_card.css">

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
			<li >
				<a href="dashboard.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li class="active">
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
            <div class="head-title">
                <!-- Head title content -->
            </div>
            <ul class="box-info">
                <li>
                <div class="loyalty-card">
                    <div class="header">
                        <h2><?php echo $loyaltyCard['FullName']; ?></h2>
                    </div>
                    <div class="content">

                        <img src="Picsart_24-03-29_12-28-55-400.png" alt="" class="loyalty-picture">

                        <h3>Banana Loyalty Card</h3>

                        <div class="barcode-container">
                            <?php if (!empty($loyaltyCard['barcode_image'])): ?>
                                <img src="<?php echo $loyaltyCard['barcode_image']; ?>" class="barcode" alt="Barcode">
                            <?php else: ?>
                                <p>No barcode available.</p>
                            <?php endif; ?>
                        </div>
                        
                    </div>
                </div>

                        </div>
                    </div>
                </li>
            </ul>
        </main>
        <!-- MAIN -->
	</section>
	<!-- CONTENT -->
	<script src="script.js"></script>
</body>
</html>
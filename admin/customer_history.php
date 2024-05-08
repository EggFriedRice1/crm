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
    $customerHistory = $pdoResult->fetch();
    

    if (!$customerHistory) {
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
	<link rel="stylesheet" href="../admincss/purchaseHistory.css">

	<title>AdminHub</title>
</head>
<body>


	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<img src="Picsart_24-03-29_12-28-55-400.png">
			<span class="text">Clothing</span>
		</a>
		<ul class="side-menu top">
			<li>
				<a href="dashboard.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li >
				<a href="customers.php">
					<i class='bx bxs-group' ></i>
					<span class="text">Customers</span>
				</a>
			</li>
			<li class="active">
				<a href="#">
					<i class='bx bxs-shopping-bag-alt' ></i>
					<span class="text">Customer History</span>
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
				<img src="img/people.png">
			</a>
		</nav>
		<!-- NAVBAR -->

		 <!-- MAIN -->
		 <main>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Purchase History</h3>
                        <i class='bx bx-search'></i>
                        <i class='bx bx-filter'></i>
                    </div>
                    <?php
                    try {
                        $pdoQuery = 'SELECT * FROM purchase_history where customerName=:customerName order by customerPdate DESC';
                        $pdoResult = $pdoConnect->prepare($pdoQuery);
						$pdoResult->execute(['customerName' => $customerHistory['Email']]);

                        echo '<table>';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>Email</th>';
						echo '<th>Purchase Type</th>';
                        echo '<th>Purchase Date</th>';
                        echo '<th>Purchase Total</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            echo '<tr>';
                            echo '<td>' . $row['customerName'] . '</td>';
							echo '<td>' . $row['productName'] . '</td>';
                            echo '<td>' . $row['customerPdate'] . '</td>';
                            echo '<td>' . $row['customerPtotal'] . '</td>';
                            echo '</tr>';
                        }

                        echo '</tbody>';
                        echo '</table>';
                    } catch (PDOException $error) {
                        echo $error->getMessage();
                    }
                    ?>
                </div>
            </div>
        </main>
        <!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script.js"></script>
</body>
</html>
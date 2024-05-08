<?php
       require_once '../include/dbcon.php';
        session_start();
        
        $Email = $_SESSION["Email"];
    if($_SESSION["Email"]){
		try{
            $pdoQuery = "SELECT * FROM usertb_account WHERE Email=:Email";
            $pdoResult = $pdoConnect->prepare($pdoQuery);
            $pdoResult->execute(['Email'=> $Email]);
            $user = $pdoResult->fetch();
        }catch(PDOException $error){
            echo $error->getMessage();
            exit;
        }
	}else{
		header("location:../login.php");
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
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Customers</h3>
						<a href="../crud/addCustomer.php">Add Customer</a>
						<nav>
							
							<form action="#">
								<div class="form-input">
									<input type="search" placeholder="Search...">
									<button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
								</div>
							</form>
						</nav>

					</div>
					<?php
						$pdoQuery = 'SELECT * FROM usertb_account WHERE usertype = 1';
						$pdoResult = $pdoConnect->prepare($pdoQuery);
						$pdoResult->execute();

							echo '<table>';
								echo '<thead>';
									echo '<tr>';
										echo '<th>Profile Picture</th>';
										echo '<th>Name</th>';
										echo '<th>Email</th>';
										echo '<th>Address</th>';
										echo '<th>Contact</th>';
										echo '<th>Total Visits</th>';
										echo '<th>Loyalty Points</th>';
										echo '<th>Purchase Total</th>';
										echo '<th>Notes</th>';
										echo '<th>Actions</th>';
									echo '</tr>';
								echo '</thead>';
							

							while($row = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
								extract($row);
								echo '<tbody>';
									echo '<tr>';
										echo '<td>';
										if ($profpic == '') {
											echo '<a href="customer_profile.php?id='.$id.'"><img src="../image/default_profile.png" class="profile"></a>';
										} else {
											echo '<a href="customer_profile.php?id='.$id.'"><img src="../uploaded_image/'.$profpic.'" class="profile"></a>';
										}
										echo '</td>';
										echo '<td>'.$FullName.'</td>';
										echo '<td>'.$Email.'</td>';
										echo '<td>'.$Address.'</td>';
										echo '<td>'.$Contact.'</td>';
										echo '<td>'.$totalVisits.'</td>';
										echo '<td>'.$LoyaltyPoints.'</td>';
										echo '<td>'.$purchaseTotal.'</td>';
										echo '<td>'.$notes.'</td>';
										echo "<td><a href='../crud/updatecustomer.php?id=$id' class='btn'>Update</a> <a href='../crud/deletecustomer.php?id=$id' class='btn'>Delete</a></td>";
									echo '</tr>';
								}

								echo '</tbody>';
							echo '</table>';
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
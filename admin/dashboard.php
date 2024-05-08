<?php
		session_start();
       require_once '../include/dbcon.php';
    
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
	<link rel="stylesheet" href="../admincss/styles.css">
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

		<!-- Visitors database -->
		<?php
			$pdoQuery = 'SELECT * FROM counter where id = 1';
			$pdoResult = $pdoConnect->prepare($pdoQuery);
			$pdoResult->execute();
			$fetch = $pdoResult->fetch();

			$date_now = date("m-d-Y");
			$date2=$fetch['date']; 
			$pdodate=date('m-d-Y',strtotime($date2));
					
			if($date_now > $pdodate){
			$pdoQuery = $pdoConnect->prepare("UPDATE counter SET tvisit = 0, date = null WHERE id = 1");
			$pdoResult = $pdoQuery->execute();
			}
		?>

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
			<ul class="box-info">
				<li>
					<i class='bx bxs-group' ></i>
					<span class="text">
						<h3>
							<?php
									echo $fetch['visits'];
							?>
						</h3>
						<p>Total Visits</p>
					</span>
				</li>
                <li>
					<i class='bx bxs-group' ></i>
					<span class="text">
						<h3>	
							<?php	
									echo $fetch['tvisit'];	
							?>
						</h3>
						<p>Today Visits</p>
					</span>
				</li>
			</ul>

			<div class="table-container">
            	<div class="chart-container">
                	<div class="head">
                    	<h3>Visitors Chart</h3>
                	</div>

                	<canvas id="visitorChart" ></canvas> <!-- Chart -->
            	</div>

				<div class="topCustomer-container">
					<div class="head">
						<h3>Top Customer</h3>
					</div>
					<?php
						try {
							$pdoQuery = 'SELECT * FROM usertb_account WHERE usertype = 1 ORDER BY purchaseTotal DESC LIMIT 1';
							$pdoResult = $pdoConnect->prepare($pdoQuery);
							$pdoResult->execute();
							$topCustomer = $pdoResult->fetch(PDO::FETCH_ASSOC);
						} catch (PDOException $error) {
							echo $error->getMessage();
							exit;
						}

						
					?>
					<div class="customer-details">
						<?php if ($topCustomer) : ?>
							<img src="../uploaded_image/<?php echo $topCustomer['profpic'] ? $topCustomer['profpic'] : 'default_profile.png'; ?>">
							<p>Name: <?php echo $topCustomer['FullName']; ?></p>
							<p>Email: <?php echo $topCustomer['Email']; ?></p>
							<p>Address: <?php echo $topCustomer['Address']; ?></p>
							<p>Total Visits: <?php echo $topCustomer['totalVisits']; ?></p>
							<p>Loyalty Points: <?php echo $topCustomer['LoyaltyPoints']; ?></p>
							<p>Purchase Total: <?php echo $topCustomer['purchaseTotal']; ?></p>
						<?php else : ?>
							<p>No top customer found.</p>
						<?php endif; ?>
    				</div>
				</div>

            	</div>
        	</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<!-- Script -->
	<script>
    var totalVisitors = <?php echo $fetch['visits']; ?>;
    var todayVisitors = <?php echo $fetch['tvisit']; ?>;

	var ctx = document.getElementById('visitorChart').getContext('2d');
    var visitorChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Visits', 'Today Visits'],
            datasets: [{
                label: 'Visits Count',
                data: [totalVisitors, todayVisitors],
                backgroundColor: [
					'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 242, 198, 0.5)',
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
					'rgb(255, 206, 38)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

	</script>

	

	<script src="script.js"></script>

	<!-- Script -->
</body>
</html>
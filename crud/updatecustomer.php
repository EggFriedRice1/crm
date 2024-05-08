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
	<link rel="stylesheet" href="../admincss/styles.css">
    <link rel="stylesheet" href="../admincss/update.css">

	<title>AdminHub</title>
</head>
<body>


	<!-- SIDEBAR -->
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
			<li class="active">
				<a href="#">
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
        <?php
        if(!empty($_POST['modify'])){
            if(empty($_FILES['profile']["name"])){
                $fullName = htmlspecialchars($_POST['FName']);
                $email = htmlspecialchars($_POST['Email']);
                $address = htmlspecialchars($_POST['Address']);
                $contact = htmlspecialchars($_POST['Contact']);
                $notes = htmlspecialchars($_POST['addNotes']);
                $imgname = $user['profpic'];
                
                $pdoQuery = $pdoConnect->prepare("UPDATE usertb_account SET Email = :email, Contact = :Contact, Address = :Address, FullName = :fullName, profpic = :img, notes = :notes WHERE id = :id");
                $pdoResult = $pdoQuery->execute(array(
                    'fullName' => $fullName,
                    'email' => $email,
                    'Address' => $address,
                    'Contact' => $contact,
                    'img' => $imgname,
                    'notes' => $notes,
                    'id' => $_GET["id"]
                    ));
                
                if($pdoResult){
                move_uploaded_file($imgtmpname, $imgfolder);
                $loggedInUser = $_SESSION["Email"];
                $pdoQuery = "INSERT INTO `audit_trail`(`action`,`user`)VALUES('User Updated',:user)";
                $pdoResult = $pdoConnect->prepare($pdoQuery);
                $pdoResult->execute([':user' => $loggedInUser]);

                header('location:/crm/admin/customers.php');
                }
                
            }elseif(!empty($_FILES['profile']["name"])){
                $fullName = htmlspecialchars($_POST['FName']);
                $email = htmlspecialchars($_POST['Email']);
                
                $imgname = $_FILES["profile"]["name"];
                $imgsize = $_FILES["profile"]["size"];
                $imgtmpname = $_FILES["profile"]["tmp_name"];
                $imgfolder = '../uploaded_image/'.$imgname;
                
                $pdoQuery = $pdoConnect->prepare("UPDATE usertb_account SET Email = :email, FullName = :fullName, profpic = :img WHERE id = :id");
                $pdoResult = $pdoQuery->execute(array(
                    'fullName' => $fullName,
                    'email' => $email,
                    'img' => $imgname,
                    'id' => $_GET["id"]
                    ));
                
                if($pdoResult){
                move_uploaded_file($imgtmpname, $imgfolder);
                $loggedInUser = $_SESSION["Email"];
                $pdoQuery = "INSERT INTO `audit_trail`(`action`,`user`)VALUES('User Updated',:user)";
                $pdoResult = $pdoConnect->prepare($pdoQuery);
                $pdoResult->execute([':user' => $loggedInUser]);

                header('location:/crm/admin/customers.php');
                }
            }
        }

        $pdoQuery = $pdoConnect->prepare("SELECT * FROM usertb_account WHERE id = :id");
        $pdoQuery->execute(array(':id' => $_GET['id']));
        $pdoResult = $pdoQuery->fetchAll();
        $pdoConnect = null;
    ?>
    <br>
    <div class="update-profile">
        <form action="updatecustomer.php?id=<?php echo $_GET["id"]; ?>" method="post" enctype="multipart/form-data">
            <?php
                if (isset($user['profpic']) == '') {
                    echo '<img src="../image/profile.png">';
                } else {
                    $imagePath = '../uploaded_image/' . $pdoResult[0]['profpic']; 
                    if (file_exists($imagePath)) {
                        echo '<img src="' . $imagePath . '">';
                    } else {
                        echo '<img src="../image/profile.png">'; 
                    }
                }
                if (isset($message)) {
                    echo '<div class="message">' . $message . '</div>';
                }
            ?>
            
            <div class="flex">
                <div class="inputBox">
                    <span>Fullname: </span>
                    <input type="text" name="FName" value="<?php echo $pdoResult[0]['FullName']; ?>" required placeholder="FullName" class="box">
                    <span>Email: </span>
                    <input type="email" name="Email" value="<?php echo $pdoResult[0]['Email']; ?>" required placeholder="Email" class="box">
                    <span>Address: </span>
                    <input type="text" name="Address" value="<?php echo $pdoResult[0]['Address']; ?>" required placeholder="Address" class="box">
                    <span>Contact: </span>
                    <input type="number" name="Contact" value="<?php echo $pdoResult[0]['Contact']; ?>" required placeholder="Contact" class="box">
                    <span>Notes: </span>
                    <input type="text" name="addNotes" value="<?php echo $pdoResult[0]['notes']; ?>" required placeholder="Notes" class="box">
                    <span>Change Profile: </span>
                    <input type="file" name="profile" accept="image/jpg, image/jpeg, image/png" class="box">
                </div>
            </div>
        <input type="submit" name="modify" value="Save Changes" class="btn">
        <a href="/crm/admin/customers.php" class="delete-btn">Cancel</a>
        </form>
    </div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	<script src="script.js"></script>
</body>
</html>
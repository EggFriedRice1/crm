<?php
 session_start();
 require_once './include/dbcon.php';
 require_once './customer_barcode/vendor/autoload.php'; // Include the Composer autoload file

 use Picqer\Barcode\BarcodeGeneratorPNG;
 
 try{
    $pdoConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if(isset($_POST["login"])){
        if(empty($_POST["Email"]) || empty($_POST["PassWord"])){
            $message = '<label>All fields are required<label>';
    }else{
        $pdoQuery = "SELECT * FROM usertb_account WHERE Email=:Email";
        $pdoResult = $pdoConnect->prepare($pdoQuery);
        $pdoResult->execute(['Email'=>$_POST["Email"]]);
        
        $user = $pdoResult->fetch();
        
        if($user && password_verify($_POST["PassWord"], $user["PassWord"])){
            $_SESSION["Email"] = $_POST["Email"];

            if($user["usertype"] == "0"){
              $loggedInUser = $_SESSION["Email"];
              $pdoQuery = "INSERT INTO `audit_trail`(`action`,`user`)VALUES('User logged in',:user)";
              $pdoResult = $pdoConnect->prepare($pdoQuery);
              $pdoResult->execute([':user' => $loggedInUser]);
              header("location:admin/dashboard.php");
            }else{
              $pdoQuery = $pdoConnect->prepare("UPDATE counter SET visits = visits+1, tvisit = tvisit+1, date = now() where id = 1");
              $pdoResult = $pdoQuery->execute();
              $pdoQuery = $pdoConnect->prepare("UPDATE usertb_account SET totalVisits = totalVisits+1, LoyaltyPoints = LoyaltyPoints+1 where Email = :Email");
              $pdoQuery->execute(['Email' => $_POST["Email"]]);
              

              $loggedInUser = $_SESSION["Email"];
              $pdoQuery = "INSERT INTO `audit_trail`(`action`,`user`)VALUES('User logged in',:user)";
              $pdoResult = $pdoConnect->prepare($pdoQuery);
              $pdoResult->execute([':user' => $loggedInUser]);
              header("location:customer/dashboard_customer.php");
              }
        }else{
            $message = "<label>Incorrect Username or Password<label>";
        }
        }
            }
    }catch(PDOException $error){
        $message = $error->getMessage();
    }

    try {
        $pdoConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        if (isset($_POST["register"])) {
            if ($_POST['regPassword'] !== $_POST['regConfirmpass']) {
                $message = "<label>Password Doesn't Match</label>";
            } else {
                $FullName = $_POST['regFullname'];
                $Email = $_POST['regEmail'];
                $Password = password_hash($_POST['regPassword'], PASSWORD_DEFAULT);
                $usertype = "1";
                $Barcode = uniqid(); // Generate unique ID for barcode
                $BarcodeImagePath = 'uploaded_image/barcodes_img/' . $Barcode . '.png'; // Path to store barcode image
                
                // Generate barcode image
                $generator = new BarcodeGeneratorPNG();
                file_put_contents($BarcodeImagePath, $generator->getBarcode($Barcode, $generator::TYPE_CODE_128));
                
                // Insert user data into database
                $pdoQuery = "INSERT INTO `usertb_account`(`FullName`,`Email`,`PassWord`,`usertype`, `barcode_image`) 
                             VALUES (:FullName, :Email, :PassWord, :usertype, :barcode_image)";
                $pdoResult = $pdoConnect->prepare($pdoQuery);
                $pdoExec = $pdoResult->execute([
                    ":PassWord" => $Password,
                    ":FullName" => $FullName,
                    ":Email" => $Email,
                    ":usertype" => $usertype,
                    ":barcode_image" => $BarcodeImagePath, 
                ]);
                
                if ($pdoExec) {
                    
                    header("location:login.php");
                } else {
                    $message = 'Failed to register user.';
                }
            }
        }
    } catch (PDOException $error) {
        $message = $error->getMessage();
    }
    ?>
    
<!DOCTYPE html>
<html>
    <head>
        <title>CRM login</title>
        <link rel="stylesheet" type="text/css" href="css/login.css">
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    </head>
<body>
        <?php
        if (isset($message)) {
            echo '<div class="message">' . $message . '</div>';
        }
        ?>
        <div class="main">        
            
            <input type="checkbox" id="chk" aria-hidden="true">
            
            <div class="signup">
                <form method="post">
                    <label for="chk" aria-hidden="true">Login</label>
                    <input type="email" name="Email" placeholder="Email">
                    <input type="password" name="PassWord" placeholder="Password">
                    <button name="login">Login</button>
                </form>
            </div>
    
            <div class="login">
                <form method="post">
                    <label for="chk" aria-hidden="true">Sign Up</label>
                    <input type="text" name="regFullname" placeholder="Fullname">
                    <input type="email" name="regEmail" placeholder="Email" required="">
                    <input type="password" name="regPassword" placeholder="Password">
                    <input type="password" name="regConfirmpass" placeholder="Confirm Password">
                    <input type="submit" name="register" class ="buttonn" value="Sign up">
                </form>
            </div>
        </div>
    
        <script type="text/javascript">
            function preventBack(){window.history.forward()};
            setTimeout("preventBack()", 0);
            window.onunload=function(){null;}
        </script>
</body>
</html>
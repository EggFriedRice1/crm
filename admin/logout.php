<?php
    session_start();

    require_once '../include/dbcon.php';

    if (isset($_SESSION["Email"])){
        $loggeInUser = $_SESSION["Email"];
        $pdoQuery = "INSERT INTO `audit_trail`(`action`,`user`)VALUES('User Logged Out', :user)";
        $pdoResult = $pdoConnect->prepare($pdoQuery);
        $pdoResult->execute([':user'=> $loggeInUser]);
    }

    unset($_SESSION['user']);
    session_destroy();
    header('location:../login.php');
?>
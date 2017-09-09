<?php 
session_start();
include 'core/connect.php';
include 'functions/secure.php';

checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);

$connection = connectToDb();

// destroy cookies and sessions
setcookie("userloggedin", "");
setcookie('user_id', '', time() - 3600);
setcookie('username', '', time() - 3600);

$username = "";

// brisanje ip adrese
$ip_address = "";
$idsess = preg_replace("/[^0-9]/", "", $_SESSION['AUTHOR_USERID']);
$updateDateLogedIN = $connection->prepare("UPDATE authors SET author_ipaddress = :ip WHERE author_id = :id LIMIT 1");
$updateDateLogedIN->bindParam(":ip", $ip_address);
$updateDateLogedIN->bindParam(":id", $idsess);
$result = $updateDateLogedIN->execute();
$connection = NULL;

unset($_SESSION['AUTHOR_USERID']);
unset($_SESSION["AUTHOR_EMAIL"]);
session_unset();
session_destroy();


//redirect
header("location: LogIn.php");

exit();
?>
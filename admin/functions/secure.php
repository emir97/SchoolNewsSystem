<?php

function base64_url_encode($input) {
 return strtr(base64_encode($input), '+/=', '-_,');
}

function base64_url_decode($input) {
 return base64_decode(strtr($input, '-_,', '+/='));
}

function detect_get($getIndex) {
	$check = isset($_GET[base64_url_encode($getIndex)]) ? TRUE : FALSE;
	return $check;
}
function returnheader($location){
	$returnheader = header("location: $location");
	return $returnheader;
}
function generateRandomString($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!#$%/*';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function cryptPass($input){
    return SHA1($input).md5($input);
}
function checkIfLoggedIn($session, $session2){
    if(!isset($session)){
        session_unset();
        session_write_close();
        session_destroy();
        header('location: LogIn.php');
        exit();
    }

    $ID = preg_replace("/[^0-9]/", "", $session);

    $connect = connectToDb();
    $fetchAdmin = $connect->prepare("SELECT author_id, author_email FROM authors WHERE author_id = :id LIMIT 1");
    $fetchAdmin->bindParam(":id", $ID);
    $fetchAdmin->execute();
    if($fetchAdmin->rowCount() != 1) {
        session_unset();
        session_write_close();
        session_destroy();
        header('location: LogIn.php');
        exit();
    }

    foreach ($fetchAdmin as $key => $value) {
        $email = $value['author_email'];
    }
    $cryptedEmail = cryptPass($email);
    if($cryptedEmail != $session2){
        session_unset();
        session_write_close();
        session_destroy();
        header('location: LogIn.php');
        exit();
    }
    $checkIPAddress = $connect->prepare("SELECT author_ipaddress FROM authors WHERE author_id = :id LIMIT 1");
    $checkIPAddress->bindParam(":id", $ID);
    $checkIPAddress->execute();
    if($checkIPAddress->rowCount() != 1){

    }
    foreach ($checkIPAddress as $key => $value) {
        if($value['author_ipaddress'] == "" || $value['author_ipaddress'] == NULL) {
            session_unset();
            session_write_close();
            session_destroy();
            header('location: LogIn.php');
            exit();
        }
    }
    $connect = NULL;

}
function checkIsAdmin($session){
    if(!isset($session)){
        session_unset();
        session_write_close();
        session_destroy();
        header('location: LogIn.php');
        exit();
    } else {
        $connectToDb = connectToDb();
        $fetchAdmin = $connectToDb->prepare("SELECT * FROM author_permission WHERE author_id = :id LIMIT 1");
        $fetchAdmin->bindParam(":id", $session);
        $result = $fetchAdmin->execute();
        if($result){
            if ($fetchAdmin->rowCount() == 1) {
                foreach ($fetchAdmin as $key => $value) {
                     if($value['is_admin'] == 1)
                        return TRUE;
                 } 
            }
        } else {
            return false;
        }
    }
    return false;
}

?>
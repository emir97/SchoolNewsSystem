<?php 
include_once '../core/connect.php';
include_once '../functions/secure.php';
session_start();
checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
if(isset($_GET[base64_url_encode("notifyOperation")])){
	if(base64_url_decode($_GET[base64_url_encode("notifyOperation")]) == "newnotify"){
	if($_POST['notify_content'] == ""){
          header('Location: ../500.php');

          exit();
        }
        //konekcija na bazu
        $connectDb = connectToDb();
        	
          //autor
        $authorID = $_SESSION['AUTHOR_USERID'];
           
		  
        //datum objave
        $date = date('o.m.d');
		  
        //sadržaj obavijesti
        $notifyContent = $_POST['notify_content'];
        
        //ID obavijesti
        for($a = 1; $a < 7; $a++){
            $checkID = $connectDb->prepare("SELECT * FROM notify WHERE notify_id = :id");
            $checkID->bindParam(":id", $a);
            $checkID->execute();
            if($checkID->rowCount() == 0){
                    $ID = $a;
                    break;
            }
        }
	
        //spremanje u bazu nove obavjesti	  		
	
              $insertNotify = $connectDb->prepare("INSERT INTO notify (notify_id, notify_content, author_id, notify_date) VALUES(:ID, :content, :author, :date)");
              $insertNotify->bindParam(":ID", $ID);
              $insertNotify->bindParam(":content", $notifyContent);
              $insertNotify->bindParam(":author", $authorID);
              $insertNotify->bindParam(":date", $date);
              if($insertNotify->execute()){
                  $connectDb = NULL;
                  header('Location: ../obavijesti.php');
                  exit();
                  
              }
              else{
                  $connectDb = NULL;
                  header('Location: ../500.php');
                  exit();
              }
        } else if(base64_url_decode($_GET[base64_url_encode("notifyOperation")]) == "update"){
                
                if($_POST['notify_content'] == ""){
                        header('Location: ../500.php');
	                 exit();
                }
                
                //provjera da li je poslan ID u URL
             if(!isset($_GET[base64_url_encode("notifyID")])){
                     header('Location: ../obavijesti.php');
                     exit();
             }
             //konekcija na bazu
             $connectDb = connectToDb();
             //dohvatanje podataka obavijesti
             $ID = base64_url_decode($_GET[base64_url_encode("notifyID")]);
             $notifyContent = $_POST['notify_content'];
             //izmjena obavijesti
             $updateNotify = $connectDb->prepare("UPDATE notify SET notify_content = :content WHERE notify_id = :id");
             $updateNotify->bindParam(":content", $notifyContent);
             $updateNotify->bindParam(":id", $ID);
             if($updateNotify->execute()){
                $connectDb = NULL;
                header('Location: ../obavijesti.php');
	               exit();
             } else {
                $connectDb = NULL;
                 header('Location: ../500.php');
	        exit();
             }
             $connectDb = NULL;
        }
	
} else {
	header('Location: ../obavijesti.php');
	exit();
}


?>
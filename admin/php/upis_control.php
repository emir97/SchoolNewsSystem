<?php 
	include '../core/connect.php';
	include '../functions/secure.php';
	session_start();
	ob_start();
	checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
	if(!checkIsAdmin($_SESSION['AUTHOR_USERID'])){
    header('location: ../index.php');
    exit();
	}
	
	if(isset($_GET[base64_url_encode("whatUpload")])){
		$whatUpload = base64_url_decode($_GET[base64_url_encode("whatUpload")]);
		
		$connect = connectToDb();

		$editDate = date('o-m-d'). " " . date('H:i:s');

		$date = date('o-m-d');

		$time = date('H:i:s');

		if($whatUpload == "staupisati"){

			if($_POST['title'] == "" || $_POST['content'] == ""){
				echo "aa";
				exit();
			}

			$upisContent = $_POST['content'];

			$upisTitle =htmlspecialchars(strip_tags($_POST['title']));

			$checkIfExist = $connect->query("SELECT * FROM upis WHERE link_title = 'staupisati' LIMIT 1");
			if($checkIfExist->rowCount() == 1){
				
				$updateUpis = $connect->prepare("UPDATE upis SET upis_title = :title ,
																 upis_content = :content, 
																 upis_edit_time = :edit_time,
																 author_id = :authorID WHERE link_title = 'staupisati'");
				$updateUpis->bindParam(":title", $upisTitle);
				$updateUpis->bindParam(":edit_time", $editDate);
				$updateUpis->bindParam(":content", $upisContent);
				$updateUpis->bindParam(":authorID", $_SESSION['AUTHOR_USERID']);
				$result = $updateUpis->execute();
				if($result){
					$url = "../upis.php?".base64_url_encode("stranica")."=".base64_url_encode("staupisati");
					$connect = NULL;
					header("location: $url");
					exit();
				} else {
					$connect = NULL;
					header("location: ../500.php");
					exit();
				}


			} else {
				$insertData = $connect->prepare("INSERT INTO upis(upis_title, upis_content, upis_date_upload, upis_time_upload, author_id, 	link_title)
												VALUES (:title, :content, :date_upload, :time_upload, :author, :link)");
				$insertData->bindParam(":title", $upisTitle);
				$insertData->bindParam(":content", $upisContent);
				$insertData->bindParam(":date_upload", $date);
				$insertData->bindParam(":time_upload", $time);
				$insertData->bindParam(":author", $_SESSION['AUTHOR_USERID']);
				$insertData->bindParam(":link", $whatUpload);
				$result = $insertData->execute();
				if($result){
					$url = "../upis.php?".base64_url_encode("stranica")."=".base64_url_encode($whatUpload);
					$connect = NULL;
					header("location: $url");
					exit();
				} else {
					$connect = NULL;
					header("location: ../500.php");
					exit();
				}

			}
		} else if($whatUpload == "postupakIBodovanjePriUpisu") {

			if($_POST['title'] == "" || $_POST['content'] == ""){
				echo "aa";
				exit();
			}

			$upisContent = $_POST['content'];
			
			$upisTitle =htmlspecialchars(strip_tags($_POST['title']));

			$checkIfExist = $connect->query("SELECT * FROM upis WHERE link_title = 'postupakIBodovanjePriUpisu' LIMIT 1");
			if($checkIfExist->rowCount() == 1){
				
				$updateUpis = $connect->prepare("UPDATE upis SET upis_title = :title ,
																 upis_content = :content, 
																 upis_edit_time = :edit_time,
																 author_id = :authorID WHERE link_title = 'postupakIBodovanjePriUpisu'");
				$updateUpis->bindParam(":title", $upisTitle);
				$updateUpis->bindParam(":edit_time", $editDate);
				$updateUpis->bindParam(":content", $upisContent);
				$updateUpis->bindParam(":authorID", $_SESSION['AUTHOR_USERID']);
				$result = $updateUpis->execute();
				if($result){
					$url = "../upis.php?".base64_url_encode("stranica")."=".base64_url_encode($whatUpload);
					$connect = NULL;
					header("location: $url");
					exit();
				} else {
					$connect = NULL;
					header("location: ../500.php");
					exit();
				}


			} else {
				$insertData = $connect->prepare("INSERT INTO upis(upis_title, upis_content, upis_date_upload, upis_time_upload, author_id, 	link_title)
												VALUES (:title, :content, :date_upload, :time_upload, :author, :link)");
				$insertData->bindParam(":title", $upisTitle);
				$insertData->bindParam(":content", $upisContent);
				$insertData->bindParam(":date_upload", $date);
				$insertData->bindParam(":time_upload", $time);
				$insertData->bindParam(":author", $_SESSION['AUTHOR_USERID']);
				$insertData->bindParam(":link", $whatUpload);
				$result = $insertData->execute();
				if($result){
					$url = "../upis.php?".base64_url_encode("stranica")."=".base64_url_encode($whatUpload);
					$connect = NULL;
					header("location: $url");
					exit();
				} else {
					$connect = NULL;
					header("location: ../500.php");
					exit();
				}
		}
	} else if($whatUpload == "vodicZaStudente") {

		if(isset($_FILES['student_file'])){
						//dohvatanje varijabli iz forme
				          $file = $_FILES['student_file']['name']; // ime slike
						  $fileTmp = $_FILES['student_file']['tmp_name']; // privremeno ime slike
				          $fileType = $_FILES["student_file"]["type"]; // ekstenzija slike
				          $fileSize = $_FILES["student_file"]["size"]; // velicina slike u bajtovima
				          $fileErrorMsg = $_FILES["student_file"]["error"]; // 0 ako ne postoji greska 1 ako postoji
				          $kaboom = explode(".", $file); // rastavljanje imena slike i ekstenzije
				          $fileExt = end($kaboom); // dohvatanje zadnjeg polja niza
				          $fileExt = strtolower($fileExt);
				          
				          if (!$fileTmp) { // ako fajl nije izabran
				            header('location: ../500.php');
				            exit();
				          } else if($fileSize > 8388608) { // ako je velicina slike veca od 5
				              header('location: ../500.php');
				              unlink($fileTmp); // uklanjanje slike sa servera
				              exit();
				          } else if (!preg_match("/.(pdf|docx|html|htm|php|txt|GDOC|gdoc)$/i", $file) ) {
				               // provjera ekstenzije slike
				               header('location: ../500.php');
				               unlink($fileTmp); // uklanjanje slike sa servera
				               exit();
				          } else if ($fileErrorMsg == 1) { // ako postoji greska u uploadu
				              header('location: ../500.php');
				              exit();
				          }
				          //prebacivanje slike u folder
				          $moveResult = move_uploaded_file($fileTmp, "../../vodic/$file");
				          // provjera da li je slika uploadovana na server
				          if ($moveResult != true) {
				              echo "ERROR: Fajl nije poslan na server.";
				              unlink($fileTmp); // uklanjanje slike iz tmp foldera
				              exit();
				          }
				          
			} else {
				$connect = NULL;
				header("location: ../500.php");
				exit();
			}

			$upisTitle = "Vodič za učenike";
			$upisContent = "";

		$checkIfExist = $connect->query("SELECT * FROM upis WHERE link_title = 'vodicZaStudente' LIMIT 1");
			if($checkIfExist->rowCount() == 1){
			
				$updateUpis = $connect->prepare("UPDATE upis SET upis_title = :title ,
																 upis_content = :content, 
																 upis_edit_time = :edit_time,
																 upis_data = :data, 
																 author_id = :authorID WHERE link_title = 'vodicZaStudente'");
				$updateUpis->bindParam(":title", $upisTitle);
				$updateUpis->bindParam(":edit_time", $editDate);
				$updateUpis->bindParam(":content", $upisContent);
				$updateUpis->bindParam(":data", $file);
				$updateUpis->bindParam(":authorID", $_SESSION['AUTHOR_USERID']);
				$result = $updateUpis->execute();
				if($result){
					$url = "../upis.php?".base64_url_encode("stranica")."=".base64_url_encode($whatUpload);
					$connect = NULL;
					header("location: $url");
					exit();
				} else {
					$connect = NULL;
					header("location: ../500.php");
					exit();
				}


			} else {
				$insertData = $connect->prepare("INSERT INTO upis(upis_title, upis_content, upis_date_upload, upis_time_upload, author_id, upis_data, link_title)
												VALUES (:title, :content, :date_upload, :time_upload, :author, :data, :link)");
				$insertData->bindParam(":title", $upisTitle);
				$insertData->bindParam(":content", $upisContent);
				$insertData->bindParam(":date_upload", $date);
				$insertData->bindParam(":time_upload", $time);
				$insertData->bindParam(":time_upload", $time);
				$insertData->bindParam(":author", $_SESSION['AUTHOR_USERID']);
				$insertData->bindParam(":data", $file);
				$insertData->bindParam(":link", $whatUpload);
				$result = $insertData->execute();
				if($result){
					$url = "../upis.php?".base64_url_encode("stranica")."=".base64_url_encode($whatUpload);
					$connect = NULL;
					header("location: $url");
					exit();
				} else {
					$connect = NULL;
					header("location: ../500.php");
					exit();
		}		}
	}
}

?>
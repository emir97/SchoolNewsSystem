<?php 
include('../core/connect.php'); 
include('../functions/secure.php');
session_start();
ob_start();
checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
if(!checkIsAdmin($_SESSION['AUTHOR_USERID'])){
    header('location: index.php');
    exit();
}
//provjera da li je poslana zeljena akcija za izvrsiti u url
if(isset($_GET[base64_url_encode("action")])) {
	// akcija je poslana smjestanje u varijablu
	$action = base64_url_decode($_GET[base64_url_encode("action")]);

	// provjera akcije
	if($action == "delete") {
		// provjera da li je ID poslan u URL 
		if(!isset($_GET[base64_url_encode("ID")])) {
			header('location: ../500.php');
			exit();
		}
		// provjera da li je vrsta zanimanja poslana u url
		if(!isset($_GET[base64_url_encode("duration")])) {
			header('location: ../500.php');
			exit();
		}

		$duration = base64_url_decode($_GET[base64_url_encode("duration")]);
		// provjera da li vrsta zanimanja ispravna
		if($duration != "3" && $duration != "4"){
			header('location: ../500.php');
			exit();
		}
		// ID zanimanja
		$ID = base64_url_decode($_GET[base64_url_encode("ID")]);
		//konekcija na bazu
		$connect = connectToDb();
		//brisanje zanimanja
		$delete = $connect->prepare("DELETE FROM zanimanja WHERE zanimanje_id = :id");
		$delete->bindParam(":id", $ID);
		$result = $delete->execute();
		//provjera da li je zanimanje obrisano
		if($result) {
			// url za preusmjeravanje na zanimanja
			$url = "../zanimanja.php?".base64_url_encode("trajanjeSkolovanja")."=".base64_url_encode($duration);
			// zatvaranje konekcije sa bazom
			$connect = NULL;
			// presumjeravanje na zanimanja
			header("location: $url");
			// zatvaranje skripte
			exit();
			// nije ispravno obrisano zanimanje 
		} else {
			// zatvaranje konekcije sa bazom
			$connect = NULL;
			// preusmjeravanje na straqnicu za javljanje greske
			header('location: ../500.php');
			//zatvaranje skripte
			exit();
		}
	} else if ($action == "insert") {

		// provjera da li je vrsta zanimanja poslana u url
		if(!isset($_GET[base64_url_encode("duration")])) {
			header('location: ../500.php');
			exit();
		}
		// dohvatanje vrste zanimanja
		$duration = base64_url_decode($_GET[base64_url_encode("duration")]);
		// provjera da li vrsta zanimanja ispravna
		if($duration != "3" && $duration != "4"){
			header('location: ../500.php');
			exit();
		}
		// provjera da li su podaci ispravno popunjeni 
		if($_POST['title'] == "" || $_POST['postContent'] == "") {
			header('location: ../500.php');
			exit();
		}

		// naslov zanimanja
		$title = strip_tags($_POST['title']);
		// sadrzaj zanimanja
		$content = $_POST['postContent'];
		// datum 
		$date = date('o.m.d G:i:s');
		// autor
		$author = $_SESSION['AUTHOR_USERID'];

		// konekcija na bazu 
		$connect = connectToDb();

	   	//dohvatanje varijabli iz forme
          $postImage = $_FILES['postImage']['name']; // ime slike
		  $postImageTmp = $_FILES['postImage']['tmp_name']; // privremeno ime slike
          $fileType = $_FILES["postImage"]["type"]; // ekstenzija slike
          $fileSize = $_FILES["postImage"]["size"]; // velicina slike u bajtovima
          $fileErrorMsg = $_FILES["postImage"]["error"]; // 0 ako ne postoji greska 1 ako postoji
          $kaboom = explode(".", $postImage); // rastavljanje imena slike i ekstenzije
          $fileExt = end($kaboom); // dohvatanje zadnjeg polja niza
          
          if (!$postImageTmp) { // ako fajl nije izabran
            header('location: ../500.php');
            exit();
          } else if($fileSize > 8388608) { // ako je velicina slike veca od 8 MB
              header('location: ../500.php');
              unlink($postImageTmp); // uklanjanje slike sa servera
              exit();
          } else if (!preg_match("/.(gif|jpg|png|jpeg)$/i", $postImage) ) {
               // provjera ekstenzije slike
               header('location: ../500.php');
               unlink($postImageTmp); // uklanjanje slike sa servera
               exit();
          } else if ($fileErrorMsg == 1) { // ako postoji greska u uploadu
              header('location: ../500.php');
              exit();
          }

          //prebacivanje slike u folder
          $moveResult = move_uploaded_file($postImageTmp, "../../images/zanimanja/resized_$postImage");
          // provjera da li je slika uploadovana na server
          if ($moveResult != true) {
              echo "ERROR: Fajl nije poslan na server.";
              unlink($postImageTmp); // uklanjanje slike iz tmp foldera
              exit();
          }
          

          include_once("ak_php_img_lib_1.0.php");
          $target_file = "../../images/zanimanja/resized_$postImage";
          $resized_file = "../../images/zanimanja/$postImage";
          $wmax = 750;
          $hmax = 390;
          ak_img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
          //brisanje prave slike
          unlink($target_file);

          // unosenje zanimanja u bazu
          $insertData = $connect->prepare("INSERT INTO zanimanja (zanimanje_title, zanimanje_content, zanimanje_date_upload, zanimanje_image, author_id, zanimanje_duration) 
          												VALUES (:title, :content, :date_upload, :image, :author, :duration)");
          $insertData->bindParam(":title", $title);
          $insertData->bindParam(":content", $content);
          $insertData->bindParam(":date_upload", $date);
          $insertData->bindParam(":image", $postImage);
          $insertData->bindParam(":author", $author);
          $insertData->bindParam(":duration", $duration);
          $result = $insertData->execute();
          //provjera da li je zanimanje obrisano
		if($result) {
			// url za preusmjeravanje na zanimanja
			$url = "../zanimanja.php?".base64_url_encode("trajanjeSkolovanja")."=".base64_url_encode($duration);
			// zatvaranje konekcije sa bazom
			$connect = NULL;
			// presumjeravanje na zanimanja
			header("location: $url");
			// zatvaranje skripte
			exit();
			// nije ispravno obrisano zanimanje 
		} else {
			// zatvaranje konekcije sa bazom
			$connect = NULL;
			// preusmjeravanje na straqnicu za javljanje greske
			header('location: ../500.php');
			//zatvaranje skripte
			exit();
		}
	} else if ($action == "edit") {

		// provjera da li je vrsta zanimanja poslana u url
		if(!isset($_GET[base64_url_encode("duration")])) {
			header('location: ../500.php');
			exit();
		}
		// dohvatanje vrste zanimanja
		$duration = base64_url_decode($_GET[base64_url_encode("duration")]);
		
		// provjera da li su podaci ispravno popunjeni 
		if($_POST['title'] == "" || $_POST['postContent'] == "") {
			header('location: ../500.php');
			exit();
		}
		// provjera da li je ID poslan u url
		if(!isset($_GET[base64_url_encode("zanimanjeID")])) {
			header('location: ../500.php');
			exit();
		}
		// ID zanimanja 
		$ID = base64_url_decode($_GET[base64_url_encode("zanimanjeID")]);
		// naslov zanimanja
		$title = strip_tags($_POST['title']);
		// sadrzaj zanimanja
		$content = $_POST['postContent'];
		// datum 
		$date = date('o.m.d G:i:s');
		// autor
		$author = $_SESSION['AUTHOR_USERID'];

		// konekcija na bazu 
		$connect = connectToDb();


		//dohvatanje varijabli iz forme
          $postImage = $_FILES['postImage']['name']; // ime slike
		  $postImageTmp = $_FILES['postImage']['tmp_name']; // privremeno ime slike
          $fileType = $_FILES["postImage"]["type"]; // ekstenzija slike
          $fileSize = $_FILES["postImage"]["size"]; // velicina slike u bajtovima
          $fileErrorMsg = $_FILES["postImage"]["error"]; // 0 ako ne postoji greska 1 ako postoji
          $kaboom = explode(".", $postImage); // rastavljanje imena slike i ekstenzije
          $fileExt = end($kaboom); // dohvatanje zadnjeg polja niza
          
          if(!is_uploaded_file($postImageTmp)) {
          	    // dohvatanje slike iz baze
	          	$fetchImage = $connect->prepare("SELECT * FROM zanimanja WHERE zanimanje_id = :id LIMIT 1");
	            $fetchImage->bindParam(":id", $ID);
	            $fetchImage->execute();
	            if($fetchImage->rowCount() > 0){
	              foreach($fetchImage as $aa){
	                $postImage = $aa['zanimanje_image'];
	              }
	            }
          } else {
          	 if($fileSize > 8388608) { // ako je velicina slike veca od 8 MB
	              header('location: ../500.php');
	              unlink($postImageTmp); // uklanjanje slike sa servera
	              exit();
	          } else if (!preg_match("/.(gif|jpg|png|jpeg)$/i", $postImage) ) {
	               // provjera ekstenzije slike
	               header('location: ../500.php');
	               unlink($postImageTmp); // uklanjanje slike sa servera
	               exit();
	          } else if ($fileErrorMsg == 1) { // ako postoji greska u uploadu
	              header('location: ../500.php');
	              exit();
	          }
          	  //prebacivanje slike u folder
	          $moveResult = move_uploaded_file($postImageTmp, "../../images/zanimanja/resized_$postImage");
	          // provjera da li je slika uploadovana na server
	          if ($moveResult != true) {
	              echo "ERROR: Fajl nije poslan na server.";
	              unlink($postImageTmp); // uklanjanje slike iz tmp foldera
	              exit();
	          }
	          

	          include_once("ak_php_img_lib_1.0.php");
	          $target_file = "../../images/zanimanja/resized_$postImage";
	          $resized_file = "../../images/zanimanja/$postImage";
	          $wmax = 750;
	          $hmax = 390;
	          ak_img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
	          //brisanje prave slike
	          unlink($target_file);
	      }
	      // izmjena zanimanja
	      $updateData = $connect->prepare("UPDATE zanimanja SET zanimanje_title = :title,
	      														zanimanje_image = :image,
	      														zanimanje_content = :content,
	      														zanimanje_date_edit = :edit_date WHERE zanimanje_id = :ID");
	      $updateData->bindParam(":title", $title);
	      $updateData->bindParam(":image", $postImage);
	      $updateData->bindParam(":content", $content);
	      $updateData->bindParam(":edit_date", $date);
	      $updateData->bindParam(":ID", $ID);
	      $result = $updateData->execute();
	      //provjera da li je zanimanje izmjenjeno
		if($result) { // zanimanje je izmijenjeno
			// url za preusmjeravanje na zanimanja
			$url = "../zanimanja.php?".base64_url_encode("trajanjeSkolovanja")."=".base64_url_encode($duration);
			// zatvaranje konekcije sa bazom
			$connect = NULL;
			// presumjeravanje na zanimanja
			header("location: $url");
			// zatvaranje skripte
			exit();
			// nije ispravno obrisano zanimanje 
		} else { // zanimanje nije izmijenjeno
			// zatvaranje konekcije sa bazom
			$connect = NULL;
			// preusmjeravanje na straqnicu za javljanje greske
			header('location: ../500.php');
			//zatvaranje skripte
			exit();
		}

	}
} else {
	header('location: ../500.php');
	exit();
}

?>
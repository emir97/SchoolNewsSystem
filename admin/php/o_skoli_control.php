<?php 
include('../core/connect.php'); 
include('../functions/secure.php');
session_start();
ob_start();
checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
if(!checkIsAdmin($_SESSION['AUTHOR_USERID'])){
    header('location: ../index.php');
    exit();
}
# provjera da li je postioji u url varijabala "whatUpload"
if(isset($_GET[base64_url_encode("whatUpload")])){
	# dohvatanje varijable "whatUpload"
	$whatUpload = base64_url_decode($_GET[base64_url_encode("whatUpload")]);
	# konekcija na bazu
	$connect = connectToDb();
	# datum
	# provjera da li su ispravno uneseni podaci
	if($_POST['title'] == "" || $_POST['content'] == ""){
		$connect = NULL;
		header('location: ../500.php');
		exit();
	}
	# dohvatanje unesenih podataka naziva i sadrzaja
	$content = $_POST['content'];
	$title =htmlspecialchars(strip_tags($_POST['title']));

	# datume
	$date = date('o-m-d'). " " . date('H:i:s');

	# autor
	$authorID = $_SESSION['AUTHOR_USERID'];
	
	# query za unos podataka u bazu
	$insertData = $connect->prepare("INSERT INTO podaci_o_skoli(o_skoli_title, o_skoli_content, o_skoli_date_upload, author_id, o_skoli_link_title)
												VALUES (:title, :content, :date_upload, :author, :link)");
	
	# query za update podataka
	$updateData = $connect->prepare("UPDATE podaci_o_skoli SET  o_skoli_title = :title ,
													 			o_skoli_content = :content, 
													  			o_skoli_edit_time = :edit_time WHERE o_skoli_link_title = :link_title");

	# query za dohvatanje podataka
	$fetchData = $connect->prepare("SELECT * FROM podaci_o_skoli WHERE o_skoli_link_title = :link_title LIMIT 1");

	//  provjera da li je varijabla "whatUpload" jednaka oNama
	if($whatUpload == "oNama"){
		// odredjivanje koji je link
		$linkTitle = "oNama";
	// porvjera da li je varijabla "wahtUpload" jednaka rijeciDirektora
	} else if($whatUpload == "rijeciDirektora") {
		// odredjivanje koji je link
		$linkTitle = "rijeciDirektora";
	// porvjera da li je varijabla "wahtUpload" jednaka rijeciDirektora
	} else if($whatUpload == "pravilaSkole") {
		// odredjivanje koji je link
		$linkTitle = "pravilaSkole";
	} 
	// provjera da li postoji podatak vec u bazi
	$fetchData->bindParam(":link_title", $linkTitle);
	$fetchData->execute();
	
	if($fetchData->rowCount() == 1){
		// podatak postoji osvjezi taj podatak
		$updateData->bindParam(":title", $title);
		$updateData->bindParam(":content", $content);
		$updateData->bindParam(":edit_time", $date);
		$updateData->bindParam(":link_title", $linkTitle);
		$result = $updateData->execute();
		//provjera da li je podatak osvjezen
		if($result){
			//podataka je unesen
			$url = "../o_skoli.php?".base64_url_encode("stranica")."=".base64_url_encode($linkTitle);
			$connect = NULL;
			header("location: $url");
			exit();
		} else {
			// podatak nije unesen
			$connect = NULL;
			header("location: ../500.php");
			exit();
		}
	} else {
		// podatak ne postoji unosenje podatka u bazu
		$insertData->bindParam(":title", $title);
		$insertData->bindParam(":content", $content);
		$insertData->bindParam(":date_upload", $date);
		$insertData->bindParam(":author", $authorID);
		$insertData->bindParam(":link", $linkTitle);
		$result = $insertData->execute();
		//provjera da li je podatak unesen
		if($result){
			//podataka je unesen
			$url = "../o_skoli.php?".base64_url_encode("stranica")."=".base64_url_encode($linkTitle);
			$connect = NULL;
			header("location: $url");
			exit();
		} else {
			// podatak nije unesen
			$connect = NULL;
			header("location: ../500.php");
			exit();
		}
	}
}
?>
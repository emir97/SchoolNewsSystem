<?php 
include('../core/connect.php'); 
include('../functions/secure.php');
include('../functions/text_editing.php');
session_start();
ob_start();
//provjera da li korisnik ulogovan
checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
//provjera da li je osoba koja pristupa skripti admin
if(!checkIsAdmin($_SESSION['AUTHOR_USERID'])){
    header('location: index.php');
    exit();
}
# dohvatanje iz URL koju akciju treba izvrsiti
if(isset($_GET[base64_url_encode("action")])) {
	#provjera da li je to akcija insert
	if(base64_url_decode($_GET[base64_url_encode("action")]) == "insert") {
		//provjera ispravnosti upisanog pitanja i naziva ankete
		if(isset($_POST)) {
			if($_POST['question'] == "" || $_POST['title'] == "") {
				 header('location: ../500.php');
			     exit();
			}

			$pollAnswers = array();
			//provjera ispravnosti upisani odgovora
			for ($i=0; $i < 10; $i++) { 
				if(isset($_POST["answer_$i"])){
					if($_POST["answer_$i"] == ""){
						header('location: ../500.php');
			     		exit();
					} else {
						//ako je ispravno dodavanje podataka u niz
						array_push($pollAnswers, strip_tags($_POST["answer_$i"]));
					}
				}
			}
			# naslov ankete
			$pollTitle = strip_tags($_POST['title']);
			# sadrzaj pitanja ankete
			$pollQuestion = strip_tags($_POST['question']);

			# unos podataka u bazu
			# konekcija na bazu
			$connect = connectToDb();
			# unos pitanja u bazu
			$insertPoll = $connect->prepare("INSERT INTO ankete_pitanja (anketa_pitanje_content, anketa_pitanje_naslov) VALUES (:pitanje, :naslov)");
			$insertPoll->bindParam(":pitanje", $pollQuestion);
			$insertPoll->bindParam(":naslov", $pollTitle);
			$result = $insertPoll->execute();
			$pollID = $connect->lastInsertId();
			if($result) {
				$counter = count($pollAnswers);
				# unos ponudjenih odgovora
				$insertAnswers = $connect->prepare("INSERT INTO ankete_odgovori (anketa_pitanje_id, anketa_odg_content) VALUES (:id_ankete, :sadrzaj_pitanja)");
				for ($i=0; $i < $counter; $i++) { 
					$insertAnswers->bindParam(":id_ankete", $pollID);
					$insertAnswers->bindParam(":sadrzaj_pitanja", $pollAnswers[$i]);
					$resultAnswer = $insertAnswers->execute();
				}
			}
			if($resultAnswer){
				$connect = NULL;
				header('location: ../anketa.php');
				exit();
			} else {
				$connect = NULL;
				header('location: ../500.php');
				exit();
			}
			$connect = NULL;
		}
	} # provjera da li je to akcija delete
	else if(base64_url_decode($_GET[base64_url_encode("action")]) == "delete") {
		if (isset($_GET[base64_url_encode("ID")])) {
			# konekcija na bazu
			$connect = connectToDb();
			//brisanje ankete
			$IDPoll = base64_url_decode($_GET[base64_url_encode("ID")]);
			$titlePoll = base64_url_decode($_GET[base64_url_encode("Title")]);

			$deleteQuestion = $connect->prepare("DELETE FROM ankete_pitanja WHERE anketa_pitanje_id = :id");
			$deleteQuestion->bindParam(":id", $IDPoll);

			$deleteAnswer = $connect->prepare("DELETE FROM ankete_odgovori WHERE anketa_pitanje_id = :ID");
			$deleteAnswer->bindParam(":ID", $IDPoll);

			$result = $deleteQuestion->execute();
			if($result) {
				$deleteAnswer->execute();
				$connect = NULL;
				header('location: ../anketa.php');
				exit();
			} else {
				$connect = NULL;
				header('location: ../500.php');
				exit();
			}
			$connect = NULL;
		} else {
			header('location: ../500.php');
			exit();
		}
	} else if(base64_url_decode($_GET[base64_url_encode("action")]) == "activePoll"){
		#konekcija na bazu
		$connect = connectToDb();
		# provjera ako postoji vec aktivirana anketa pa ako postoji istu deaktivirati
		$checkIfExistsActivePoll = $connect->query("SELECT * FROM ankete_pitanja WHERE is_active = '1'");
		if($checkIfExistsActivePoll->rowCount() > 0) {
			$updatePoll = $connect->query("UPDATE ankete_pitanja SET is_active = '0' WHERE is_active = '1'");
		}
		# dohavatanje varijabli iz URL
		$ID = base64_url_decode($_GET[base64_url_encode("ID")]);
		# provjera da li je anketa vec aktivirana 
		# ako jest deaktivirat je
		$checkIsPollActive = $connect->query("SELECT is_active FROM ankete_pitanja WHERE anketa_pitanje_id = '$ID' LIMIT 1");
		foreach ($checkIsPollActive as $key => $value) {
			if($value['is_active'] == "1") {
				$active = 0;
				$deactivePoll = $connect->prepare("UPDATE ankete_pitanja SET is_active = :active WHERE anketa_pitanje_id = :id");
				$deactivePoll->bindParam(":active", $active);
				$deactivePoll->bindParam(":id", $ID);
				$result = $deactivePoll->execute();
				if($result){
					$connect = NULL;
					header('location: ../anketa.php');
					exit();
				} else {
					$connect = NULL;
					header('location: ../500.php');
					exit();
				}
			} else if($value['is_active'] == "0") {
				$active = 1;
				$activePoll = $connect->prepare("UPDATE ankete_pitanja SET is_active = :active WHERE anketa_pitanje_id = :id");
				$activePoll->bindParam(":active", $active);
				$activePoll->bindParam(":id", $ID);
				$result = $activePoll->execute();
				if($result){
					$connect = NULL;
					header('location: ../anketa.php');
					exit();
				} else {
					$connect = NULL;
					header('location: ../500.php');
					exit();
				}
			} else {
				$connect = NULL;
				header('location: ../500.php');
				exit();
			}
		}
	} else {
		header('location: ../500.php');
		exit();
	}

}
?>
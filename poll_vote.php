<?php
	include 'core/connection.php';

	$vote = $_GET['vote'];
	$vote = preg_replace("/[^0-9]/", "", $vote);

	$userIP = $_SERVER['REMOTE_ADDR'];

	$connect = connectOnDb();

	$checkForPollID = $connect->prepare("SELECT anketa_pitanje_id, anketa_odg_num_votes FROM ankete_odgovori WHERE anketa_odg_id = :id LIMIT 1");
	$checkForPollID->bindParam(":id", $vote);
	$checkForPollID->execute();
	if($checkForPollID->rowCount() != 1){
		echo "Glasanje nije uspjelo.";
		exit();
	}
	foreach ($checkForPollID as $key => $value) {
		$pollID = $value['anketa_pitanje_id'];
		$numOfVotes = $value['anketa_odg_num_votes'];
	}
	$checkIsVoted = $connect->prepare("SELECT * FROM ankete_pitanja WHERE anketa_pitanje_id = :id LIMIT 1");
	$checkIsVoted->bindParam(":id", $pollID);
	$checkIsVoted->execute();
	if($checkIsVoted->rowCount() != 1) {
		echo "Glasanje nije uspjelo.";
		exit();
	}
	foreach ($checkIsVoted as $key => $value) {
		$newipaddress = $value['anketa_ip_address'];
		$ipaddress = $value['anketa_ip_address'];
		$ipaddress = explode(";", $ipaddress);
		if(in_array($userIP, $ipaddress, TRUE)) {
			echo "Već ste glasali";
			exit();
		}
	}
	$newipaddress = $newipaddress.$userIP.";";
	$insertVote = $connect->prepare("UPDATE ankete_pitanja SET anketa_ip_address = :ip WHERE anketa_pitanje_id = :id");
	$insertVote->bindParam(":ip", $newipaddress);
	$insertVote->bindParam(":id", $pollID);
	$result = $insertVote->execute();
	if($result) {
		$numOfVotes = $numOfVotes + 1;
		$insertNumOfVote = $connect->prepare("UPDATE ankete_odgovori SET anketa_odg_num_votes = :numOfVotes WHERE anketa_odg_id = :id");
		$insertNumOfVote->bindParam(":numOfVotes", $numOfVotes);
		$insertNumOfVote->bindParam(":id", $vote);
		$result1 = $insertNumOfVote->execute();
		if($result1) {
			$connect = NULL;
			header('location: index.php');
			exit();
		}
	}


?>
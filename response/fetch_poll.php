<?php 
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		include '../core/connection.php';

		$connection = connectOnDb();
		$question = "";
		$output = "";
		$fetchPoll = $connection->query("SELECT * FROM ankete_pitanja WHERE is_active = '1' LIMIT 1");
		if($fetchPoll->rowCount() == 1) {
			foreach ($fetchPoll as $key => $value) {
				$question = $value['anketa_pitanje_content'];
				// uklanjanje nepozeljnih karaktera iz stringa
				$question = preg_replace("/[^A-Za-z0-9\- ščćžđ]/", "", $question);
				$ID = $value['anketa_pitanje_id'];		
			}
			$fetchAnswers = $connection->query("SELECT * FROM ankete_odgovori WHERE anketa_pitanje_id = '$ID'");
			foreach ($fetchAnswers as $key => $value) {
				if($output != "") { $output = $output . ", "; }
				$output .= '{"answer":"'.preg_replace("/\r|\n/", "", $value['anketa_odg_content']).'", ';
				$output .= '"ID":"'.preg_replace("/\r|\n/", "", $value['anketa_odg_id']).'"}';
			}
			$output = '{"Question":"'.$question.'", "ID":"'.$ID.'", "Answers":['.$output.']}';
		}
		echo $output;

?>
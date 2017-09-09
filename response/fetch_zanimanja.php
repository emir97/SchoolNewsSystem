<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include '../core/connection.php';

	$output = "";
	if(isset($_GET['trajanje'])) {
		if($_GET['trajanje'] == "3 Godine"){
			$connect = connectOnDb();
			$fetchZanimanja = $connect->query("SELECT * FROM zanimanja WHERE zanimanje_duration = '3'");
			if($fetchZanimanja->rowCount() > 0){
				while ($r = $fetchZanimanja->fetch(PDO::FETCH_OBJ)) {
					//id
					$ID = $r->zanimanje_id;
					// naslov
					$title = $r->zanimanje_title;
					// uklanjanje nepozeljnih karaktera iz stringa
					$title = preg_replace("/[^A-Za-z0-9\- ščćžđ]/", "", $title);
					// slika 
					$image = $r->zanimanje_image;
					// url 
					$url = "zanimanja_detalji.php?ID=".$ID."&title=".$title;

					

					if($output != "") { $output = $output . ", "; }
					$output .= '{"Title":"'.preg_replace("/\r|\n/", "", $title).'", ';
					$output .= '"Image":"'.$image.'", ';
					$output .= '"URL":"'.$url.'"}';
				}
				$output = '{"trajanje":"3 Godine", "zanimanja":['.$output.']}';
			}
				$connect = NULL;
		} else if($_GET['trajanje'] == "4 Godine") {
			$connect = connectOnDb();
			$fetchZanimanja = $connect->query("SELECT * FROM zanimanja WHERE zanimanje_duration = '4'");
			if($fetchZanimanja->rowCount() > 0){
				while ($r = $fetchZanimanja->fetch(PDO::FETCH_OBJ)) {
					//id
					$ID = $r->zanimanje_id;
					// naslov
					$title = $r->zanimanje_title;

					// slika 
					$image = $r->zanimanje_image;
					// url 
					$url = "zanimanja_detalji.php?ID=".$ID."&title=".$title;

					// uklanjanje nepozeljnih karaktera iz stringa
					$title = preg_replace("/[^A-Za-z0-9\- ščćžđ]/", "", $title);

					if($output != "") { $output = $output . ", "; }
					$output .= '{"Title":"'.preg_replace("/\r|\n/", "", $title).'", ';
					$output .= '"Image":"'.$image.'", ';
					$output .= '"URL":"'.$url.'"}';
				}
				$output = '{"trajanje":"4 Godine", "zanimanja":['.$output.']}';
			}
				$connect = NULL;
		} 
	}
	echo $output;


?>
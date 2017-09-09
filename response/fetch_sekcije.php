<?php 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include '../core/connection.php';

	$output = "";
	
		$connect = connectOnDb();
		$fetchSeckije = $connect->query("SELECT * FROM sekcije");
		if($fetchSeckije->rowCount() > 0){
			while ($r = $fetchSeckije->fetch(PDO::FETCH_OBJ)) {
				//id
				$ID = $r->sekcija_id;
				// naslov
				$title = $r->sekcija_title;
				// slika 
				$image = $r->sekcija_image;
				// url 
				$url = "sekcija_detalji.php?ID=".$ID."&title=".$title;

				if($output != "") { $output = $output . ", "; }
				$output .= '{"Title":"'.preg_replace("/\r|\n/", "", $title).'", ';
				$output .= '"Image":"'.preg_replace("/\r|\n/", "", $image).'", ';
				$output .= '"URL":"'.$url.'"}';
			}
			$output = '{"sekcije":['.$output.']}';
		}
			$connect = NULL;
		
	echo $output;


?>
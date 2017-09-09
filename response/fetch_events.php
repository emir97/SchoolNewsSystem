<?php
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		include '../core/connection.php';
		
		$outputEvent = "";
		
		//povezivanje na bazu
		$connectToDb = connectOnDb();
		//dohvatanje dogadjaja
		$fetchEvents = $connectToDb->query("SELECT * FROM events ORDER BY event_date_post DESC LIMIT 6");
		if($fetchEvents->rowCount() > 0){
			while($row = $fetchEvents->fetch(PDO::FETCH_OBJ)){
				
				//ID dogadjaja
				$eventID = $row->event_id;
				
				//naslov dogadjaja
				$eventTitle = $row->event_title;
				// uklanjanje nepozeljnih karaktera iz stringa
				$eventTitle = preg_replace("/[^A-Za-z0-9\- ščćžđ]/", "", $eventTitle);
				$eventTitle = preg_replace("/\r|\n/", "", $eventTitle);
				
				
				//sadrzaj dogadjaja
				$eventContent = strip_tags( $row->event_content);
				// uklanjanje nepozeljnih karaktera iz stringa
				$eventContent = preg_replace("/&#?[A-Za-z0-9 ščćžđ]+;/i", "", $eventContent);
				$eventContent = preg_replace("/\r|\n/", "", $eventContent);

				
				
				//mjesto odrzavanja dogadjaja
				$eventPlace = $row->event_place;
				
				//datum pocetka odrzavanja
				$eventStartDate = new DateTime($row->event_start_date);
				$eventStartYear = $eventStartDate->format('o');
				$eventStartDay = $eventStartDate->format('d');
				$eventStartMonth = $eventStartDate->format('m');
				switch($eventStartMonth){
					case "01":	$eventStartMonth = "Januar"; break;
					case "02":	$eventStartMonth = "Februar"; break;
					case "03":	$eventStartMonth = "Mart"; break;
					case "04":	$eventStartMonth = "April"; break;
					case "05":	$eventStartMonth = "Maj"; break;
					case "06":	$eventStartMonth = "Juni"; break;
					case "07":	$eventStartMonth = "Juli"; break;
					case "08":	$eventStartMonth = "August"; break;
					case "09":	$eventStartMonth = "Septembar"; break;
					case "10":	$eventStartMonth = "Oktobar"; break;
					case "11":	$eventStartMonth = "Novembar"; break;
					case "12": 	$eventStartMonth = "Decembar"; break;
				}
				$eventStart = $eventStartMonth. " ".$eventStartDay .", ".$eventStartYear.". ";
				
				//slika dogadjaja
				$eventImage = $row->event_image;
				
				$eventUrl = "dogadjaji_detalji.php?ID=" . $eventID . "&" . "title=" . $eventTitle;

				
				
				if($outputEvent != "") { $outputEvent = $outputEvent . ", "; }
				$outputEvent .= '{"Title":"'.$eventTitle.'", ';
				$outputEvent .= '"Content":"'.$eventContent.'", ';
				$outputEvent .= '"Image":"'.$eventImage.'", ';
				$outputEvent .= '"startDate":"'.$eventStart.'", ';
				$outputEvent .= '"Place":"'.$eventPlace.'", ';
				$outputEvent .= '"URL":"'.$eventUrl.'"}';
				
				
				
			}
		}
		$connectToDb = NULL;
		$outputEvent = '{"events":['.$outputEvent.']}';
		echo $outputEvent;

?>
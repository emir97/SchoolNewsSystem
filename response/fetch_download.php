<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include '../core/connection.php';

	$outputDownload = "";
	//povezivanje na bazu
	$connectToDb = connectOnDb();
	//dohvatanje dogadjaja
	$fetchDownload = $connectToDb->query("SELECT * FROM downloads");
	//provjera ima li ista u bazi 
	if($fetchDownload->rowCount() > 0) {
		while ($r = $fetchDownload->fetch(PDO::FETCH_OBJ)) {
			// naslov fajla
			$downloadTitle = $r->download_title;
			// uklanjanje nepozeljnih karaktera iz stringa
			$downloadTitle = preg_replace("/[^A-Za-z0-9\- ščćžđ]/", "", $downloadTitle);
			
			// URL fajla 
			$downloadURL = $r->download_content;

			if($outputDownload != "") {$outputDownload = $outputDownload .",";}
			$outputDownload .= '{"title": "'.$downloadTitle.'",';
			$outputDownload .= '"url":"'.$downloadURL.'"}';
		}
		$outputDownload = '{"downloads":['.$outputDownload."]}";
	}
	$connectToDb = NULL;
	echo $outputDownload;

?>
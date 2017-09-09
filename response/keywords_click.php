<?php 
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include '../core/connection.php';

	$outputNews = "";
	if(isset($_GET['key'])) {
		$key = htmlspecialchars(strip_tags($_GET['key']));
		$key = trim($key);

		$connect = connectOnDb();
		$fetchIDKeys = $connect->prepare("SELECT keywords_id FROM keywords WHERE keywords_content = :content LIMIT 1");
		$fetchIDKeys->bindParam(":content", $key);
		$fetchIDKeys->execute();
		if($fetchIDKeys->rowCount() == 1) {
			foreach ($fetchIDKeys as $key => $value) {
				$keyID = $value['keywords_id'];
			}

			$fetchNewsFromKeys = $connect->prepare("SELECT * FROM news WHERE keywords_id = :id");
			$fetchNewsFromKeys->bindParam(":id", $keyID);
			$fetchNewsFromKeys->execute();
			
			if($fetchNewsFromKeys->rowCount() > 0) {
				foreach ($fetchNewsFromKeys as $key => $value) {
					$newsID = $value['news_id'];
					$newsTitleForUrl = $value['news_title'];
					//naslov vijesti
					$newsTitle = substr($value['news_title'], 0, 72) . " ...";
					// uklanjanje nepozeljnih karaktera iz stringa
					$newsTitle = preg_replace("/[^A-Za-z0-9\- ščćžđ]/", "", $newsTitle);
					//sadrzaj vijesti
					$newsContent = substr(strip_tags($value['news_content']), 0, 180);
					// uklanjanje nepozeljnih karaktera iz stringa
					$newsContent = preg_replace("/[^A-Za-z0-9\- ščćžđ]/", "", $newsContent);
					//datum vijesti
					$newsDate = new DateTime($value['news_date']);
					$newsDate = $newsDate->format('d. m. o.');
					
					//slika vijesti
					$newsImage = $value['news_image'];
					
					//autor ID 
					$author_id = $value['author_id'];
					
					//url za citanje pojedine vijesti
					$urlForNews = "vijest_detalji.php?ID=" . $newsID . "&" . "title=" . $newsTitleForUrl;

					

					$newsAuthorQuery = $connect->query("SELECT author_name, author_surname FROM authors WHERE author_id = $author_id LIMIT 1");
					foreach($newsAuthorQuery as $row){
						$newsAuthor = $row['author_name'] . " ".$row['author_surname'];
					}
						if($outputNews != "") { $outputNews = $outputNews . ", "; }
						$outputNews .= '{"Title":"'.$newsTitle.'", ';
						$outputNews .= '"Content":"'.preg_replace("/\r|\n/", "", $newsContent).'", ';
						$outputNews .= '"Image":"'.$newsImage.'", ';
						$outputNews .= '"Date":"'.$newsDate.'", ';
						$outputNews .= '"Author":"'.$newsAuthor.'", ';
						$outputNews .= '"URL":"'.$urlForNews.'"}';
				}
						$outputNews = '{"page":"Oznake","news":['.$outputNews.']}';
			}
		}
		$connect = NULL;
		if($outputNews == "") {
			$outputNews = '{"page":"Oznake","news":""}';
		}
		echo $outputNews;
	}
	
	if(isset($_GET['search_query'])) {
		$search = $_GET['search_query'];
		$search = preg_replace("#[^0-9a-zA-Z ]#i", "", $search);

		$connect = connectOnDb();
		$checkForSearch = $connect->prepare("SELECT * FROM news WHERE news_title LIKE ? OR news_content LIKE ?");
		$params = array("%$search%", "%$search%");
		$checkForSearch->execute($params);
		if($checkForSearch->rowCount() > 0) {
			foreach($checkForSearch as $row){
			
				$newsID = $row['news_id'];
				$newsTitleForUrl = $row['news_title'];
				//naslov vijesti
				$newsTitle = substr($row['news_title'], 0, 72) . " ...";
				// uklanjanje nepozeljnih karaktera iz stringa
				$newsTitle = preg_replace("/[^A-Za-z0-9\- ščćžđ]/", "", $newsTitle);
				//sadrzaj vijesti
				$newsContent = substr(htmlspecialchars(strip_tags($row['news_content'])), 0, 180);
				// uklanjanje nepozeljnih karaktera iz stringa
				$newsContent = preg_replace("/[^A-Za-z0-9\- ščćžđ]/", "", $newsContent);
				
				//datum vijesti
				$newsDate = new DateTime($row['news_date']);
				$newsDate = $newsDate->format('d. m. o.');
				
				//slika vijesti
				$newsImage = $row['news_image'];
				
				//autor ID 
				$author_id = $row['author_id'];
				
				//url za citanje pojedine vijesti
				$urlForNews = "vijest_detalji.php?ID=" . $newsID . "&" . "title=" . $newsTitleForUrl;

				$newsAuthorQuery = $connect->query("SELECT author_name, author_surname FROM authors WHERE author_id = $author_id LIMIT 1");
				foreach($newsAuthorQuery as $row){
					$newsAuthor = $row['author_name'] . " ".$row['author_surname'];
				}
				if($outputNews != "") { $outputNews = $outputNews . ", "; }
				$outputNews .= '{"Title":"'.$newsTitle.'", ';
				$outputNews .= '"Content":"'.preg_replace("/\r|\n/", "", $newsContent).'", ';
				$outputNews .= '"Image":"'.$newsImage.'", ';
				$outputNews .= '"Date":"'.$newsDate.'", ';
				$outputNews .= '"Author":"'.$newsAuthor.'", ';
				$outputNews .= '"URL":"'.$urlForNews.'"}';
  			}
  			$outputNews = '{"page":"Pretraga", "news":['.$outputNews.']}';
		}
		$connect = NULL;
		if($outputNews == "") {
			$outputNews = '{"page":"Pretraga", "news":""}';
		}
		echo $outputNews;
	}
	
	
?>
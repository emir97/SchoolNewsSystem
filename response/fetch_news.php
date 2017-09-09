<?php  	
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		include '../core/connection.php';
						
		$outputNews = "";
						
		$connectToDb = connectOnDb();
		$newsResult = $connectToDb->query("SELECT * FROM news ORDER BY news_date DESC LIMIT 0, 6");
		$fetchNumberOfNews = $connectToDb->query("SELECT * FROM news");
		$numberOfNews = $fetchNumberOfNews->rowCount();
		foreach($newsResult as $row){
			
			$newsID = $row['news_id'];
			$newsTitleForUrl = $row['news_title'];
			//naslov vijesti
			$newsTitle = substr($row['news_title'], 0, 72) . " ...";
			// uklanjanje nepozeljnih karaktera iz stringa
			$newsTitle = preg_replace("/[^A-Za-z0-9\- ščćžđ]/", "", $newsTitle);
			
			//sadrzaj vijesti
			$newsContent = substr(strip_tags($row['news_content']), 0, 180);
			// uklanjanje nepozeljnih karaktera iz stringa
			$newsContent = preg_replace("/&#?[A-Za-z0-9 ščćžđ]+;/i", "", $newsContent);
			
			//datum vijesti
			$newsDate = new DateTime($row['news_date']);
			$newsDate = $newsDate->format('d. m. o.');
			
			//slika vijesti
			$newsImage = $row['news_image'];
			
			//autor ID 
			$author_id = $row['author_id'];
			
			//url za citanje pojedine vijesti
			$urlForNews = "vijest_detalji.php?ID=" . $newsID . "&" . "title=" . $newsTitleForUrl;


			$newsAuthorQuery = $connectToDb->query("SELECT author_name, author_surname FROM authors WHERE author_id = $author_id LIMIT 1");
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
  $connectToDb = NULL;
  $outputNews = '{"news":['.$outputNews.']}';
  echo $outputNews;
                    ?>
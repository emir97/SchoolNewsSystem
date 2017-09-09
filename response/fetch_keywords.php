<?php  	
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		include '../core/connection.php';
						
		$outputKeys1 = "";
		$outputKeys2 = "";
		$keyword = array();
		$keyUrl = array();
		$connectToDb = connectOnDb();
		$keyFetch = $connectToDb->query("SELECT * FROM keywords ORDER BY RAND() LIMIT 8");
		if($keyFetch->rowCount() > 0) {
			foreach($keyFetch as $row){
				$keyID = $row['keywords_id'];
				array_push($keyword, preg_replace("/\r|\n/", "", $row['keywords_content']));
				array_push($keyUrl, "search_page.php?key=".preg_replace("/\r|\n/", "", $row['keywords_content']));
  			}
  			$counter = count($keyword);
  			for ($i=0; $i < $counter/2; $i++) { 
  				if($outputKeys1 != "") { $outputKeys1 = $outputKeys1 . ", "; }
				$outputKeys1 .= '{"key":"'.$keyword[$i].'", ';
				$outputKeys1 .= '"URL":"'.$keyUrl[$i].'"}';
  			}
  			$outputKeys1 = '"keywords1":['.$outputKeys1.']';
  			if($counter > 4) {
  				for ($i=4; $i < $counter; $i++) { 
	  				if($outputKeys2 != "") { $outputKeys2 = $outputKeys2 . ", "; }
					$outputKeys2 .= '{"key":"'.$keyword[$i].'", ';
					$outputKeys2 .= '"URL":"'.$keyUrl[$i].'"}';
  				}
  				$outputKeys2 = ', "keywords2":['.$outputKeys2.']';
  			}
		}
		$connectToDb = NULL;
		
  
  echo "{".$outputKeys1.$outputKeys2."}";
                    ?>

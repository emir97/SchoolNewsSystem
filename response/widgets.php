<?php 
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include '../core/connection.php';
	
	$connectToDb = connectOnDb();

	$outputIzdvojeno = "";		
    $outputEvents = "";
    $outputMostRead = "";
    $outputPopularNews = "";
    $outputGallery = "";

    $IzdvojenoNews = $connectToDb->query("SELECT * FROM news WHERE izdvojeno = '1' LIMIT 5");
    foreach($IzdvojenoNews as $row){
        
        $urlForViewNewsIzdovjeno = "vijest_detalji.php?ID=".$row['news_id']."&title=".$row['news_title'];
        
            //slika vijesti
        $newsImage = $row['news_image'];
        //naslov vijesti
        $newsTitle = $row['news_title'];
        // uklanjanje nepozeljnih karaktera iz stringa
        $newsTitle = preg_replace("/[^A-Za-z0-9\- ščćžđ]/", "", $newsTitle);
        //datum objave vijesti
        $newsDate = new DateTime($row['news_date']);
        $newsDate = $newsDate->format('d. m. o.');
        
        //autor
        $authorID = $row['author_id'];
        $fetchAuthor = $connectToDb->prepare("SELECT * FROM authors WHERE author_id = :ID LIMIT 1");
        $fetchAuthor->bindParam(":ID", $authorID);
        $fetchAuthor->execute();
        foreach($fetchAuthor as $rowAuthor){
            $authorName = $rowAuthor['author_name'];
            $authorSurname = $rowAuthor['author_surname'];
            $newsAuthor = $authorName . " " . $authorSurname;
            break;
        }
        
        
        if ($outputIzdvojeno != "") {$outputIzdvojeno .= ",";}
        
        $outputIzdvojeno .= '{"Title":"'  . $newsTitle . '",';
        $outputIzdvojeno .= '"Date":"'  . $newsDate . '",';
        $outputIzdvojeno .= '"Image":"'  . $newsImage . '",';
        $outputIzdvojeno .= '"Author":"'  . $newsAuthor . '",';
        $outputIzdvojeno .= '"URL":"'  . $urlForViewNewsIzdovjeno . '"}';
    }
    $outputIzdvojeno ='{"newsIzdvojeno": ['.$outputIzdvojeno.']';
    echo $outputIzdvojeno;
    
    
    $mostReadNews = $connectToDb->query("SELECT * FROM news ORDER BY news_num_views DESC LIMIT 4");
        foreach($mostReadNews as $row){
            
            $urlMostRead = "vijest_detalji.php?ID=".$row['news_id']."&title=".$row['news_title'];
            //slika vijesti
            $newsImageMostRead = $row['news_image'];
            //naslov vijesti
            $newsTitleMostRead = $row['news_title'];
            // uklanjanje nepozeljnih karaktera iz stringa
            $newsTitleMostRead = preg_replace("/[^A-Za-z0-9\- ščćžđ]/", "", $newsTitleMostRead);
            //datum objave vijesti
            $newsDateMostRead = new DateTime($row['news_date']);
            $newsDateMostRead = $newsDateMostRead->format('d. m. o.');
            
            //autor
            $authorIDMostRead = $row['author_id'];
            $fetchAuthorMostRead = $connectToDb->prepare("SELECT * FROM authors WHERE author_id = :ID LIMIT 1");
            $fetchAuthorMostRead->bindParam(":ID", $authorIDMostRead);
            $fetchAuthorMostRead->execute();
            foreach($fetchAuthorMostRead as $rowAuthor){
                $authorNameMostRead = $rowAuthor['author_name'];
                $authorSurnameMostRead = $rowAuthor['author_surname'];
                $authorMostRead = $authorNameMostRead . " " . $authorSurnameMostRead;
                break;
            }
            
            if ($outputMostRead != "") {$outputMostRead .= ",";}
        
            $outputMostRead .= '{"Title":"'  . $newsTitleMostRead . '",';
            $outputMostRead .= '"Date":"'  . $newsDateMostRead . '",';
            $outputMostRead .= '"Image":"'  . $newsImageMostRead . '",';
            $outputMostRead .= '"Author":"'  . $authorMostRead . '",';
            $outputMostRead .= '"URL":"'  . $urlMostRead . '"}'; 
        }
        
        $outputMostRead =', "newsMostRead": ['.$outputMostRead.']';

        echo $outputMostRead;
        
        $fetchPopular = $connectToDb->query("SELECT * FROM news WHERE num_comment > 0 LIMIT 4");
        if($fetchPopular->rowCount() == 0){
        $fetchPopularPosts = $connectToDb->query("SELECT * FROM news ORDER BY num_comment DESC LIMIT 4");
        if($fetchPopularPosts->rowCount() > 0) {
            foreach($fetchPopularPosts as $row){
                
                $urlPopular = "vijest_detalji.php?ID=".$row['news_id']."&title=".$row['news_title'];
                
                $newsTitlePopular = $row['news_title'];
                // uklanjanje nepozeljnih karaktera iz stringa
                $newsTitlePopular = preg_replace("/[^A-Za-z0-9\- ščćžđ]/", "", $newsTitlePopular);
                
                $newsImagePopular = $row['news_image'];
                
                    //autor
                $authorIDPopular = $row['author_id'];
                $fetchAuthorPopular = $connectToDb->prepare("SELECT * FROM authors WHERE author_id = :ID LIMIT 1");
                $fetchAuthorPopular->bindParam(":ID", $authorIDPopular);
                $fetchAuthorPopular->execute();
                foreach($fetchAuthorPopular as $rowAuthor){
                    $authorNamePopular = $rowAuthor['author_name'];
                    $authorSurnamePopular = $rowAuthor['author_surname'];
                    
                    $authorPopular = $authorNamePopular . " " . $authorSurnamePopular;
                    break;
                }
                
                //datum objave vijesti
                $newsDatePopular = new DateTime($row['news_date']);
                $newsDatePopular = $newsDatePopular->format('d. m. o.');
                
                if ($outputPopularNews != "") {$outputPopularNews .= ",";}
        
                $outputPopularNews .= '{"Title":"'  . $newsTitlePopular . '",';
                $outputPopularNews .= '"Date":"'  . $newsDatePopular . '",';
                $outputPopularNews .= '"Image":"'  . $newsImagePopular . '",';
                $outputPopularNews .= '"Author":"'  . $authorPopular . '",';
                $outputPopularNews .= '"URL":"'  . $urlPopular . '"}';
            
            }
          }
        } else {
            foreach($fetchPopular as $row){
                
                $urlPopular = "vijest_detalji.php?ID=".$row['news_id']."&title=".$row['news_title'];
                
                $newsTitlePopular = $row['news_title'];
                // uklanjanje nepozeljnih karaktera iz stringa
                $newsTitlePopular = preg_replace("/[^A-Za-z0-9\- ščćžđ]/", "", $newsTitlePopular);

                $newsImagePopular = $row['news_image'];
                
                    //autor
                $authorIDPopular = $row['author_id'];
                $fetchAuthorPopular = $connectToDb->prepare("SELECT * FROM authors WHERE author_id = :ID LIMIT 1");
                $fetchAuthorPopular->bindParam(":ID", $authorIDPopular);
                $fetchAuthorPopular->execute();
                foreach($fetchAuthorPopular as $rowAuthor){
                    $authorNamePopular = $rowAuthor['author_name'];
                    $authorSurnamePopular = $rowAuthor['author_surname'];
                    
                    $authorPopular = $authorNamePopular . " " . $authorSurnamePopular;
                    break;
                }
                
                //datum objave vijesti
                $newsDatePopular = new DateTime($row['news_date']);
                $newsDatePopular = $newsDatePopular->format('d. m. o.');
                
                if ($outputPopularNews != "") {$outputPopularNews .= ",";}

                $outputPopularNews .= '{"Title":"'  . $newsTitlePopular . '",';
                $outputPopularNews .= '"Date":"'  . $newsDatePopular . '",';
                $outputPopularNews .= '"Image":"'  . $newsImagePopular . '",';
                $outputPopularNews .= '"Author":"'  . $authorPopular . '",';
                $outputPopularNews .= '"URL":"'  . $urlPopular . '"}';
            }
        }
            $outputPopularNews = ', "newsPopular" : ['.$outputPopularNews.']';

            echo $outputPopularNews;
            

            $fetchEvents = $connectToDb->query("SELECT * FROM events ORDER BY event_start_date LIMIT 3");
            if($fetchEvents->rowCount() > 0){
                while ($r = $fetchEvents->fetch(PDO::FETCH_OBJ)) {

                    # ID dogadjaja
                    $eventID = $r->event_id;
                    # naslov dogadjaja
                    $eventTitle = $r->event_title;
                    // uklanjanje nepozeljnih karaktera iz stringa
                    $eventTitle = preg_replace("/[^A-Za-z0-9\- ščćžđ]/", "", $eventTitle);
                    # vrijeme dogadjaja
                    $eventStart = new DateTime($r->event_start_date);
                    # mjesec pocetka dogadjaja
                    $eventStartMonth = $eventStart->format('m');
                    switch ($eventStartMonth) {
                        case '01': $eventStartMonth = "Jan"; break;
                        case '02': $eventStartMonth = "Feb"; break;
                        case '03': $eventStartMonth = "Mar"; break;
                        case '04': $eventStartMonth = "Apr"; break;
                        case '05': $eventStartMonth = "Maj"; break;
                        case '06': $eventStartMonth = "Jun"; break;
                        case '07': $eventStartMonth = "Jul"; break;
                        case '08': $eventStartMonth = "Aug"; break;
                        case '09': $eventStartMonth = "Sep"; break;
                        case '10': $eventStartMonth = "Okt"; break;
                        case '11': $eventStartMonth = "Nov"; break;
                        case '12': $eventStartMonth = "Dec"; break;
                        default:  break;
                    }
                    # dan pocetka dogadjaja
                    $eventStartDay = $eventStart->format('d');

                    $eventStartDate = $eventStart->format('d. m. o.');

                    $urlForViewEvent = "dogadjaji_detalji.php?ID=".$eventID."&title=".$eventTitle;

                    if ($outputEvents != "") {$outputEvents .= ",";}

                    $outputEvents .= '{"Title":"'  . $eventTitle . '",';
                    $outputEvents .= '"MonthStart":"'  . $eventStartMonth . '",';
                    $outputEvents .= '"DayStart":"'  . $eventStartDay . '",';
                    $outputEvents .= '"DateStart":"'  . $eventStartDate . '",';
                    $outputEvents .= '"URL":"'  . $urlForViewEvent . '"}';

                }

                $outputEvents = ', "events" : ['.$outputEvents.']';
            
                echo $outputEvents;
            } else {
                echo "}";
            }

            $fetchPhotos = $connectToDb->query("SELECT * FROM gallery_images ORDER BY RAND() LIMIT 8");
            if($fetchPhotos->rowCount() > 0) {
                while ($r = $fetchPhotos->fetch(PDO::FETCH_OBJ)) {

                    $albumID = $r->album_id;

                    $fetchAlbum = $connectToDb->query("SELECT * FROM albums WHERE album_id = '$albumID' LIMIT 1");
                    foreach ($fetchAlbum as $key => $value) {
                        $albumTitle = $value['album_title'];
                    }
                    $albumTitle = preg_replace("/&#?[A-Za-z0-9 ščćžđ]+;/i", "", $albumTitle);

                    $photoName = $r->photo_name;

                    if ($outputGallery != "") {$outputGallery .= ",";}

                    $outputGallery .= '{"Photo":"'  . $photoName .'", ';
                    $outputGallery .= '"Title":"'.$albumTitle.'"}';

                }
                $outputGallery = ', "photos" : ['.$outputGallery.']}';
                echo $outputGallery;
            } else {
                echo "}";
            }

            $connectToDb = NULL;
  ?>
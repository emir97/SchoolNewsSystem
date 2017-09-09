<?php 
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include '../core/connection.php';
	
	$connectToDb = connectOnDb();
	$output = "";
    $newsResult = $connectToDb->query("SELECT * FROM news ORDER BY news_date DESC LIMIT 6");
                        foreach($newsResult as $row){

                            //url za pregledavanje vijesti
                            $viewNewsUrl = "vijest_detalji.php?ID=".$row['news_id'] . "&title=".$row['news_title'];
                            
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
                            
                            $newsAuthorQuery = $connectToDb->query("SELECT author_name, author_surname FROM authors WHERE author_id = $author_id");
                            foreach($newsAuthorQuery as $row){
                                $newsAuthor = $row['author_name'] . " ".$row['author_surname'];
                            	if ($output != "") {$output .= ",";}
								
								$output .= '{"Title":"'  . $newsTitle . '",';
								$output .= '"Date":"'  . $newsDate . '",';
								$output .= '"Image":"'  . $newsImage . '",';
								$output .= '"Author":"'  . $newsAuthor . '",';
								$output .= '"URL":"'  . $viewNewsUrl . '",';
    							$output .= '"Content":"'.preg_replace("/\r|\n/", "", $newsContent).'"}';
                        
                        }
                        }
                    
	$output ='{"news": ['.$output.']';
	
	echo $output;
    
    $outputSlider = "";
    $sliderResult = $connectToDb->query("SELECT * FROM slider ORDER BY red_br LIMIT 6");
                                   foreach($sliderResult as $row){

                                        $sliderURL = "slider_detalji.php?ID=".$row['id']."&title=".$row['title'];
                                       //naslov slidera
                                       $sliderTitle = $row['title'];
                                       $sliderTitle = substr($sliderTitle, 0, 45);
                                       // uklanjanje nepozeljnih karaktera iz stringa
                                       $sliderTitle = preg_replace("/[^A-Za-z0-9\- ščćžđ]/", "", $sliderTitle);
                                       //slika slidera
                                       $sliderImage = $row['image'];
                                   
                                        if($row['red_br'] == 1)
                                            $class = "item active";
                                         else{
                                            $class = "item";
                                         }
                                         if($outputSlider != '') { $outputSlider = $outputSlider . ",";}
                                         $outputSlider .= '{"Title":"'  . $sliderTitle . '",';
                                         $outputSlider .= '"Image":"'  . $sliderImage . '",';
                                         $outputSlider .= '"URL":"'  . $sliderURL . '",';
                                         $outputSlider .= '"class":"'.$class.'"}';
                                   }
    $outputSlider = ', "slider": ['.$outputSlider.']}';
    echo $outputSlider;
    
    $connectToDb = NULL;
?>
<?php
	require_once 'core/connection.php';
	$load = htmlentities(strip_tags($_POST['load']));
	
	$connection = connectOnDb();
	$query = $connection->query("SELECT * FROM news ORDER BY news_date DESC LIMIT $load, 2");
	
	while($row = $query->fetch()){
		$newsID = $row['news_id'];
                            $newsTitleForUrl = $row['news_title'];
                            //naslov vijesti
                            $newsTitle = substr($row['news_title'], 0, 72) . " ...";
                            
                            //sadrzaj vijesti
                            $newsContent = substr(htmlspecialchars(strip_tags($row['news_content'])), 0, 180);
                            
                            //datum vijesti
                            $newsDate = new DateTime($row['news_date']);
                            $newsDate = $newsDate->format('d. m. o.');
                            
                            //slika vijesti
                            $newsImage = $row['news_image'];
                            
                            //autor ID 
                            $author_id = $row['author_id'];
                            
                            //url za citanje pojedine vijesti
                            $urlForNews = "vijest_detalji.php?ID=" . $newsID . "&" . "title=" . $newsTitleForUrl;

                            $newsAuthorQuery = $connection->query("SELECT author_name, author_surname FROM authors WHERE author_id = $author_id");
                            foreach($newsAuthorQuery as $row){
                                $newsAuthor = $row['author_name'] . " ".$row['author_surname'];
                            
                    
                        echo '
                            <div class="col-md-6">
                                <div class="blog-grid-item">
                                    <div class="blog-grid-thumb">
                                        <a href="'.$urlForNews.'">
                                            <img src="./images/news/'.$newsImage.'" alt="'.$newsImage.'" />
                                        </a>
                                    </div>
                                    <div class="box-content-inner">
                                       <div class="box-content-heading"> <h4 class="blog-grid-title"><a href="'.$urlForNews.'">'.$newsTitle.'</a></h4></div>
                                       <div class="box-content-content"> <p>'.$newsContent.'... </p> </div>
                                        <p class="blog-grid-meta small-text"><span> '.$newsDate.'</span>&nbsp;&nbsp; | &nbsp;&nbsp;<span class="box-content-author"> '.$newsAuthor.'</span></p>
                                    </div> <!-- /.box-content-inner -->
                               </div> <!-- /.blog-grid-item -->
                            </div><!-- /.col-md-6 -->
                        ';
	}
    }
    $connection = NULL;
?>
<?php 
	    include '../functions/secure.php';
      include '../core/connect.php';
      session_start();
      checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
      // provjera da li su poslane varijable u url
		  foreach($_GET as $value){
        if($value != $_GET[base64_url_encode("postType")] && 
           $value != $_GET[base64_url_encode("newsTitle")] && 
           $value != $_GET[base64_url_encode("newsID")]) {
          session_unset();
          session_destroy();
          header('Location: ../index.php');
          exit();
        }
      }
      
      //podaci o vijesti
		  $whereToUpdate = base64_url_decode($_GET[base64_url_encode("postType")]);
      $postID = base64_url_decode($_GET[base64_url_encode("newsID")]);
      $titleOfNews = base64_url_decode($_GET[base64_url_encode("newsTitle")]);
      
		  try{
		                //konekcija na server i bazu
                    $connectToDb = connectToDb();
                    
                    //dohvatanje podataka iz forme
                    //slika
                    $postImage = $_FILES['postImage']['name'];
                    $postImageTmp = $_FILES['postImage']['tmp_name'];
                    $fileType = $_FILES["postImage"]["type"]; // ekstenzija slike
                    $fileSize = $_FILES["postImage"]["size"]; // velicina slike u bajtovima
                    $fileErrorMsg = $_FILES["postImage"]["error"]; // 0 ako ne postoji greska 1 ako postoji
                    $kaboom = explode(".", $postImage); // rastavljanje imena slike i ekstenzije
                    $fileExt = end($kaboom); // dohvatanje zadnjeg polja niza
                    $toExecute = TRUE;
                    
                    //dohvatanje slike u slucaju da nije nova upload-ovana
                    if(!is_uploaded_file($postImageTmp) && $whereToUpdate == "slider"){
                        $fetchImage = $connectToDb->prepare("SELECT * FROM slider WHERE title = :titleOfNews AND id = :postID");
                        $fetchImage->bindParam(":titleOfNews", $titleOfNews);
                        $fetchImage->bindParam(":postID", $postID);
                        $fetchImage->execute();
                        if($fetchImage->rowCount() > 0){
                          foreach($fetchImage as $aa){
                            $postImage = $aa['image'];
                          }
                        }
                        $toExecute = FALSE;
                    } else if(!is_uploaded_file($postImageTmp) && $whereToUpdate == "vijest"){
                        $fetchImage = $connectToDb->prepare("SELECT * FROM news WHERE news_title = :titleOfNews AND news_id = :postID");
                        $fetchImage->bindParam(":titleOfNews", $titleOfNews);
                        $fetchImage->bindParam(":postID", $postID);
                        $fetchImage->execute();
                        if($fetchImage->rowCount() > 0){
                            foreach($fetchImage as $aa){
                              $postImage = $aa['news_image'];
                            }
                          }
                         $toExecute = FALSE;
                    }
                    
                    //datum objave
                    $date = date('o-m-d G:i:s');
                    
                    //sadrzaj
                    $editorContent = (string)$_POST['postContent'];
                    
                    //autor
                    $authorID = $_SESSION['AUTHOR_USERID'];
                    
              
                    //  provjera da li u postoji oznaka 
                    $keywordQuery = $connectToDb->prepare("SELECT * FROM keywords WHERE keywords_content = :title");
                    $keywordQuery->bindParam(":title", $_POST['postKeywords']);
                    $keywordQuery->execute();
              
                    if($keywordQuery->rowCount() > 0) {
                        //oznaka postoji i dohvatanje ID-a
                        foreach($keywordQuery as $aaa){
                            $keyID = $aaa['keywords_id'];
                    }
                    } else {
                        //oznaka ne postoji pa se kreiranje te oznake
                        $insertKey = $connectToDb->prepare("INSERT INTO keywords(keywords_content) 
                                    VALUES(:key_title)");
                        $insertKey->bindParam(":key_title", $_POST['postKeywords']);
                        $insertKey->execute();
                        $keyID = $connectToDb->lastInsertId();
                    }
              
                    if($whereToUpdate == "slider"){
                      
                      //podesavalje rednih brojeva slidera
                       $c = 0;
                       for($a=1;$a<=6;$a++){
                           $sliderBrQuery = $connectToDb->query("SELECT * FROM slider WHERE red_br='$a'");
                           if($sliderBrQuery->rowCount() == 0){
                               $c = $a;
                               break;
                           }
                       }
                       if($c > $_POST['redBrSlide'])
                       for($a=$c;$a>=$_POST['redBrSlide'];$a--){
                            if($a == 6)
                            continue;
                            $d = $a+1;
                            $sliderBrQuery = $connectToDb->prepare("UPDATE slider SET red_br = '$d' WHERE red_br = '$a'");
                            $sliderBrQuery->execute();
                       }
                
                        //upload slike na server u folder slider
                        while($toExecute){
                        //prebacivanje slike u folder
                        $moveResult = move_uploaded_file($postImageTmp, "../../images/slider/resized_$postImage");
                        // provjera da li je slika uploadovana na server
                        if ($moveResult != true) {
                            echo "ERROR: Fajl nije poslan na server.";
                            unlink($postImageTmp); // uklanjanje slike iz tmp foldera
                            exit();
                        }
                        
                        // ---------- Include Universal Image Resizing Function --------
                        include_once("ak_php_img_lib_1.0.php");
                        $target_file = "../../images/slider/resized_$postImage";
                        $resized_file = "../../images/slider/$postImage";
                        $wmax = 750;
                        $hmax = 390;
                        ak_img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
                        //brisanje prave slike
                        unlink($target_file);
                        $toExecute = FALSE;
                        }
                        //upload podataka u bazu
                        $updateSlideData = "UPDATE slider
                                            SET red_br = :red_br, 
                                                title = :title, 
                                                content = :content, 
                                                image = :image, 
                                                author_id = :author_id, 
                                                keywords_id = :keywords_id, 
                                                slider_last_updated = :last_updated
                                          WHERE title = '$titleOfNews' AND id = '$postID'";
                                          
                        $updating = $connectToDb->prepare($updateSlideData);
                        $updating->bindParam(":red_br", $_POST['redBrSlide']);
                        $updating->bindParam(":title", $_POST['postTitle']);
                        $updating->bindParam(":content", $editorContent);
                        $updating->bindParam(":image", $postImage);
                        $updating->bindParam(":author_id", $authorID);
                        $updating->bindParam(":keywords_id", $keyID);
                        $updating->bindParam(":last_updated", $date);
                        $result = $updating->execute();
                        
                        //provjera da li postoji prvi slider ako ne postoji postavljanje narednog slidera na prvo mjesto
                        $checkIfNotExistFirstSlider = $connectToDb->query("SELECT * FROM slider WHERE red_br = '1'");
                        if($checkIfNotExistFirstSlider->rowCount() == 0){
                         for($a=1;$a<=6;$a++){
                             $connectToDb->query("UPDATE slider SET red_br = '1' WHERE red_br = '$a'");
                         }
                       }
                       
                        if($result){
                          //preusmjeravanje na stranicu
                          $redirectToPage = "slider.php";
                        } else {
                          $redirectToPage = "500.php";
                        }
                        
                        
                
                  } else if($whereToUpdate == "vijest"){
                
                        //upload slike na server u folder news
                        while($toExecute){
                        //prebacivanje slike u folder
                        $moveResult = move_uploaded_file($postImageTmp, "../../images/news/resized_$postImage");
                        // provjera da li je slika uploadovana na server
                        if ($moveResult != true) {
                            echo "ERROR: Fajl nije poslan na server.";
                            unlink($postImageTmp); // uklanjanje slike iz tmp foldera
                            exit();
                        }
                       
                        // ---------- Include Universal Image Resizing Function --------
                        include_once("ak_php_img_lib_1.0.php");
                        $target_file = "../../images/news/resized_$postImage";
                        $resized_file = "../../images/news/$postImage";
                        $wmax = 750;
                        $hmax = 390;
                        ak_img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
                        //brisanje prave slike
                        unlink($target_file);
                        $toExecute = FALSE;
                        }
                        //upload podataka u bazu
                        $updateNewsData = "UPDATE news
                                                SET news_title = :title, 
                                                    news_content = :content, 
                                                    news_image = :image, 
                                                    author_id = :author_id, 
                                                    keywords_id = :keywords_id, 
                                                    news_last_updated = :last_updated
                                              WHERE news_title = '$titleOfNews' AND news_id = '$postID'";
                                              
                        $updating = $connectToDb->prepare($updateNewsData);
                        $updating->bindParam(":title", $_POST['postTitle']);
                        $updating->bindParam(":content", $editorContent);
                        $updating->bindParam(":image", $postImage);
                        $updating->bindParam(":author_id", $authorID);
                        $updating->bindParam(":keywords_id", $keyID);
                        $updating->bindParam(":last_updated", $date);
                        $result = $updating->execute();
                        
                        if($result){
                           //preusmjeravanje na stranicu
                          $redirectToPage = "vijesti.php";
                        } else {
                          $redirectToPage = "500.php";
                        }
                       
                
                  } else {
                        //preusmjeravanje na stranicu
                        $redirectToPage = 'index.php';
                  }
              
                      $connectToDb = NULL;
                      header("Location: ../$redirectToPage");
                      exit();
    }
    catch(PDOException $e){
      $e->getMessage();
      exit();
    }
    ?>
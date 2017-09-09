<?php 	  
      include '../functions/secure.php';
      include '../core/connect.php';
      session_start();
      checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
		  if(!detect_get("postType")){
		  	header('Location: ../index.php');
        session_unset();
		  	session_destroy();
			  exit();
		  }
    
		  $whereToUpload = base64_url_decode($_GET[base64_url_encode("postType")]);
      
		try {
		  //konekcija na server i bazu
          $connectToDb = connectToDb();
          
          chmod("../../images/news",0777); 

          //dohvatanje varijabli iz forme
          $postImage = $_FILES['postImage']['name']; // ime slike
		      $postImageTmp = $_FILES['postImage']['tmp_name']; // privremeno ime slike
          $fileType = $_FILES["postImage"]["type"]; // ekstenzija slike
          $fileSize = $_FILES["postImage"]["size"]; // velicina slike u bajtovima
          $fileErrorMsg = $_FILES["postImage"]["error"]; // 0 ako ne postoji greska 1 ako postoji
          $kaboom = explode(".", $postImage); // rastavljanje imena slike i ekstenzije
          $fileExt = end($kaboom); // dohvatanje zadnjeg polja niza
          
          if (!$postImageTmp) { // ako fajl nije izabran
            header('location: ../500.php');
            exit();
          } else if($fileSize > 8388608) { // ako je velicina slike veca od 5
              header('location: ../500.php');
              unlink($postImageTmp); // uklanjanje slike sa servera
              exit();
          } else if (!preg_match("/.(gif|jpg|png|jpeg)$/i", $postImage) ) {
               // provjera ekstenzije slike
               header('location: ../500.php');
               unlink($postImageTmp); // uklanjanje slike sa servera
               exit();
          } else if ($fileErrorMsg == 1) { // ako postoji greska u uploadu
              header('location: ../500.php');
              exit();
          }
          


          //datum objave
          $date = date('o.m.d');
          
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
          
          
          // spremanje u bazu
          if($whereToUpload == "vijest"){
            

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

         
         $newNewsData = "INSERT INTO news (news_title, news_content, news_date, author_id, news_image, keywords_id)
                             VALUES (:title, :content, :date, :author_id, :image, :keywords_id)";
                              
         $new = $connectToDb->prepare($newNewsData);
         $new->bindParam(":title", $_POST['postTitle']);
         $new->bindParam(":content", $editorContent);
         $new->bindParam(":image", $postImage);
         $new->bindParam(":date", $date);
         $new->bindParam(":author_id", $authorID);
         $new->bindParam(":keywords_id", $keyID);
         $result = $new->execute();
         
         if($result)
         $redirectToPage = 'vijesti.php';
         else 
          $redirectToPage = '500.php';
            
          } else if($whereToUpload == "slider"){
            
            //redni broj slajda
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
         
         $newSlideData = "INSERT INTO slider (red_br, title, content, image, date, author_id, keywords_id)
                             VALUES (:red_br, :title, :content, :image, :date, :author_id, :keywords_id)";
                              
         $new = $connectToDb->prepare($newSlideData);
         $new->bindParam(":red_br", $_POST['redBrSlide']);
         $new->bindParam(":title", $_POST['postTitle']);
         $new->bindParam(":content", $editorContent);
         $new->bindParam(":image", $postImage);
         $new->bindParam(":date", $date);
         $new->bindParam(":author_id", $authorID);
         $new->bindParam(":keywords_id", $keyID);
         $result = $new->execute();
         
         //provjera da li postoji prvi slider ako ne postoji postavljanje narednog slidera na prvo mjesto
         $checkIfNotExistFirstSlider = $connectToDb->query("SELECT * FROM slider WHERE red_br = '1'");
         if($checkIfNotExistFirstSlider->rowCount() == 0){
            for($a=1;$a<=6;$a++){
                $connectToDb->query("UPDATE slider SET red_br = '1' WHERE red_br = '$a'");
            }
         }
         //preusmjeravanje na stranicu
         if($result){
           $redirectToPage = 'slider.php';
         } else {
           $redirectToPage = '500.php';
         }
          
          } else {
            $redirectToPage = 'index.php';
          }
          
          chmod("../../images/news",0755); 

          $connectToDb = NULL;
          header("Location: ../$redirectToPage");
          exit();
    }
    catch(PDOException $e){
      $e->getMessage();
      exit();   
       } ?>
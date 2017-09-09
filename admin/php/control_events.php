<?php
	  include '../functions/secure.php';
      include '../core/connect.php';
      session_start();
      checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);

      if(isset($_GET[base64_url_encode("action")])){
      	$action = base64_url_decode($_GET[base64_url_encode("action")]);

        if($action == "insert" || $action == "edit") {
            #konekcija na bazu
            $connect = connectToDb();

            #naslov dogadjaja
            $eventTitle = $_POST['title'];

            #sadrzaj dogadjaja
            $eventContent = $_POST['postContent'];

            # vrijeme pocetka dogadjaja
            $dateStart = explode("/", $_POST['startDate']);
            $timestart = $_POST['startTime'] .":00";
            $eventStart = $dateStart[2]."-".$dateStart[1]."-".$dateStart[0]." ".$timestart;

            $postDate = date('o-m-d');
            $postTime = date('G:i:s');

            # provjera da li su ispravno uneseni sati za pocetak dogadjaja
            $checkTime = explode(":", $_POST['startTime']);
            $checkTime[0] = (int) $checkTime[0];
            if($checkTime[0] > 23 || $checkTime[0] < 0){
              $url = "izmjena_dogadjaji.php?".base64_url_encode("eventTitle") ."=".base64_url_encode($eventTitle)."&"
                      .base64_url_encode("eventContent")."=".base64_url_encode($eventContent)."&";
              header('refresh:4;url=../izmjena_dogadjaji.php');
              die("<b>Unijeli ste pogrešno vrijeme. Sati ne može biti veći od 23.</b>");
              exit();
            }

            # provjera da li su ispravno unesene minute za pocetak dogadjaja
            if($checkTime[1] > 59 || $checkTime[1] < 0){
               $url = "izmjena_dogadjaji.php?".base64_url_encode("eventTitle") ."=".base64_url_encode($eventTitle)."&"
                      .base64_url_encode("eventContent")."=".base64_url_encode($eventContent)."&";
              header('refresh:4;url=../izmjena_dogadjaji.php');
              die("<b>Unijeli ste pogrešno vrijeme. Minute ne mogu biti veći od 59.</b>");
              exit();
            }

            # datum kraja dogadjaja
            if($_POST['endDate'] != "") {
              $dateEnd = explode("/", $_POST['endDate']);
              $endDate= $dateEnd[2]."-".$dateEnd[1]."-".$dateEnd[0];
            } else {
              $endDate = "0000-00-00";
            }
            
            # vrijeme kraja dogadjaja
            if($_POST['endTime'] != ''){
              $endTime = $_POST['endTime'] . ":00";
            } else {
              $endTime = "00:00:00";
            }
            $eventEnd = $endDate . " " . $endTime;
            
            # mjesto odrzavanja dogadjaja
            $eventPlace = $_POST['place'];

        }
      	if($action == "insert") {

          try {

            	  #dohvatanje varijabli slike iz forme
                $postImage = $_FILES['postImage']['name']; # ime slike
      		      $postImageTmp = $_FILES['postImage']['tmp_name']; # privremeno ime slike
                $fileType = $_FILES["postImage"]["type"]; # ekstenzija slike
                $fileSize = $_FILES["postImage"]["size"]; # velicina slike u bajtovima
                $fileErrorMsg = $_FILES["postImage"]["error"]; # 0 ako ne postoji greska 1 ako postoji
                $kaboom = explode(".", $postImage); # rastavljanje imena slike i ekstenzije
                $fileExt = end($kaboom); # dohvatanje zadnjeg polja niza
                
                if (!$postImageTmp) { # ako fajl nije izabran
                  header('location: ../500.php');
                  exit();
                } else if($fileSize > 8388608) { # ako je velicina slike veca od 5
                    header('location: ../500.php');
                    unlink($postImageTmp); # uklanjanje slike sa servera
                    exit();
                } else if (!preg_match("/.(gif|jpg|png|jpeg)$/i", $postImage) ) {
                     # provjera ekstenzije slike
                     header('location: ../500.php');
                     unlink($postImageTmp); # uklanjanje slike sa servera
                     exit();
                } else if ($fileErrorMsg == 1) { # ako postoji greska u uploadu
                    header('location: ../500.php');
                    exit();
                }
            	
            	//prebacivanje slike u folder
                $moveResult = move_uploaded_file($postImageTmp, "../../images/events/resized_$postImage");
                // provjera da li je slika uploadovana na server
                if ($moveResult != true) {
                    echo "ERROR: Fajl nije poslan na server.";
                    unlink($postImageTmp); // uklanjanje slike iz tmp foldera
                    exit();
                }
                
                // ---------- Include Universal Image Resizing Function --------
                include_once("ak_php_img_lib_1.0.php");
                $target_file = "../../images/events/resized_$postImage";
                $resized_file = "../../images/events/$postImage";
                $wmax = 225;
                $hmax = 240;
                ak_img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
                //brisanje prave slike
                unlink($target_file);

              
          		$insertEvent = $connect->prepare("INSERT INTO events (event_title, event_content, event_place, event_image, event_date_post, event_time_post, author_id, event_start_date, event_end_date) VALUES (:title, :content, :place, :image, :date_post, :time_post, :author, :start_event, :end_event)");
          		$insertEvent->bindParam(":title", $eventTitle);
          		$insertEvent->bindParam(":content", $eventContent);
          		$insertEvent->bindParam(":place", $eventPlace);
          		$insertEvent->bindParam("image", $postImage);
          		$insertEvent->bindParam(":date_post", $postDate);
          		$insertEvent->bindParam("time_post", $postTime);
          		$insertEvent->bindParam("author", $_SESSION['AUTHOR_USERID']);
          		$insertEvent->bindParam(":start_event", $eventStart);
          		$insertEvent->bindParam(":end_event", $eventEnd);
          		$result = $insertEvent->execute();

          		$connect = NULL;

          		if($result){
          			$connect = NULL;
          			header('location: ../dogadjaji.php');
          			exit();
          		} else {
          			$connect = NULL;
          			header('location: ../500.php');
          			exit();
          		}



      		} catch (Exception $e) {
      					    $connect = NULL;
      		      		$e->getMessage();
      		      		exit();
      		      	}

      } else if($action == "edit") {

        try {
                #vrijeme zadnje promjene
                $date = date('o-m-d H:i:s');
                if(isset($_GET[base64_url_encode("eventID")])){
                    $IDOfEvent = base64_url_decode($_GET[base64_url_encode("eventID")]);
                } else {
                  $connect = NULL;
                  header('location: ../500.php');
                  exit();
                }
                #dohvatanje varijabli slike iz forme
                $postImage = $_FILES['postImage']['name']; # ime slike
                $postImageTmp = $_FILES['postImage']['tmp_name']; # privremeno ime slike
                $fileType = $_FILES["postImage"]["type"]; # ekstenzija slike
                $fileSize = $_FILES["postImage"]["size"]; # velicina slike u bajtovima
                $fileErrorMsg = $_FILES["postImage"]["error"]; # 0 ako ne postoji greska 1 ako postoji
                $kaboom = explode(".", $postImage); # rastavljanje imena slike i ekstenzije
                $fileExt = end($kaboom); # dohvatanje zadnjeg polja niza

                if(!is_uploaded_file($postImageTmp)){
                    $fetchImage = $connect->query("SELECT event_image FROM events WHERE event_id = '$IDOfEvent'");
                    foreach ($fetchImage as $row) {
                      $eventImage = $row['event_image'];
                      break;
                    }
                } else {

                    $eventImage = $postImage;

                    #prebacivanje slike u folder
                    $moveResult = move_uploaded_file($postImageTmp, "../../images/events/resized_$postImage");
                    // provjera da li je slika uploadovana na server
                    if ($moveResult != true) {
                        echo "ERROR: Fajl nije poslan na server.";
                        unlink($postImageTmp); // uklanjanje slike iz tmp foldera
                        exit();
                    }
                    
                    // ---------- Include Universal Image Resizing Function --------
                    include_once("ak_php_img_lib_1.0.php");
                    $target_file = "../../images/events/resized_$postImage";
                    $resized_file = "../../images/events/$postImage";
                    $wmax = 225;
                    $hmax = 240;
                    ak_img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
                    //brisanje prave slike
                    unlink($target_file);

                }

                $updateEvent = $connect->prepare("UPDATE events SET 
                                                          event_title = :title,
                                                          event_content = :content, 
                                                          event_place = :place, 
                                                          event_image = :image,
                                                          event_start_date = :start, 
                                                          event_end_date = :end, 
                                                          event_last_updated = :last_updated 
                                                          WHERE event_id = :id");
                $updateEvent->bindParam(":title", $eventTitle);
                $updateEvent->bindParam(":content", $eventContent);
                $updateEvent->bindParam(":place", $eventPlace);
                $updateEvent->bindParam(":image", $eventImage);
                $updateEvent->bindParam(":start", $eventStart);
                $updateEvent->bindParam(":end", $eventEnd);
                $updateEvent->bindParam(":last_updated", $date);
                $updateEvent->bindParam("id", $IDOfEvent);
                $result = $updateEvent->execute();

                $connect = NULL;

              if($result){
                $connect = NULL;
                header('location: ../dogadjaji.php');
                exit();
              } else {
                $connect = NULL;
                header('location: ../500.php');
                exit();
              }

          
        } catch (Exception $e) {
                    $connect = NULL;
                    $e->getMessage();
                    exit(); 
        }

      } else if ($action == "delete"){

            $connect = connectToDb();
            if(isset($_GET[base64_url_encode("eventID")])){
              $IDOfEvent = base64_url_decode($_GET[base64_url_encode("eventID")]);
            } else {
              $connect = NULL;
              header('location: ../500.php');
              exit();
            }
            $deleteEvent = $connect->prepare("DELETE FROM events WHERE event_id = :id");
            $deleteEvent->bindParam(":id", $IDOfEvent);

            if(isset($_GET[base64_url_encode("delete")])){
              if(base64_url_decode($_GET[base64_url_encode("delete")]) == "yes"){
                $result = $deleteEvent->execute();
                if($result){
                  $connect = NULL;
                  header('location: ../dogadjaji.php');
                  exit();
                } else {
                  $connect = NULL;
                  header('location: ../500.php');
                  exit();
                }
              }
            } else {
              $connect = NULL;
              header('location: ../500.php');
              exit();
            }

      } else {
      	header('location: ../500.php');
      	exit();
      }
  } else {
      	header('location: ../500.php');
      	exit();
  }
	
?>
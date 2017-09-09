<?php

if(isset($_POST['g-recaptcha-response'])&& $_POST['g-recaptcha-response']){

		include 'core/connection.php';

		 $whatcomment = explode(".", $_POST['whatcomment']);
		 $ID = end($whatcomment);
		 $type = $whatcomment[0];
		 if ($type == "news") {
  		 	$news_id = $ID;
  		 	$slider_id = 0;
        $event_id = 0;
		 } else if ($type == "event"){
        $news_id = 0;
        $slider_id = 0;
        $event_id = $ID;
     } else if($type == "slider") {
        $news_id = 0;
        $slider_id = $ID;
        $event_id = 0;
     }

		 if($_POST['firstname-id'] == "" || $_POST['lastemail-id'] == "" || $_POST['email-id'] == "" || $_POST['comment'] == ""){
  		 	header('location: vijest_detalji.php?ID='.$ID.'&title='.$whatcomment[1]);
  		 	exit();
		 }
		//podaci koje je korisnik unio
		$firstName = $_POST['firstname-id'];
		$lastName = $_POST['lastemail-id'];
		$email = $_POST['email-id'];
		$comment = $_POST['comment'];

		//podaci o korinsku
    $details = json_decode(file_get_contents("http://ipinfo.io/{$_SERVER['REMOTE_ADDR']}/json"));
    $hostname=gethostbyaddr($_SERVER['REMOTE_ADDR']);
    $jsonCountry = file_get_contents('country.json');
    $fullCountryName = json_decode($jsonCountry);
        
		$provider = $details->org;
		$region = $details->region;
		$shortCountryName = $details->country;
    $country = $fullCountryName->$shortCountryName;
    $city = $details->city;
		$userPhoto = "no-profile-image.jpg";
    $date = date('o-m-d');
    $time = date('G:i:s');

    $secret = "6LeidRgTAAAAAO_Uup3VvtXGwTHlpAKtKXz05PWd";
    $ip = $_SERVER['REMOTE_ADDR'];
    $captcha = $_POST['g-recaptcha-response'];
    $rsp  = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha&remoteip$ip");
    $arr = json_decode($rsp,TRUE);
    if($arr['success']){
       $connect = connectOnDb();

       $insertComment = $connect->prepare("INSERT INTO comments (user_firstname, user_lastname, user_email, user_comment, user_photo, user_ipaddress, user_date_post, user_time_post, news_id, slider_id, event_id, user_location_country, user_isp_provider, user_location_region, user_location_city) 
       										VALUES(:firstname, :lastname, :email, :comment, :photo, :ipaddress, :date_post, :time_post, :news_id, :slider_id, :event_id, :country, :provider, :region, :city)");
       $insertComment->bindParam(":firstname", $firstName);
       $insertComment->bindParam(":lastname", $lastName);
       $insertComment->bindParam(":email", $email);
       $insertComment->bindParam(":comment", $comment);
       $insertComment->bindParam(":photo", $userPhoto);
       $insertComment->bindParam(":ipaddress", $ip);
       $insertComment->bindParam(":date_post", $date);
       $insertComment->bindParam(":time_post", $time);
       $insertComment->bindParam(":news_id", $news_id);
       $insertComment->bindParam(":slider_id", $slider_id);
       $insertComment->bindParam(":event_id", $event_id);
       $insertComment->bindParam(":country", $country);
       $insertComment->bindParam(":provider", $provider);
       $insertComment->bindParam(":region", $region);
       $insertComment->bindParam(":city", $city);
       
       $result = $insertComment->execute();
       
       if($news_id != 0) {
         $fetchNumOfComm = $connect->query("SELECT num_comment FROM news WHERE news_id = '$news_id'");
         while($r = $fetchNumOfComm->fetch(PDO::FETCH_OBJ)){
             $commnumber = $r->num_comment;
         }
         $commnumber = $commnumber + 1;
         $insertCommNumber = $connect->query("UPDATE news SET num_comment = '$commnumber' WHERE news_id = '$news_id'");
       } else if($slider_id != 0) {
         $fetchNumOfComm = $connect->query("SELECT slider_num_comment FROM slider WHERE id = '$slider_id'");
         while($r = $fetchNumOfComm->fetch(PDO::FETCH_OBJ)){
             $commnumber = $r->num_comment;
         }
         $commnumber = $commnumber + 1;
         $insertCommNumber = $connect->query("UPDATE slider SET slider_num_comment = '$commnumber' WHERE id = '$slider_id'");
       }

       if($result && $news_id != 0){
            $connect = NULL;
            header('location: vijest_detalji.php?ID='.$ID.'&title='.$whatcomment[1]);
            exit();
       } else if($result && $event_id != 0) {
            $connect = NULL;
            header('location: dogadjaji_detalji.php?ID='.$ID.'&title='.$whatcomment[1]);
            exit();
       } else if($result && $slider_id != 0) {
            $connect = NULL;
            header('location: slider_detalji.php?ID='.$ID.'&title='.$whatcomment[1]);
            exit();
       }



    }else{
        echo 'Spam';
    }
    
}


?>

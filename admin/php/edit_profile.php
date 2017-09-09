<?php 	  
  include '../functions/secure.php';
  include '../core/connect.php';
  session_start();
  checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);

  if(isset($_GET[base64_url_encode("edit")])) {
  	$edit = base64_url_decode($_GET[base64_url_encode("edit")]);

  	if($edit == "data") {
  		
		if($_POST['name'] == "" || $_POST['surname'] == "" || $_POST['email'] == "" || $_POST['cv'] == "" || $_POST['sex'] == "") {
			header('location: ../500.php');
			exit();
		}
		if(strlen($_POST['pw']) < 8){
  			echo "Šifra mora sadržavati minimalno 8 karaktera.";
  			exit();
  		}

		// konekcija na bazu
	     $connect = connectToDb();

		$name = strip_tags(htmlspecialchars($_POST['name']));

		$surname = strip_tags(htmlspecialchars($_POST['surname']));

		$email = strip_tags($_POST['email']);
		$email = preg_replace("/\n|\r/", "", $email);

		$cv = strip_tags($_POST['cv']);

		$sex = $_POST['sex'];

		$date = date('o.m.d G:i:s');

		// dohvatanje starih podataka autora
		$selectMailFromDb = $connect->prepare("SELECT * FROM authors WHERE author_id = :id LIMIT 1");
		$selectMailFromDb->bindParam(":id", $_SESSION['AUTHOR_USERID']);
		$selectMailFromDb->execute();
		foreach ($selectMailFromDb as $key => $value) {
			$oldMail = $value['author_email'];
			$Pass = $value['author_password'];
		}
		// provjera sifre autora
		$inputPass = strip_tags($_POST['pw']);
		$userPass = cryptPass($inputPass);

		if($userPass != $Pass) {
			$connect = NULL;
			echo "Neispravna šifra.";
			exit();
		}

		// provjera da li postoji vec taj mail 
		if($oldMail != $email) {
			$checkIfMailExist = $connect->query("SELECT COUNT(author_id) AS AUTHOREXISTMAIL FROM authors WHERE author_email = '$email'");
			$row = $checkIfMailExist->fetch(PDO::FETCH_ASSOC);
			$numOfAuthors = $row['AUTHOREXISTMAIL'];
			if($numOfAuthors > 0) {
				$connect = NULL;
				echo "Mail koji se unijeli već potoji. Molimo unesite drugi mail.";
				exit();
			}
		}

		//dohvatanje varijabli iz forme
          $postImage = $_FILES['postImage']['name']; // ime slike
		  $postImageTmp = $_FILES['postImage']['tmp_name']; // privremeno ime slike
          $fileType = $_FILES["postImage"]["type"]; // ekstenzija slike
          $fileSize = $_FILES["postImage"]["size"]; // velicina slike u bajtovima
          $fileErrorMsg = $_FILES["postImage"]["error"]; // 0 ako ne postoji greska 1 ako postoji
          $kaboom = explode(".", $postImage); // rastavljanje imena slike i ekstenzije
          $fileExt = end($kaboom); // dohvatanje zadnjeg polja niza

          if(!is_uploaded_file($postImageTmp)) {
          	    // dohvatanje slike iz baze
	          	$fetchImage = $connect->prepare("SELECT * FROM authors WHERE author_id = :id LIMIT 1");
	            $fetchImage->bindParam(":id", $_SESSION['AUTHOR_USERID']);
	            $fetchImage->execute();
	            if($fetchImage->rowCount() > 0){
	              foreach($fetchImage as $aa){
	                $postImage = $aa['author_image'];
	              }
	            }
          } else {
          	 if($fileSize > 8388608) { // ako je velicina slike veca od 8 MB
	              header('location: ../500.php');
	              unlink($postImageTmp); // uklanjanje slike sa servera
	              exit();
	          } else if (!preg_match("/.(jpg|png|jpeg)$/i", $postImage) ) {
	               // provjera ekstenzije slike
	               header('location: ../500.php');
	               unlink($postImageTmp); // uklanjanje slike sa servera
	               exit();
	          } else if ($fileErrorMsg == 1) { // ako postoji greska u uploadu
	              header('location: ../500.php');
	              exit();
	          }
          	  //prebacivanje slike u folder
	          $moveResult = move_uploaded_file($postImageTmp, "../../images/prof/$postImage");
	          // provjera da li je slika uploadovana na server
	          if ($moveResult != true) {
	              echo "ERROR: Fajl nije poslan na server.";
	              unlink($postImageTmp); // uklanjanje slike iz tmp foldera
	              exit();
	          }
	          
	          
	      }

	      // izmjena profila
	      $editProfile = $connect->prepare("UPDATE authors SET  author_email = :email, 
	      														author_name = :name, 
	      														author_surname = :surname, 
	      														author_image = :image, 
	      														author_sex = :sex, 
	      														author_cv = :cv, 
	      														author_last_updated = :last_edited WHERE author_id = :id");
	      $editProfile->bindParam(":email", $email);
	      $editProfile->bindParam(":name", $name);
	      $editProfile->bindParam(":surname", $surname);
	      $editProfile->bindParam(":image", $postImage);
	      $editProfile->bindParam(":sex", $sex);
	      $editProfile->bindParam(":cv", $cv);
	      $editProfile->bindParam(":last_edited", $date);
	      $editProfile->bindParam(":id", $_SESSION['AUTHOR_USERID']);

	      $result = $editProfile->execute();
	      if($result) {
	      	$connect = NULL;
	      	header('location: ../account_settings.php');
	      	exit();
	      } else {
	      	$connect = NULL;
	      	header('location: ../500.php');
	      	exit();
	      }


  	} else if ($edit == "setDefaultPhoto") {
  		if($_POST['pw'] == "") {
  			header('location: ../500.php');
			exit();
  		}
  		if(strlen($_POST['pw']) < 8){
  			echo "Šifra mora sadržavati minimalno 8 karaktera.";
  			exit();
  		}
  		// konekcija na bazu
  		$connect = connectToDb();
  		// naziv slike 
  		$image = "no-profile-image.jpg";
  		// dohvatanje starih podataka autora
		$selectAuthFromDb = $connect->prepare("SELECT * FROM authors WHERE author_id = :id LIMIT 1");
		$selectAuthFromDb->bindParam(":id", $_SESSION['AUTHOR_USERID']);
		$selectAuthFromDb->execute();
		foreach ($selectAuthFromDb as $key => $value) {
			$Pass = $value['author_password'];
		}
		// provjera sifre autora
		$inputPass = strip_tags($_POST['pw']);
		$userPass = cryptPass($inputPass);

		if($userPass != $Pass) {
			$connect = NULL;
			echo "Neispravna šifra.";
			exit();
		}
		// stavljanje defaultne slike 
		$setDefaultPhoto = $connect->prepare("UPDATE authors SET author_image = :image WHERE author_id = :id");
		$setDefaultPhoto->bindParam(":image", $image);
		$setDefaultPhoto->bindParam(":id", $_SESSION['AUTHOR_USERID']);
		$result = $setDefaultPhoto->execute();
		// provjera da li je slika vracena na zadano
		if($result) {
	      	$connect = NULL;
	      	header('location: ../account_settings.php');
	      	exit();
	      } else {
	      	$connect = NULL;
	      	header('location: ../500.php');
	      	exit();
	      }

  	} else if($edit == "changeAuthorPassword") {
  		// provjera ispravnosti unesenih podataka
  		if($_POST['n_pw'] == "" || $_POST['o_pw'] == "" || $_POST['n_r_pw'] == "") {
  			header('location: ../500.php');
			exit();
  		}
  		if(strlen($_POST['n_pw']) < 8 || strlen($_POST['o_pw']) < 8){
  			echo "Šifra mora sadržavati minimalno 8 karaktera.";
  			exit();
  		}
  		if($_POST['n_pw'] != $_POST['n_r_pw']) {
  			echo "Šifre se ne poklapaju. Ispravite grešku.";
  			exit();
  		}
  		if(strlen($_POST['n_pw']) < 8){
  			echo "Šifra mora sadržavati minimalno 8 karaktera.";
  			exit();
  		}
  		// konekcija na bazu
  		$connect = connectToDb();
  		// dohvatanje starih podataka autora
		$selectAuthFromDb = $connect->prepare("SELECT * FROM authors WHERE author_id = :id LIMIT 1");
		$selectAuthFromDb->bindParam(":id", $_SESSION['AUTHOR_USERID']);
		$selectAuthFromDb->execute();
		foreach ($selectAuthFromDb as $key => $value) {
			//stara sifra
			$Pass = $value['author_password'];
		}
		// provjera sifre autora
		$inputPass = strip_tags($_POST['o_pw']);
		$userPass = cryptPass($inputPass);

		if($userPass != $Pass) {
			$connect = NULL;
			echo "Neispravna šifra.";
			exit();
		}

		// kriptovanje nove sifre
		$newPass = strip_tags($_POST['n_pw']);
		$newPass = cryptPass($newPass);
		// unosenje nove sifre u bazu
		$setNewPassword = $connect->prepare("UPDATE authors SET author_password = :pass WHERE author_id = :id");
		$setNewPassword->bindParam(":pass", $newPass);
		$setNewPassword->bindParam(":id", $_SESSION['AUTHOR_USERID']);
		$result = $setNewPassword->execute();
		// provjera da li je sifra promijenjena
		if($result) {
	      	$connect = NULL;
	      	header('location: ../account_settings.php');
	      	exit();
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
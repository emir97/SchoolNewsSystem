<?php 
	include '../core/connect.php';
	include '../functions/secure.php';

	if(isset($_POST)) {
		if($_POST['FullName'] == "" || $_POST['Email'] == "" || $_POST['Password'] == "" ||
			$_POST['ConfirmPassword'] == "" || $_POST['about'] == "") {
				$url = "../registration.php?".base64_url_encode("error")."=".base64_url_encode("Niste ispravno popunili formu.");
				header("location: $url");
				exit();
		}
		if(strlen($_POST['Password']) < 8)  {
			$url = "../registration.php?".base64_url_encode("error")."=".base64_url_encode("Šifra mora sadržavati najmanje 8 karaktera.");
			header("location: $url");
			exit();
		}
		if($_POST['Password'] != $_POST['ConfirmPassword'])  {
			$url = "../registration.php?".base64_url_encode("error")."=".base64_url_encode("Šifre se ne poklapaju.");
			header("location: $url");
			exit();
		}
		if(!isset($_POST['sex'])) {
			$url = "../registration.php?".base64_url_encode("error")."=".base64_url_encode("Niste izabrali spol.");
			header("location: $url");
			exit();
		}
		if(!isset($_POST['agreement'])) {
			$url = "../registration.php?".base64_url_encode("error")."=".base64_url_encode("Označite da se slažete sa pravilima stranice.");
			header("location: $url");
			exit();
		}
		$connection = connectToDb();
		// ime i prezime
		$FullName = strip_tags($_POST['FullName']);
		$expand = explode(" ", $FullName);
		$authorName = $expand[0];
		$authorName = preg_replace("/[^A-Za-z0-9\- čćžšđ]/", "", $expand[0]);
		$authorSurname = $expand[1];
		$authorSurname = preg_replace("/[^A-Za-z0-9\- čćžšđ]/", "", $expand[1]);
		//email
		$email = strip_tags($_POST['Email']);
		$email = filter_var($email, FILTER_SANITIZE_EMAIL);
		// provjera da li postoji vec taj mail
		$checkMail = $connection->prepare("SELECT author_id FROM authors WHERE author_email = :email");
		$checkMail->bindParam(":email", $email);
		$checkMail->execute();
		if($checkMail->rowCount() > 0) {
			$connection = NULL;
			$url = "../registration.php?".base64_url_encode("error")."=".base64_url_encode("Unesena email adresa već postoji.");
			header("location: $url");
			exit();
		}

		// autor sex
		$authorSex = $_POST['sex'];
		// sifra autora
		$authorPass = cryptPass($_POST['Password']);
		// autor cv 
		$authorCV = strip_tags($_POST['about']);
		$authorCV = preg_replace("/[^A-Za-z0-9\- čćđžš]/", "", $_POST['about']);

		$confirmCode = rand(1000000000, 9999999999);
		$confirmCodeWithoutEncriptyon = $confirmCode;
		$confirmCode = md5($confirmCode);
		// dohvatanje ip adrese
		$http_client_ip = $_SERVER['HTTP_CLIENT_IP'];
		$http_x_forwarded_for = $_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote_addr = $_SERVER['REMOTE_ADDR'];
		
		if(!empty($http_client_ip)){
			$ip_address = $http_client_ip;
		} else if(!empty($http_x_forwarded_for)){
			$ip_address = $http_x_forwarded_for;
		} else {
			$ip_address = $remote_addr;
		}

		$details = json_decode(file_get_contents("http://ipinfo.io/{$_SERVER['REMOTE_ADDR']}/json"));
	    $hostname=gethostbyaddr($_SERVER['REMOTE_ADDR']);
	    $jsonCountry = file_get_contents('country.json');
	    $fullCountryName = json_decode($jsonCountry);
        
		$region = $details->region;
		$shortCountryName = $details->country;
	    $country = $fullCountryName->$shortCountryName;
	    $city = $details->city;

		$insertAutor = $connection->prepare("INSERT INTO authors_register (author_register_email, author_register_password, author_register_name, author_register_surname,
		 author_register_sex, author_register_code, author_register_cv, author_register_country, author_register_city, author_register_region) 
																	VALUES (:email, :password, :name, :surname, :sex, :code, :cv, :country, :city, :region)");
		$insertAutor->bindParam(":email", $email);
		$insertAutor->bindParam(":password", $authorPass);
		$insertAutor->bindParam(":name", $authorName);
		$insertAutor->bindParam(":surname", $authorSurname);
		$insertAutor->bindParam(":sex", $authorSex);
		$insertAutor->bindParam(":code", $confirmCode);
		$insertAutor->bindParam(":cv", $authorCV);
		$insertAutor->bindParam(":country", $country);
		$insertAutor->bindParam(":city", $city);
		$insertAutor->bindParam(":region", $region);
		$result = $insertAutor->execute();

		// provjera da li je slika vracena na zadano
		if($result) {
			//url za akivaciju racuna
			$urlForActiveAcc = "http://etsmostar.edu.ba/admin/active_acc.php?".base64_url_encode("email")."=".base64_url_encode($email);
			$urlForActiveAcc .= "&".base64_url_encode("activateCode")."=".base64_url_encode($confirmCodeWithoutEncriptyon);
	      		// slanje maila
	      	require_once ("../PHPMailer/class.phpmailer.php");
			$mail = new PHPMailer;
			$mail->isSMTP();/*Set mailer to use SMTP*/
			$mail->Host = 'mail.etsmostar.edu.ba';/*Specify main and backup SMTP servers*/
			$mail->Port = 587;
			$mail->SMTPAuth = true;/*Enable SMTP authentication*/
			$mail->Username = "etsmo@etsmostar.edu.ba";/*SMTP username*/
			$mail->Password = "Ail757q#";/*SMTP password*/
			$mail->Secure = "ssl";
			//$mail->SMTPSecure = 'tls';/*Enable encryption, 'ssl' also accepted*/
			$mail->From = 'etsmo@etsmostar.edu.ba';
			$mail->FromName = "Registracija na ETS WEB";
			$mail->addAddress($email, 'Recipients Name');/*Add a recipient*/
			$mail->addReplyTo($email, "bb");
			$mail->isHTML(true);/*Set email format to HTML (default = true)*/
			$mail->Subject = "Registracija na ETS - Administrator stranice";
			$mail->Body    = "Da bi ste obavili prvi korak u registraciji idite na lik ispod i potvrdite vašu email adresu. $urlForActiveAcc";
			$mail->AltBody = "Registracija na ETS - Administrator stranice";
	      	if($mail->send()){
	      		$connect = NULL;
	      		$url = "../registration.php?".base64_url_encode("error")."=".base64_url_encode("Uspješno ste prijavljeni. Molimo da se prijavite na email da bi potvrdili prijavu. Ako poruka nije u inbox-u provjerite spam poruke.");
		      	header("location: $url");
		      	exit();
	      	} else {
	      		$url = "../registration.php?".base64_url_encode("error")."=".base64_url_encode("Registracija nije uspješno izvedena. Molimo pokušajte ponovo.");
		      	header("location: $url");
	      	}

	      } else {
	      	$connect = NULL;
	      	header('location: ../500.php');
	      	exit();
	      }

	}


?>
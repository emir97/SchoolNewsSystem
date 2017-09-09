<?php 
include('../core/connect.php'); 
include('../functions/secure.php');
session_start();
ob_start();
checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
if(!checkIsAdmin($_SESSION['AUTHOR_USERID'])){
    header('location: ../index.php');
    exit();
}
if(isset($_GET[base64_url_encode("addForAuthor")])) {
	$addAuthor = base64_url_decode($_GET[base64_url_encode("addForAuthor")]);
	if($addAuthor == "true") {
		if(!isset($_GET[base64_url_encode("ID")])) {
			header('location: ../500.php');
			exit();
		}
		$ID = base64_url_decode($_GET[base64_url_encode("ID")]);
		$ID = preg_replace("/[^0-9]/", "", $ID);

		// konekcija na bazu
		$connect = connectToDb();
		// dohvatanje authora iz tabele registrovani korisnici
		$fetchAuthor = $connect->prepare("SELECT * FROM authors_register WHERE author_register_id = :id LIMIT 1");
		$fetchAuthor->bindParam(":id", $ID);
		$fetchAuthor->execute();
		if($fetchAuthor->rowCount() == 1) {
			while ($value = $fetchAuthor->fetch(PDO::FETCH_OBJ)) {
				$name = $value->author_register_name;

				$surname = $value->author_register_surname;

				$email = $value->author_register_email;

				$password = $value->author_register_password;

				$sex = $value->author_register_sex;

				$cv = $value->author_register_cv;	

				$country = $value->author_register_country;

				$city = $value->author_register_city;

				$region = $value->author_register_region;	

				$image = "no-profile-image.jpg";		
			}

			$insertAuthor = $connect->prepare("INSERT INTO authors (author_email, author_password, author_name, author_surname, author_image, author_sex, author_cv, author_country, author_city, author_region)
															VALUES (:email, :password, :name, :surname, :image, :sex, :cv, :country, :city, :region)");
			$insertAuthor->bindParam(":email", $email);
			$insertAuthor->bindParam(":password", $password);
			$insertAuthor->bindParam(":name", $name);
			$insertAuthor->bindParam(":surname", $surname);
			$insertAuthor->bindParam(":image", $image);
			$insertAuthor->bindParam(":sex", $sex);
			$insertAuthor->bindParam(":cv", $cv);
			$insertAuthor->bindParam(":country", $country);
			$insertAuthor->bindParam(":city", $city);
			$insertAuthor->bindParam(":region", $region);
			$result = $insertAuthor->execute();
			if($result) {
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
				$mail->Body    = "Vaš zahtjev za uređivanjem stranice elektrotehničke škole je prihvaćen. Molimo da se pridržavate pravila korištenja bez stranačke propagande, vulagarnosti, itd... Hvala i Sretno.";
				$mail->AltBody = "Registracija na ETS - Administrator stranice";
				if($mail->send()) {
					$deleteAuthor = $connect->prepare("DELETE FROM authors_register WHERE author_register_id = :id LIMIT 1");
					$deleteAuthor->bindParam(":id", $ID);
					$result1 = $deleteAuthor->execute();
					if($result1) {
						$connect = NULL;
				      	header("location: ../active_author.php");
				      	exit();
					}
				} else {
					$connect = NULL;
			      	header("location: ../500.php");
			      	exit();
				}
			} else {
				$connect = NULL;
		      	header("location: ../500.php");
		      	exit();
			}
		}
	} else if($addAuthor == "false"){

		if(!isset($_GET[base64_url_encode("ID")])) {
			header('location: ../500.php');
			exit();
		}
		$ID = base64_url_decode($_GET[base64_url_encode("ID")]);
		$ID = preg_replace("/[^0-9]/", "", $ID);

		$connect = connectToDb();

		$deleteAuthor = $connect->prepare("DELETE FROM authors_register WHERE author_register_id = :id LIMIT 1");
		$deleteAuthor->bindParam(":id", $ID);
		$result = $deleteAuthor->execute();
		if($result) {
			$connect = NULL;
	      	header("location: ../active_author.php");
	      	exit();
		} else {
			$connect = NULL;
	      	header("location: ../500.php");
	      	exit();
		}
	} else {
		$connect = NULL;
      	header("location: ../500.php");
      	exit();
	}
} else {
	header("location: ../404.php");
    exit();
}

?>
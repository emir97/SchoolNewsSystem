<?php 
	include 'core/connect.php';
	include 'functions/secure.php';

	if(isset($_GET[base64_url_encode("email")]) && isset($_GET[base64_url_encode("activateCode")])) {

		$connect = connectToDb();

		$activateCode = base64_url_decode($_GET[base64_url_encode("activateCode")]);
		
		$activateCode = md5($activateCode);

		$email = base64_url_decode($_GET[base64_url_encode("email")]);

		$active = 1;

		$updateRegisterData = $connect->prepare("UPDATE authors_register SET author_register_email_active = :active WHERE author_register_email = :email AND author_register_code = :code");
		$updateRegisterData->bindParam(":active", $active);
		$updateRegisterData->bindParam(":email", $email);
		$updateRegisterData->bindParam(":code", $activateCode);
		$result = $updateRegisterData->execute();
		if($result) {
			echo "<html><head><title>Potvrda registracije</title></head><body><p>Uspjesno ste obavili prvi korak u registraciji. Sljedeci korak je da vas odobri adminstrator stranice. Ukoliko odobri vas zahtjev bit ce te obavijesteni email-om i moci ce te pristupiti vasem racunu. Hvala. </p></body></html>";
		} else {
			echo "<p>Potvrda email adrese nije uspjela. Pokusajte ponovo. </p>";
		}
		$connect = NULL;

	}

?>
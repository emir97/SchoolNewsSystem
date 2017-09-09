<?php 
	require '../core/connect.php';
	require '../functions/secure.php';
	session_start();
	$connection = connectToDb();

	if(isset($_POST["loginbugaround"])){
		
	//dohvatanje emaila i sifre
	$uname = trim(htmlentities(strip_tags($_POST['email'])));
	$inputPass = trim(strip_tags($_POST['password']));

	if(empty($uname)){
		//greska prilikom unosa email-a
		$_SESSION['errors'] = "Molimo unseite vaš email.";
		session_write_close();
		returnheader('../LogIn.php');
		exit();

	}
	if(empty($inputPass)){
		//greska prilikom unosa sifre
		$_SESSION['errors'] = "Molimo unesite vašu šifru.";
		session_write_close();
		returnheader('../LogIn.php');
		exit();
	}

	if(!$errors){
		
		$userPass = "";

		$fetchAuthorPass = $connection->prepare("SELECT author_password FROM authors WHERE author_email = :autemail LIMIT 1");
		$fetchAuthorPass->bindParam(":autemail", $uname);
		$fetchAuthorPass->execute();

		if($fetchAuthorPass->rowCount() == 1){
			foreach ($fetchAuthorPass as $row) {
					$userPass = $row['author_password'];
				}	
		
		} else {
			$_SESSION['errors'] = "Neispravna email adresa. Pokušajte ponovo.";
			session_write_close();
			returnheader('../LogIn.php');
			exit();
		}
		$cryptedInputPass = cryptPass($inputPass);

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

		if($cryptedInputPass === $userPass){

		$queryForAuthor = "SELECT * FROM authors WHERE author_email=:email AND author_password = :pass LIMIT 1";
		$fetchAuthor = $connection->prepare($queryForAuthor);
		$fetchAuthor->bindParam(":email", $uname);
		$fetchAuthor->bindParam(":pass", $cryptedInputPass);
		$result = $fetchAuthor->execute();
		if($fetchAuthor->rowCount() > 0){
			foreach($fetchAuthor as $row){
				$idsess = stripslashes($row["author_id"]);
				$firstnameses = stripslashes($row["author_name"]);
				$email = stripslashes($row["author_email"]);
				$lastname = stripslashes($row["author_surname"]);
				
				session_unset();

				$updateDateLogedIN = $connection->prepare("UPDATE authors SET author_ipaddress = :ip WHERE author_id = :id LIMIT 1");
				$updateDateLogedIN->bindParam(":ip", $ip_address);
				$updateDateLogedIN->bindParam(":id", $idsess);
				$result = $updateDateLogedIN->execute();
				// provjera da li je unesena ip adresa
				if(!$result) {
					$_SESSION['errors'] = "Prijava nije uspjela. Pokušajte ponovo.";
					session_write_close();
					returnheader('../LogIn.php');
					exit();
				}

				$_SESSION["AUTHOR_USERID"] = (int)$idsess;
				$_SESSION["AUTHOR_USERFIRSTNAME"] = $firstnameses;
				$_SESSION["AUTHOR_LASTNAME"] = $lastname;
				$_SESSION["AUTHOR_EMAIL"] = cryptPass($email);


				setcookie("userloggedin", $email);
				setcookie("userloggedin", $email, time()+43200); // expires in 1 hour
				
				//success lets login to page
				returnheader("../index.php");
				$connection = NULL;
				exit();
			}
		} else {
		
			//tell there is no username etc
			$_SESSION['errors'] = "Neispravan email ili šifra.";
			session_write_close();
			returnheader('../LogIn.php');
			exit();
			
		}
		} else {
			$_SESSION['errors'] = "Neispravan šifra. Pokušajte ponovo.";
			session_write_close();
			returnheader('../LogIn.php');
			exit();

		}
	}
	} else {
		$uname = "";
	}
?>
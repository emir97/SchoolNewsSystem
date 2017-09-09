<?php
	include '../core/connect.php';
	include '../functions/secure.php';
	session_start();
	ob_start();
	checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
	
	if(isset($_GET[base64_url_encode("stranica")])) {
		$stranica = base64_url_decode($_GET[base64_url_encode("stranica")]);

		if($stranica == "download") {
			$action = base64_url_decode($_GET[base64_url_encode("action")]);
			if($action == "insert") {
				if($_POST['title'] == "") {
					header('location ../500.php');
					exit();
				}
				$downloadTitle =htmlspecialchars(strip_tags($_POST['title']));

				//dohvatanje varijabli iz forme
		          $postFile = $_FILES['content']['name']; // ime fajla
				  $postFileTmp = $_FILES['content']['tmp_name']; // privremeno ime fajla
		          $fileType = $_FILES["content"]["type"]; // ekstenzija fajla
		          $fileSize = $_FILES["content"]["size"]; // velicina fajla u bajtovima
		          $fileErrorMsg = $_FILES["content"]["error"]; // 0 ako ne postoji greska 1 ako postoji
		          $kaboom = explode(".", $postFile); // rastavljanje imena fajla i ekstenzije
		          $fileExt = end($kaboom); // dohvatanje zadnjeg polja niza
		          
		          if (!$postFileTmp) { // ako fajl nije izabran
		            header('location: ../500.php');
		            exit();
		          } else if($fileSize > 8388608) { // ako je velicina fajla veca od 5
		              header('location: ../500.php');
		              unlink($postFileTmp); // uklanjanje fajla sa servera
		              exit();
		          } else if ($fileErrorMsg == 1) { // ako postoji greska u uploadu
		              header('location: ../500.php');
		              exit();
		          }

		          //prebacivanje slike u folder
		          $moveResult = move_uploaded_file($postFileTmp, "../../files/$postFile");
		          // provjera da li je slika uploadovana na server
		          if ($moveResult != true) {
		              echo "ERROR: Fajl nije poslan na server.";
		              unlink($postFileTmp); // uklanjanje slike iz tmp foldera
		              exit();
		          }
		          

		          $date = date('o.m.d. G:i:s');

		          $connect = connectToDb();

		        $insertDownloadFileInDb = $connect->prepare("INSERT INTO downloads (download_title, download_content, author_id, download_date_upload) 
		        	VALUES (:title, :content, :author, :date_upload)");
		        $insertDownloadFileInDb->bindParam(":title", $downloadTitle);
		        $insertDownloadFileInDb->bindParam(":content", $postFile);
		        $insertDownloadFileInDb->bindParam(":author", $_SESSION['AUTHOR_USERID']);
		        $insertDownloadFileInDb->bindParam(":date_upload", $date);
		        $result = $insertDownloadFileInDb->execute();
		        // provjera da li je fajl upisan u bazu
		        if($result) {
			      	$connect = NULL;
			      	$urlForRedirect = "../ostalo.php?".base64_url_encode("stranica")."=".base64_url_encode("download");
			      	header("location: $urlForRedirect");
			      	exit();
			    } else {
			      	$connect = NULL;
			      	header('location: ../500.php');
			      	exit();
			    }

			} else if($action == "delete") {
				if(!isset($_GET[base64_url_encode("ID")])) {
					header('location: ../500.php');
					exit();
				}

				$ID = htmlspecialchars(strip_tags(base64_url_decode($_GET[base64_url_encode("ID")])));

				$connect = connectToDb();

				$fetchFile = $connect->prepare("SELECT download_content FROM downloads WHERE download_id = :id LIMIT 1");
				$fetchFile->bindParam(":id", $ID);
				$fetchFile->execute();
				if($fetchFile->rowCount() == 1) {
					foreach ($fetchFile as $key => $value) {
						$file = $value['download_content'];
					}
				} else {
					$connect = NULL;
					header('location: ../500.php');
					exit();
				}

				unlink("../../files/$file");
				$deleteFromDb = $connect->prepare("DELETE FROM downloads WHERE download_id = :id LIMIT 1");
				$deleteFromDb->bindParam(":id", $ID);
				$result = $deleteFromDb->execute();
				// provjera da li je fajl obrisan
		        if($result) {
			      	$connect = NULL;
			      	$urlForRedirect = "../ostalo.php?".base64_url_encode("stranica")."=".base64_url_encode("download");
			      	header("location: $urlForRedirect");
			      	exit();
			    } else {
			      	$connect = NULL;
			      	header('location: ../500.php');
			      	exit();
			    }
			}
		} else if($stranica == "kutakzaroditelje") {
			if($_POST['title'] == "" || $_POST['content'] == "") {
				header('location: ../500.php');
				exit();
			}

			$parentsCornerTitle = htmlspecialchars(strip_tags($_POST['title']));
			$parentsCornerContent = $_POST['content'];
			$date = date('o.m.d. G:i:s');
			$linkTitle = "kutakzaroditelje";

			$connect = connectToDb();

			$checkIfLinkExist = $connect->query("SELECT * FROM podaci_o_skoli WHERE o_skoli_link_title = 'kutakzaroditelje' LIMIT 1");
			if($checkIfLinkExist->rowCount() == 1) {
				$updateParentsCorner = $connect->prepare("UPDATE podaci_o_skoli SET o_skoli_title = :title,
																					o_skoli_content = :content,
																					o_skoli_edit_time = :time WHERE o_skoli_link_title = 'kutakzaroditelje'");
				$updateParentsCorner->bindParam(":title", $parentsCornerTitle);
				$updateParentsCorner->bindParam(":content", $parentsCornerContent);
				$updateParentsCorner->bindParam(":time", $date);
				$result = $updateParentsCorner->execute();
				// provjera da li je izmijenjen podatak
		        if($result) {
			      	$connect = NULL;
			      	$urlForRedirect = "../ostalo.php?".base64_url_encode("stranica")."=".base64_url_encode("kutakzaroditelje");
			      	header("location: $urlForRedirect");
			      	exit();
			    } else {
			      	$connect = NULL;
			      	header('location: ../500.php');
			      	exit();
			    }
			} else {
				$insertParentsCorner = $connect->prepare("INSERT INTO podaci_o_skoli (o_skoli_title, o_skoli_content, o_skoli_date_upload, author_id, o_skoli_link_title) 
																				VALUES (:title, :content, :time, :author, :link_title)");
				$insertParentsCorner->bindParam(":title", $parentsCornerTitle);
				$insertParentsCorner->bindParam(":content", $parentsCornerContent);
				$insertParentsCorner->bindParam(":time", $date);
				$insertParentsCorner->bindParam(":author", $_SESSION['AUTHOR_USERID']);
				$insertParentsCorner->bindParam(":link_title", $linkTitle);
				$result = $insertParentsCorner->execute();
				// provjera da li je unesen podatak
		        if($result) {
			      	$connect = NULL;
			      	$urlForRedirect = "../ostalo.php?".base64_url_encode("stranica")."=".base64_url_encode("kutakzaroditelje");
			      	header("location: $urlForRedirect");
			      	exit();
			    } else {
			      	$connect = NULL;
			      	header('location: ../500.php');
			      	exit();
			    }
			}
		} else if($stranica == "sekcije") {
			$action = base64_url_decode($_GET[base64_url_encode("action")]);
			if($action == "insert") {
				if($_POST['postTitle'] == "" || $_POST['postContent'] == "") {
					header('location: ../500.php');
					exit();
				}

				$title = strip_tags(htmlspecialchars($_POST['postTitle']));
				$content = $_POST['postContent'];
				$date = date('o.m.d G:i:s');

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

		          //prebacivanje slike u folder
		          $moveResult = move_uploaded_file($postImageTmp, "../../images/sekcije/resized_$postImage");
		          // provjera da li je slika uploadovana na server
		          if ($moveResult != true) {
		              echo "ERROR: Fajl nije poslan na server.";
		              unlink($postImageTmp); // uklanjanje slike iz tmp foldera
		              exit();
		          }
		          
		          // ---------- Include Universal Image Resizing Function --------
		          include_once("ak_php_img_lib_1.0.php");
		          $target_file = "../../images/sekcije/resized_$postImage";
		          $resized_file = "../../images/sekcije/$postImage";
		          $wmax = 750;
		          $hmax = 390;
		          ak_img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
		          //brisanje prave slike
		          unlink($target_file);

		          // ubacivanje u bazu

		          $connect = connectToDb();

		         $insertInDb = $connect->prepare("INSERT INTO sekcije (sekcija_title, sekcija_content, author_id, sekcija_date_upload, sekcija_image)
		         												VALUES (:title, :content, :author, :date_upload, :image)");
		         $insertInDb->bindParam(":title", $title);
		         $insertInDb->bindParam(":content", $content);
		         $insertInDb->bindParam(":author", $_SESSION['AUTHOR_USERID']);
		         $insertInDb->bindParam(":date_upload", $date);
		         $insertInDb->bindParam(":image", $postImage);
		         $result = $insertInDb->execute();
		         $connect = NULL;
		         // provjera da li je unesen podatak
		        if($result) {
			      	$connect = NULL;
			      	$urlForRedirect = "../ostalo.php?".base64_url_encode("stranica")."=".base64_url_encode("sekcija");
			      	header("location: $urlForRedirect");
			      	exit();
			    } else {
			      	$connect = NULL;
			      	header('location: ../500.php');
			      	exit();
			    }
			} else if($action == "edit") {
				if($_POST['postTitle'] == "" || $_POST['postContent'] == "") {
					header('location: ../500.php');
					exit();
				}

				$title = strip_tags(htmlspecialchars($_POST['postTitle']));
				$content = $_POST['postContent'];
				$date = date('o.m.d G:i:s');

				$ID = base64_url_decode($_GET[base64_url_encode("ID")]);

	 			  //dohvatanje varijabli iz forme
		          $postImage = $_FILES['postImage']['name']; // ime slike
				  $postImageTmp = $_FILES['postImage']['tmp_name']; // privremeno ime slike
		          $fileType = $_FILES["postImage"]["type"]; // ekstenzija slike
		          $fileSize = $_FILES["postImage"]["size"]; // velicina slike u bajtovima
		          $fileErrorMsg = $_FILES["postImage"]["error"]; // 0 ako ne postoji greska 1 ako postoji
		          $kaboom = explode(".", $postImage); // rastavljanje imena slike i ekstenzije
		          $fileExt = end($kaboom); // dohvatanje zadnjeg polja niza

		          $connect = connectToDb();

		           if(!is_uploaded_file($postImageTmp)){
                        $fetchImage = $connect->prepare("SELECT * FROM sekcije WHERE sekcija_id = :postID");
                        $fetchImage->bindParam(":postID", $ID);
                        $fetchImage->execute();
                        if($fetchImage->rowCount() > 0){
                          foreach($fetchImage as $aa){
                            $postImage = $aa['sekcija_image'];
                          }
                        }
                    } else {
                    	//prebacivanje slike u folder
                        $moveResult = move_uploaded_file($postImageTmp, "../../images/sekcije/resized_$postImage");
                        // provjera da li je slika uploadovana na server
                        if ($moveResult != true) {
                            echo "ERROR: Fajl nije poslan na server.";
                            unlink($postImageTmp); // uklanjanje slike iz tmp foldera
                            exit();
                        }
                        
                        // ---------- Include Universal Image Resizing Function --------
                        include_once("ak_php_img_lib_1.0.php");
                        $target_file = "../../images/sekcije/resized_$postImage";
                        $resized_file = "../../images/sekcije/$postImage";
                        $wmax = 750;
                        $hmax = 390;
                        ak_img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
                        //brisanje prave slike
                        unlink($target_file);
                    }

                    $updateSekcija = $connect->prepare("UPDATE sekcije SET sekcija_title = :title,
                    													   sekcija_content = :content,
                    													   sekcija_date_edit = :edit_date,
                    													   sekcija_image = :image WHERE sekcija_id = :id");
                    $updateSekcija->bindParam(":title", $title);
                    $updateSekcija->bindParam(":content", $content);
                    $updateSekcija->bindParam(":edit_date", $date);
                    $updateSekcija->bindParam(":image", $postImage);
                    $updateSekcija->bindParam(":id", $ID);
                    $result = $updateSekcija->execute();
                     // provjera da li je unesen podatak
			        if($result) {
				      	$connect = NULL;
				      	$urlForRedirect = "../ostalo.php?".base64_url_encode("stranica")."=".base64_url_encode("sekcija");
				      	header("location: $urlForRedirect");
				      	exit();
				    } else {
				      	$connect = NULL;
				      	header('location: ../500.php');
				      	exit();
				    }
			} else if($action == "delete") {
				$ID = base64_url_decode($_GET[base64_url_encode("ID")]);

				$connect = connectToDb();

				$deleteSekcija = $connect->prepare("DELETE FROM sekcije WHERE sekcija_id = :id");
				$deleteSekcija->bindParam(":id", $ID);
				$result = $deleteSekcija->execute();
				 // provjera da li je unesen podatak
		        if($result) {
			      	$connect = NULL;
			      	$urlForRedirect = "../ostalo.php?".base64_url_encode("stranica")."=".base64_url_encode("sekcija");
			      	header("location: $urlForRedirect");
			      	exit();
			    } else {
			      	$connect = NULL;
			      	header('location: ../500.php');
			      	exit();
			    }
			} else {
				header('location: ../404.php');
				exit();
			}
		} else {
			header('location: ../404.php');
			exit();
		}
	} else {
		header('location: ../404.php');
		exit();
	}

?>
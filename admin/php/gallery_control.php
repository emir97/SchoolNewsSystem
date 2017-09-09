<?php 
include('../core/connect.php'); 
include('../functions/secure.php');
session_start();
ob_start();
checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);

if(isset($_GET[base64_url_encode("actionForAlbum")])) {
	$actionForAlbum = base64_url_decode($_GET[base64_url_encode("actionForAlbum")]);

	if($actionForAlbum == "deleteAlbum") {

		$ID = base64_url_decode($_GET[base64_url_encode("albumID")]);
		$ID = preg_replace("/[^0-9]/", "", $ID);

		$connect = connectToDb();

		$deleteAlbum = $connect->prepare("DELETE FROM albums WHERE album_id = :id LIMIT 1");
		$deleteAlbum->bindParam(":id", $ID);
		$resultOfDelete = $deleteAlbum->execute();
		
		if($resultOfDelete) {
			$deletePhotoFromAlbum = $connect->prepare("DELETE FROM gallery_images WHERE album_id = :id");
			$deletePhotoFromAlbum->bindParam(":id", $ID);
			$resultOfDeletePhotos = $deletePhotoFromAlbum->execute();
			$connect = NULL;
			if($resultOfDeletePhotos) {
				header('location: ../gallery.php');
				exit();
			} else {
				header('location: ../500.php');
				exit();
			}
		} else {
			header('location: ../500.php');
			exit();
		}
	} else if($actionForAlbum == "deletePhotoFromAlbum") {

		$ID = base64_url_decode($_GET[base64_url_encode("ID")]);
		$ID = preg_replace("/[^0-9]/", "", $ID);

		$connect = connectToDb();

		$deleteAlbum = $connect->prepare("DELETE FROM gallery_images WHERE photo_id = :id LIMIT 1");
		$deleteAlbum->bindParam(":id", $ID);
		$resultOfDelete = $deleteAlbum->execute();
		$connect = NULL;
		if($resultOfDelete) {
			$albumID = base64_url_decode($_GET[base64_url_encode("albumID")]);
			$albumID = preg_replace("/[^0-9]/", "", $albumID);
			$url = "edit_gallery.php?".base64_url_encode("albumID")."=".base64_url_encode($albumID);
			header("location: ../$url");
			exit();
		} else {
			header('location: ../500.php');
			exit();
		}
	} else if($actionForAlbum = "uploadPhotoToAlbum") {
		if(isset($_POST)) {
			$date = date('o-m-d G:i:s');

			if(!isset($_GET[base64_url_encode("albumID")])) {
				header('location: ../500.php');
				exit();
			}

			$albumID = base64_url_decode($_GET[base64_url_encode("albumID")]);
			$albumID = preg_replace("/[^0-9]/", "", $albumID);

			$connect = connectToDb();

			foreach($_FILES['gallery_photo']['name'] as $i => $name) {

				$name = $_FILES['gallery_photo']['name'][$i];
				$size = $_FILES['gallery_photo']['size'][$i];
				$type = $_FILES['gallery_photo']['type'][$i];
				$tmp = $_FILES['gallery_photo']['tmp_name'][$i];

				$selectPictureName = $connect->query("SELECT photo_name FROM gallery_images ORDER BY photo_id DESC LIMIT 1");
				if($selectPictureName->rowCount() > 0) {
					foreach ($selectPictureName as $key => $value) {
						$temporary = $value['photo_name'];
						break;
					}
					$temporary = $temporary + 1;
				} else {
					$temporary = "0";
				}


				$explode = explode('.', $name);

				$ext = end($explode);

				$path = '../../images/gallery/';
				$path = $path . $temporary .'.'. $ext;

				$photoName = $temporary . ".".$ext;
				$contentPhoto = $photoName;
				
				$errors = array();

				if(empty($_FILES['gallery_photo']['tmp_name'][$i])) {
					$errors[] = 'Please choose at least 1 file to be uploaded.';
				}else {

					$allowed = array('jpg','jpeg','gif','bmp','png');

					$max_size = 10000000; // 10 MB

					if(in_array($ext, $allowed) === false) {
						$errors[] = 'The file <b>'.$name.'</b> extension is not allowed.';
					}

					if($size > $max_size) {
						$errors[] = 'The file <b>'.$name.'</b> size is too hight.';
					}

				}

				if(empty($errors)) {
					$resultUpload = move_uploaded_file($tmp, $path);
					if(!$resultUpload) {
						echo 'Something went wrong while uploading the file <b>'.$name.'</b>';
						exit();
					}
					$insertPhoto = $connect->prepare("INSERT INTO gallery_images (album_id, photo_name, photo_date_post, author_id)
																			VALUES(:album_id, :photo_name, :date_post_photo, :author)");
					$insertPhoto->bindParam(":album_id", $albumID);
					$insertPhoto->bindParam(":photo_name", $photoName);
					$insertPhoto->bindParam(":date_post_photo", $date);
					$insertPhoto->bindParam(":author", $_SESSION['AUTHOR_USERID']);
					$resultOFInsertPhoto = $insertPhoto->execute();

					if(!$resultOFInsertPhoto) {
						header('location: ../500.php');
						exit();
					}

				}else {
					foreach($errors as $error) {
						echo '<p>'.$error.'<p>';
						exit();
					}
				}

			}
			$url = "edit_gallery.php?".base64_url_encode("albumID")."=".base64_url_encode($albumID);
			$connect = NULL;
			header("location: ../$url");
			exit();

		}
	}
}

if($_POST) {

	if($_POST['title'] == "" || $_POST['content'] == "") {
		header('location: ../500.php');
		exit();
	} 

	$date = date('o-m-d G:i:s');

	$albumTitle = strip_tags($_POST['title']);
	$albumTitle = preg_replace("/&#?[A-Za-z0-9 ščćžđ]+;/i", "", $albumTitle);

	$albumContet = strip_tags($_POST['content']);
	$albumContet = preg_replace("/&#?[A-Za-z0-9 ščćžđ]+;/i", "", $albumContet);

	$connect = connectToDb();

	$selectContentPictureName = $connect->query("SELECT photo_name FROM gallery_images ORDER BY photo_id DESC LIMIT 1");
	if($selectContentPictureName->rowCount() > 0) {
		foreach ($selectContentPictureName as $key => $value) {
			$contentPhoto = $value['photo_name'];
			break;
		}
		$contentPhoto = $contentPhoto + 1;
	} else {
		$contentPhoto = "0";
	}


	// ubacivanje albuma u bazu
	$insertAlbum = $connect->prepare("INSERT INTO albums (album_title, album_date_post, author_id, album_content) 
													VALUES (:title, :date_post, :author, :content)");
	$insertAlbum->bindParam(":title", $albumTitle);
	$insertAlbum->bindParam(":date_post", $date);
	$insertAlbum->bindParam(":author", $_SESSION['AUTHOR_USERID']);
	$insertAlbum->bindParam(":content", $albumContet);
	$resultOfInsertAlbum = $insertAlbum->execute();

	if(!$resultOfInsertAlbum) {
		header('location: ../500.php');
		exit();
	}

	//dohvatanje album ID
	$albumID = $connect->lastInsertId();
	// ubacivanje slika u bazu
	foreach($_FILES['gallery_photo']['name'] as $i => $name) {

	$name = $_FILES['gallery_photo']['name'][$i];
	$size = $_FILES['gallery_photo']['size'][$i];
	$type = $_FILES['gallery_photo']['type'][$i];
	$tmp = $_FILES['gallery_photo']['tmp_name'][$i];

	$selectPictureName = $connect->query("SELECT photo_name FROM gallery_images ORDER BY photo_id DESC LIMIT 1");
	if($selectPictureName->rowCount() > 0) {
		foreach ($selectPictureName as $key => $value) {
			$temporary = $value['photo_name'];
			break;
		}
		$temporary = $temporary + 1;
	} else {
		$temporary = "0";
	}


	$explode = explode('.', $name);

	$ext = end($explode);

	$path = '../../images/gallery/';
	$path = $path . $temporary .'.'. $ext;

	$photoName = $temporary . ".".$ext;
	$contentPhoto = $photoName;
	
	$errors = array();

	if(empty($_FILES['gallery_photo']['tmp_name'][$i])) {
		$errors[] = 'Please choose at least 1 file to be uploaded.';
	}else {

		$allowed = array('jpg','jpeg','gif','bmp','png');

		$max_size = 10000000; // 10 MB

		if(in_array($ext, $allowed) === false) {
			$errors[] = 'The file <b>'.$name.'</b> extension is not allowed.';
		}

		if($size > $max_size) {
			$errors[] = 'The file <b>'.$name.'</b> size is too hight.';
		}

	}

	if(empty($errors)) {
		$resultUpload = move_uploaded_file($tmp, $path);
		if(!$resultUpload) {
			echo 'Something went wrong while uploading the file <b>'.$name.'</b>';
			exit();
		}
		$insertPhoto = $connect->prepare("INSERT INTO gallery_images (album_id, photo_name, photo_date_post, author_id)
																VALUES(:album_id, :photo_name, :date_post_photo, :author)");
		$insertPhoto->bindParam(":album_id", $albumID);
		$insertPhoto->bindParam(":photo_name", $photoName);
		$insertPhoto->bindParam(":date_post_photo", $date);
		$insertPhoto->bindParam(":author", $_SESSION['AUTHOR_USERID']);
		$resultOFInsertPhoto = $insertPhoto->execute();

		if(!$resultOFInsertPhoto) {
			header('location: ../500.php');
			exit();
		}

	}else {
		foreach($errors as $error) {
			echo '<p>'.$error.'<p>';
			exit();
		}
	}

}
$updateAlbum = $connect->prepare("UPDATE albums SET content_photo = :photo WHERE album_id = :id LIMIT 1");
$updateAlbum->bindParam(":photo", $contentPhoto);
$updateAlbum->bindParam(":id", $albumID);
$resultOfUpdateAlbum = $updateAlbum->execute();
$connect = NULL;
if($resultOfInsertAlbum) {
	header('location: ../gallery.php');
	exit();
} else {
	header('location: ../500.php');
	exit();
}
}
?>
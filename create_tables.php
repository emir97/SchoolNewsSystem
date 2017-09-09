<?php
/*** mysql hostname ***/
$hostname = 'localhost';

/*** mysql username ***/
$username = 'emir';

/*** mysql password ***/
$password = 'bGvn***10_8SEG';

/*** database name ***/
$dbname = 'etsweb';

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    /*** echo a message saying we have connected ***/
    echo 'Connected to database<br />';

    /*** set the PDO error mode to exception ***/
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
   $dbh->beginTransaction();
   
   $tableGalleryImages = "CREATE TABLE IF NOT EXISTS gallery_images ( 
        photo_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
        album_id BIGINT NOT NULL,
        photo_date_post DATETIME NOT NULL,
        author_id BIGINT NOT NULL, 
        photo_name TEXT NOT NULL COLLATE utf8_general_ci
    )";
    $dbh->exec($tableGalleryImages);
   
   $tableAlbum = "CREATE TABLE IF NOT EXISTS albums ( 
        album_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
        album_title TEXT NOT NULL COLLATE utf8_general_ci,
        album_date_post DATETIME NOT NULL,
        album_date_edit DATETIME NOT NULL, 
        author_id BIGINT NOT NULL, 
        album_content TEXT NOT NULL COLLATE utf8_general_ci,
        content_photo TEXT NOT NULL
    )";
    $dbh->exec($tableAlbum);
    
     $tableAnketeOdgovori = "CREATE TABLE IF NOT EXISTS ankete_odgovori ( 
        anketa_odg_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
        anketa_pitanje_id BIGINT NOT NULL,
        anketa_odg_content TEXT NOT NULL,
        anketa_odg_num_votes BIGINT NOT NULL
        
    )";
    $dbh->exec($tableAnketeOdgovori);
    
    $tableAnketePitanja = "CREATE TABLE IF NOT EXISTS ankete_pitanja ( 
        anketa_pitanje_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
        anketa_pitanje_naslov VARCHAR(60) NOT NULL,
        anketa_ip_address TEXT NOT NULL,
        anketa_pitanje_content TEXT NOT NULL, 
        is_active TINYINT(2) NOT NULL
        
    )";
    $dbh->exec($tableAnketePitanja);
    
    // Creating table authors
    $tableAuthors = "CREATE TABLE IF NOT EXISTS authors ( 
        author_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        author_email VARCHAR(50) NOT NULL,
        author_password TEXT NOT NULL,
        author_name VARCHAR(20) NOT NULL COLLATE utf8_general_ci,
        author_surname VARCHAR(20) NOT NULL COLLATE  utf8_general_ci,
        author_image TEXT NOT NULL, 
        author_sex TINYINT(2) NOT NULL, 
        author_cv TEXT COLLATE  utf8_general_ci,
        author_ipaddress VARCHAR(20), 
        author_country VARCHAR(40),
        author_city VARCHAR(40),
        author_region VARCHAR(100),
        author_last_updated DATETIME, 
        
        UNIQUE (author_email)
         
    )";
    $dbh->exec($tableAuthors);
    
    // creating table author register
    $tableAuthorRegister = "CREATE TABLE IF NOT EXISTS authors_register ( 
        author_register_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        author_register_email VARCHAR(50) NOT NULL,
        author_register_password TEXT NOT NULL,
        author_register_name VARCHAR(20) NOT NULL COLLATE utf8_general_ci,
        author_register_surname VARCHAR(20) NOT NULL COLLATE  utf8_general_ci,
        author_register_sex TINYINT(2) NOT NULL, 
        author_register_cv TEXT COLLATE  utf8_general_ci,
        author_register_ipaddress VARCHAR(20), 
        author_register_country TEXT COLLATE  utf8_general_ci,
        author_register_city TEXT COLLATE  utf8_general_ci,
        author_register_region TEXT COLLATE  utf8_general_ci,
        author_register_code TEXT,
        author_register_email_active INT(11),
        author_register_admin_active INT(11)
    )";
    $dbh->exec($tableAuthorRegister);
    
    // Creating table author permission
    $tableAuthorPermission = "CREATE TABLE IF NOT EXISTS author_permission ( 
        permission_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
        author_id BIGINT NOT NULL,
        is_admin TINYINT(2) NOT NULL
    )";
    $dbh->exec($tableAuthorPermission);
    
    // Creating table comments
    $tableComments = "CREATE TABLE IF NOT EXISTS comments ( 
        comment_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
        user_firstname VARCHAR(20) NOT NULL COLLATE utf8_general_ci, 
        user_lastname VARCHAR(20) NOT NULL COLLATE utf8_general_ci, 
        user_email TEXT NOT NULL,
        user_comment TEXT NOT NULL NOT NULL COLLATE utf8_general_ci, 
        user_photo TEXT NOT NULL,
        user_ipaddress TEXT NOT NULL,
        user_date_post DATE NOT NULL, 
        user_time_post TIME NOT NULL,
        news_id BIGINT NOT NULL,
        slider_id BIGINT NOT NULL,
        event_id BIGINT NOT NULL,
        user_location_country TEXT NOT NULL COLLATE utf8_general_ci, 
        user_isp_provider TEXT NOT NULL COLLATE utf8_general_ci, 
        user_location_region TEXT NOT NULL COLLATE utf8_general_ci, 
        user_location_city TEXT NOT NULL COLLATE utf8_general_ci
    )";
    $dbh->exec($tableComments);
    
    // Creating table downloads
    $tableDownloads = "CREATE TABLE IF NOT EXISTS downloads ( 
        download_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
        download_title TEXT NOT NULL COLLATE utf8_general_ci, 
        download_content TEXT NOT NULL COLLATE utf8_general_ci, 
        download_date_upload DATETIME, 
        author_id BIGINT
    )";
    $dbh->exec($tableDownloads);
    
    // Creating table events
    $tableEvents = "CREATE TABLE IF NOT EXISTS events ( 
        event_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
        event_title VARCHAR(100) NOT NULL COLLATE utf8_general_ci, 
        event_content LONGTEXT NOT NULL COLLATE utf8_general_ci, 
        event_place TEXT NOT NULL COLLATE utf8_general_ci, 
        event_image TEXT NOT NULL COLLATE utf8_general_ci,
        event_date_post DATE NOT NULL,
        event_time_post TIME NOT NULL,
        author_id BIGINT NOT NULL,
        event_start_date DATETIME, 
        event_end_date DATETIME,
        event_last_updated DATETIME
    )";
    $dbh->exec($tableEvents);
    
    // Creating table keywords
    $tableKeys = "CREATE TABLE IF NOT EXISTS keywords ( 
        keywords_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        keywords_content TEXT COLLATE  utf8_general_ci
    )";
    $dbh->exec($tableKeys);
    
    // Creating table news
    $tableNews = "CREATE TABLE IF NOT EXISTS news ( 
        news_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
        news_title VARCHAR(100) NOT NULL COLLATE utf8_general_ci,
        news_content LONGTEXT NOT NULL COLLATE utf8_general_ci,
        news_date DATE NOT NULL,
        author_id BIGINT NOT NULL,
        news_image TEXT NOT NULL, 
        keywords_id BIGINT NOT NULL, 
        news_num_views BIGINT,
        izdvojeno TINYINT(1), 
        num_comment BIGINT,
        news_last_updated DATETIME
    )";
    $dbh->exec($tableNews);
    
    // Creating table notify
    $tableNotify = "CREATE TABLE IF NOT EXISTS notify ( 
        notify_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
        notify_content LONGTEXT NOT NULL COLLATE utf8_general_ci,
        notify_date DATE NOT NULL,
        author_id BIGINT NOT NULL,
        notify_last_updated DATETIME
    )";
    $dbh->exec($tableNotify);
    
    // Creating table Podaci o skoli
    $tablePodaciOSkoli = "CREATE TABLE IF NOT EXISTS podaci_o_skoli ( 
        o_skoli_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        o_skoli_title TEXT NOT NULL COLLATE utf8_general_ci,
        o_skoli_content LONGTEXT NOT NULL COLLATE utf8_general_ci,
        o_skoli_date_upload DATETIME NOT NULL,
        o_skoli_edit_time DATETIME,
        author_id BIGINT NOT NULL,
        o_skoli_link_title VARCHAR(20) NOT NULL UNIQUE
    )";
    $dbh->exec($tablePodaciOSkoli);
    
    // Creating table Podaci o sekcije
    $tableSekcije = "CREATE TABLE IF NOT EXISTS sekcije ( 
        sekcija_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
        sekcija_title TEXT NOT NULL COLLATE utf8_general_ci,
        sekcija_content LONGTEXT NOT NULL COLLATE utf8_general_ci,
        sekcija_date_upload DATETIME NOT NULL,
        sekcija_date_edit DATETIME,
        author_id BIGINT NOT NULL,
        sekcija_image TEXT NOT NULL
    )";
    $dbh->exec($tableSekcije);
    
    // Creating table slider
    $tableSlider = "CREATE TABLE IF NOT EXISTS slider ( 
        id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        red_br TINYINT(4) NOT NULL, 
        title VARCHAR(100) NOT NULL COLLATE utf8_general_ci,
        content LONGTEXT NOT NULL COLLATE utf8_general_ci,
        date DATE NOT NULL,
        author_id BIGINT NOT NULL,
        image TEXT NOT NULL, 
        keywords_id BIGINT NOT NULL,
        slider_num_comment BIGINT, 
        slider_last_updated DATETIME,
        image_url_slider TEXT
    )";
    $dbh->exec($tableSlider); 
    
    // Creating table upis
    $tableUpis = "CREATE TABLE IF NOT EXISTS upis (                                        
        upis_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
        upis_title TEXT NOT NULL COLLATE utf8_general_ci,
        upis_content LONGTEXT NOT NULL COLLATE utf8_general_ci,
        upis_date_upload DATE NOT NULL,
        upis_time_upload TIME NOT NULL, 
        upis_edit_time DATETIME,
        upis_data TEXT, 
        author_id BIGINT NOT NULL,
        link_title TEXT NOT NULL
    )";
    $dbh->exec($tableUpis);
    
    // Creating table zanimanja
    $tableZanimanja = "CREATE TABLE IF NOT EXISTS zanimanja (                                        
        zanimanje_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
        zanimanje_title TEXT NOT NULL COLLATE utf8_general_ci,
        zanimanje_content LONGTEXT NOT NULL COLLATE utf8_general_ci,
        zanimanje_date_upload DATETIME NOT NULL,
        zanimanje_date_edit DATETIME,
        zanimanje_image TEXT, 
        author_id BIGINT NOT NULL,
        zanimanje_duration INT(11) NOT NULL
    )";
    $dbh->exec($tableZanimanja);
    
    /*** commit the transaction ***/
    $dbh->commit();

    /*** echo a message to say the database was created ***/
    echo 'Data entered successfully<br />';
}
catch(PDOException $e)
    {

    /*** echo the sql statement and error message ***/
    echo   '<br />' . $e->getMessage();
    }
?>
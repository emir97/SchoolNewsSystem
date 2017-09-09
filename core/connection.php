<?php 
	function connectOnDb(){
		try{
			$opt = array(
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
			);
			$conncetOnDb = new PDO("mysql:host=localhost;dbname=ets;charset=utf8;", "root", "");
			return $conncetOnDb;
			
		} catch(PDOException $message){
        echo $message->getMessage();
        exit();
    }
	}
?>
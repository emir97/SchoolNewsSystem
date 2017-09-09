<?php
	require_once 'core/connection.php';
	$load = htmlentities(strip_tags($_POST['load']));
	
	$connection = connectOnDb();
	$query = $connection->query("SELECT * FROM events ORDER BY event_date_post DESC LIMIT $load, 4");
	if($query->rowCount() > 0) {
	while($row = $query->fetch()){
        
        //ID dogadjaja
		$eventID = $row['event_id'];
        
        //naslov dogadjaja
        $eventTitle = $row['event_title'];
        
        //sadrzaj dogadjaja
        $eventContent = htmlspecialchars(strip_tags($row['event_content']));
        
        //datum odrzavanja dogadjaja
        $eventStartDate = new DateTime($row['event_start_date']);
        $eventStartYear = $eventStartDate->format('o');
        $eventStartDay = $eventStartDate->format('d');
        $eventStartMonth = $eventStartDate->format('m');
        switch($eventStartMonth){
            case "01":	$eventStartMonth = "Januar"; break;
            case "02":	$eventStartMonth = "Februar"; break;
            case "03":	$eventStartMonth = "Mart"; break;
            case "04":	$eventStartMonth = "April"; break;
            case "05":	$eventStartMonth = "Maj"; break;
            case "06":	$eventStartMonth = "Juni"; break;
            case "07":	$eventStartMonth = "Juli"; break;
            case "08":	$eventStartMonth = "August"; break;
            case "09":	$eventStartMonth = "Septembar"; break;
            case "10":	$eventStartMonth = "Oktobar"; break;
            case "11":	$eventStartMonth = "Novembar"; break;
            case "12": 	$eventStartMonth = "Decembar"; break;
        }
        $eventStart = $eventStartMonth. " ".$eventStartDay .", ".$eventStartYear.". ";
        
        //slika dogadjaja
        $eventImage = $row['event_image'];
        
        //mjesto dogadjaja
        $eventPlace = $row['event_place'];
        
        //url za citanje pojedine vijesti
        $urlForEvents = "dogadjaji_detalji.php?ID=" . $eventID . "&" . "title=" . $eventTitle;

        
        

    echo '
       <div class="list-event-item" ng-repeat="event in events">
                            <div class="box-content-inner clearfix">
                                <div class="list-event-thumb">
                                    <a href="'.$urlForEvents.'">
                                        <img src="./images/events/'.$eventImage.'" alt="'.$eventImage.'" />
                                    </a>
                                </div>
                                <div class="list-event-header">
                                    <span class="event-place small-text"><i class="fa fa-globe"></i>'.$eventPlace.'</span>
                                    <span class="event-date small-text"><i class="fa fa-calendar-o"></i>'.$eventStart.'</span>
                                    <div class="view-details"><a href="'.$urlForEvents.'" class="lightBtn">Detaljnije</a></div>
                                </div>
                                <h5 class="event-title"><a href="'.$urlForEvents.'">'.$eventTitle.'</a></h5>
                                <p class="event-content">'.$eventContent.'</p>
                            </div> <!-- /.box-content-inner -->
                        </div> <!-- /.list-event-item -->
    ';
	
    }
    }
    $connection = NULL;
?>
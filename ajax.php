<?php
// Include Google calendar api handler class 
include_once 'classes/GoogleCalendarApi.php';

// Include Calendar Events handler Class
include_once 'classes/CalendarEvents.php';

// Include database configuration file 
require_once 'include/dbConfig.php'; 

$GoogleCalendarApi = new GoogleCalendarApi(); 
$CalendarEvents = new CalendarEvents($db);

$case = isset($_GET['action']) ? $_GET['action'] : $_REQUEST['action'] ;
switch($case) {
    case "listevent": {
        $data  = $CalendarEvents->GetCalendarEvent();
        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($data),
            "totaldisplayrecords" => count($data),
            "data" => $data
        );
        
        echo json_encode($dataset);
        break;
    }

    case "deleteevent": {
        $db_event_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
        // Get google calendar id from database
        $data  = $CalendarEvents->GetCalendarEvent((int)$db_event_id);
         // Get the access token 
         $event_id = $data[0]['google_calendar_event_id'];
         $access_token_sess = $_SESSION['google_access_token']; 
         if(!empty($access_token_sess)){ 
             $access_token = $access_token_sess; 
         }
        
        $result  = $GoogleCalendarApi->DeleteCalendarEvent($access_token,  'primary', $event_id);
        if($result) {
            $data  = $CalendarEvents->DeleteCalendarEvent((int)$db_event_id);
            $dataset = array(
                "status" => true,
                "msg"    => "Sucessfully Deleted Calendar Event!!"
            );
        } else {
            $dataset = array(
                "status" => false,
                 "msg"    => "Error while deleting !!"
            );
        }
            echo json_encode($dataset);
        
        break;
    }

    case 'logout' : {
        $access_token_sess = $_SESSION['google_access_token']; 
         if(!empty($access_token_sess)){ 
            unset($_SESSION['google_access_token']);
            unset($_SESSION['last_event_id']);
         }
         $dataset = array(
            "status" => true,
            "msg"    => "Sucessfully Disconnected Calendar !"
        );
        echo json_encode($dataset);
        break;
    }

    case "default": {
        break;
    }
}


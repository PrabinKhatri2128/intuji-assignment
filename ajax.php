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
        $db_event_id = 0;
        $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
        $length = isset($_REQUEST['length']) ? $_REQUEST['length'] : 10;
        $count = isset($_REQUEST['count']) ? $_REQUEST['count'] : '';
        $order =  isset($_REQUEST['order']) ? $_REQUEST['order'] : '';
        $search =  isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
        list($data, $totalRecord)  = $CalendarEvents->GetCalendarEvent($db_event_id, $start, $length, $order, $search,  $count);
        
        $recordsFiltered = $totalRecord;
        if(isset($search['value']) && !empty($search['value'])) {
            $recordsFiltered = count($data);
        }
        $dataset = array(
            "draw" => $_REQUEST['draw'],
            "recordsTotal" => (int) $totalRecord,
            "recordsFiltered" => (int) $recordsFiltered,
            "data" => $data
        );
        
        echo json_encode($dataset);
        exit(1);
        break;
    }

    case "deleteevent": {
        $db_event_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
        // Get google calendar id from database
        list($data, $totalRecord)  = $CalendarEvents->GetCalendarEvent((int)$db_event_id, $start = 0, $length = 1, $order = array(), $search = array(),  $count = 0);
        
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
        exit(1);  
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
        exit(1);
        break;
    }

    case "default": {
        break;
    }
}


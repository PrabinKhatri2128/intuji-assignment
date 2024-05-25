<?php
// Include Calendar Events handler Class
include_once 'classes/CalendarEvents.php';

// Include database configuration file 
require_once 'include/dbConfig.php'; 

$CalendarEvents = new CalendarEvents($db);
$case = isset($_GET['case']) ? $_GET['case'] : 'listevent' ;
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
    case "default": {
        break;
    }
}


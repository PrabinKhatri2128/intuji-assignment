<?php 
/** 
 * 
 * This Calendar Events handler class is a custom PHP library to handle the Google Calendar Event Data. 
 * 
 * @class        CalendarEvents
 * @author        Prabin Khatri 
 * @version        1.0 
 */ 
class CalendarEvents {      
     
    function __construct($db) { 
        //print_r($params);
        $this->db = $db;
        // if (count($params) > 0){ 
        //     $this->initialize($params);         
        // } 
    }
    
    function initialize($params = array()) { 
        if (count($params) > 0){ 
            foreach ($params as $key => $val){ 
                if (isset($this->$key)){ 
                    $this->$key = $val; 
                } 
            }         
        } 
    }

    public function GetCalendarEvent() {        
        $sqlQ = "SELECT `title`, `description`, `location`, `date`, `time_from`, `time_to` FROM events WHERE google_calendar_event_id IS NOT NULL order by `date` ASC"; 
        $stmt = $this->db->query($sqlQ);
        //$eventData = $stmt->fetch_all();
        while ($row = $stmt->fetch_assoc()) {
            $eventData[] = $row;
        }         
        return $eventData; 
    } 
}
?>
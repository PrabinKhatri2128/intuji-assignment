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

    public function GetCalendarEvent($db_event_id = null) {        
        $sqlQ = "SELECT `id`,`title`, `description`, `location`, `date`, `time_from`, `time_to`, `google_calendar_event_id` FROM events WHERE google_calendar_event_id IS NOT NULL";
        if(!empty($db_event_id)) { 
            $sqlQ .= " AND id=?";  
        }
        $sqlQ .= " order by `date` ASC";
        if(!empty($db_event_id)) {
            $stmt = $this->db->prepare($sqlQ);
            $stmt->bind_param("i", $db_event_id); 
            $stmt->execute();
            $stmt = $stmt->get_result(); 
        } else {
            $stmt = $this->db->query($sqlQ);   
        }
        $eventData = [];
        while ($row = $stmt->fetch_assoc()) {
            $eventData[] = $row;
        }
        return $eventData;        
    }

    public function DeleteCalendarEvent($db_event_id = null) {

        $sqlQ = "DELETE FROM events WHERE 1=1 AND id=?"; 
        $stmt = $this->db->prepare($sqlQ);
        $stmt->bind_param("i", $db_event_id); 
        $stmt->execute();
        return $stmt;        
    } 
}
?>
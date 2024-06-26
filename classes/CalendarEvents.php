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

    public function GetCalendarEvent($db_event_id, $start = 0, $length = 0, $order = array(), $search = array(),  $count = 0) {
        $order = isset($order[0]) ? $order[0] : '';
	    $order_by = '';
        $order_dir = '';
        $column = '';
        if(isset($order)) {
            $order_dir = isset($order['dir']) ? $order['dir'] : "asc";
            $column = isset($order['column']) ? $order['column'] : '';
        }
        $search = isset($search['value']) ? $search['value'] : '';
	    switch ((string)$column){
		    case '0':
			    $order_by = 'title';
			    break;
		    case '1':
			    $order_by = 'description';
			    break;
		    case '2':
			    $order_by = 'location';
			    break;
		    case '3':
			    $order_by = 'date';
			    break;
		    default:
			    $order_by = 'date';
			    break;
	    }

        $sqlQ = "SELECT `id`,`title`, `description`, `location`, `date`, `time_from`, `time_to`, `google_calendar_event_id` FROM events WHERE google_calendar_event_id IS NOT NULL";
        if(!empty($db_event_id)) { 
            $sqlQ .= " AND id=?";  
        }
        if(isset($search)) {
            $sqlQ .= " AND title LIKE '%$search%'";
        }
        $sqlQ .= " ORDER BY ".$order_by." ".$order_dir;
        $sqlQ .= " LIMIT ".$start. ' , '.$length;
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

        //Fetching Data for Total Records
        $sqlQ1 = "SELECT `id`,`title`, `description`, `location`, `date`, `time_from`, `time_to`, `google_calendar_event_id` FROM events WHERE google_calendar_event_id IS NOT NULL";
        $stmt1 = $this->db->query($sqlQ1);   
        $recordsTotal = [];
        while ($row1 = $stmt1->fetch_assoc()) {
            $recordsTotal[] = $row1;
        }
        $recordsTotalcount = count($recordsTotal);
        return [$eventData, $recordsTotalcount] ;        
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
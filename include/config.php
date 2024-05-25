<?php 
 
// Database configuration    
define('DB_HOST', 'localhost'); 
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', ''); 
define('DB_NAME', 'google_calendar'); 
 

define('REDIRECT_URI', 'http://localhost/googlecalendar/google_calendar_event_sync.php'); 
 
// Start session 
if(!session_id()) session_start(); 

?>
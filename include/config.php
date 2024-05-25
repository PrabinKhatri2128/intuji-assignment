<?php 
 
// Database configuration    
define('DB_HOST', 'localhost'); 
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', ''); 
define('DB_NAME', 'google_calendar'); 
 

define('GOOGLE_OAUTH_SCOPE', 'https://www.googleapis.com/auth/calendar'); 
define('REDIRECT_URI', 'http://localhost/googlecalendar/google_calendar_event_sync.php'); 
 
// Start session 
if(!session_id()) session_start(); 
 
$googleOauthURL = 'https://accounts.google.com/o/oauth2/auth?scope=' . urlencode(GOOGLE_OAUTH_SCOPE) . '&redirect_uri=' . REDIRECT_URI . '&response_type=code&client_id=' . GOOGLE_CLIENT_ID . '&access_type=online'; 
 
?>
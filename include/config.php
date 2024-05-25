<?php 
 
// Database configuration    
define('DB_HOST', 'localhost'); 
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', ''); 
define('DB_NAME', 'google_calendar'); 
 
// Google API configuration 

// Start session 
if(!session_id()) session_start(); 
 
// Google OAuth URL
?>
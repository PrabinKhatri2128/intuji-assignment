<?php 
 
// Database configuration    
define('DB_HOST', ''); 
define('DB_USERNAME', ''); 
define('DB_PASSWORD', ''); 
define('DB_NAME', ''); 
 
// Google API configuration 
define('GOOGLE_CLIENT_ID', ''); 
define('GOOGLE_CLIENT_SECRET', ''); 
define('GOOGLE_OAUTH_SCOPE', ''); 
define('REDIRECT_URI', '');

// Start session 
if(!session_id()) session_start(); 
 
// Google OAuth URL
$googleOauthURL = '';
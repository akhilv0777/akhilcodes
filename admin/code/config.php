<?php
// ---------------------------------------------
// Development Settings: Error Reporting
// ---------------------------------------------
// Enable full error reporting for development.
// Be sure to disable this in production!
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ---------------------------------------------
// Database Configuration
// ---------------------------------------------
define('DB_HOST', 'localhost');                // Database host (usually localhost)
define('DB_NAME', 'akhilcodes');               // Name of your database
define('DB_USER', 'root');                     // Database username
define('DB_PASS', '');                         // Database password
define('DB_CHARSET', 'utf8mb4');               // Character set for database communication

// ---------------------------------------------
// Path Configuration
// ---------------------------------------------
//define('SUBDIR', '/crm');                      // Base subdirectory for your project (no trailing slash)

// Detect the protocol (HTTP or HTTPS)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";

// Get the host name (e.g., mbb.test)
$host = $_SERVER['HTTP_HOST'];

// Define the base URL of the theme/project (e.g., https://akhilv0777.github.io/akhilcodes/)
//define('THEMEURI', $protocol . $host . SUBDIR);

// Define the physical path to the base directory on the server (e.g., D:/laragon/www/mbb/crm)
//define('THEMEDIR', __DIR__);

// Define the URL path to the images folder (e.g., https://akhilv0777.github.io/akhilcodes/assets/images)
//define('IMG', THEMEURI . '/assets/images');

// ---------------------------------------------
// Utility Functions
// ---------------------------------------------
/**
 * Debug-friendly var_dump wrapped in <pre> tags for readability.
 *
 * @param mixed $var The variable to dump.
 */
function ao_var_dump($var){
  echo '<pre>';
  var_dump($var);
  echo '</pre>';
}

// ---------------------------------------------
// Session Configuration
// ---------------------------------------------
// Optional: Custom session save path (uncomment if needed)
// ini_set('session.save_path', '/tmp');
// Start the PHP session
session_start();

// ---------------------------------------------
// Optional Debugging (Uncomment as needed)
// ---------------------------------------------
// echo THEMEURI;
// ao_var_dump(THEMEDIR);
// echo IMG;


// ---------------------------------------------
// include class db 
require 'class.db.php';
//----------------------------------------------

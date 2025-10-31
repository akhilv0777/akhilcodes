<?php
session_start();
if (isset($_SESSION['user_id'])) {
    setcookie($_SESSION['user_id'], '', time() - 3600, '/');
}
session_unset();
session_destroy();
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache"); 
header("Expires: 0"); 
header("Location: ../login.php");
exit;
?>

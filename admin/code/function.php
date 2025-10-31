<?php
// Helper function to send JSON response
function sendResponse($status, $data = null, $message = null)
{
  $response = ['status' => $status];
  if ($data !== null) $response['data'] = $data;
  if ($message !== null) $response['message'] = $message;
  echo json_encode($response);
  exit();
}
// function getUserData($conn)
// {
//   include 'db_connection.php';
//   $query = "SELECT * FROM user";
//   $result = $conn->query($query);
//   if ($result) {
//     $row = $result->fetch_assoc();
//     if ($row) {
//       return $row;
//     } else {
//       return false;
//     }
//   } else {
//     return false;
//   }
// }
// $user_data = getUserData($conn);
// $contact_details = unserialize(getUserData($conn)['contact_details']);

// function getAboutUs()
// {
//   include 'db_connection.php';
//   $query = "SELECT * FROM about_us";
//   $result = $conn->query($query);
//   if ($result) {
//     $row = $result->fetch_assoc();
//     if ($row) {
//       return $row;
//     } else {
//       return false;
//     }
//   } else {
//     return false;
//   }
// }
// $aboutData = getAboutUs($conn);
// $resumeDetails = unserialize($aboutData['resume_details']);

$users_table = 'users';
$checkTable = $db->query("SHOW TABLES LIKE '$users_table'");

if (is_array($checkTable) && count($checkTable) == 0) {
  $applicationSql = "
        CREATE TABLE `" . DB_NAME . "`.`$users_table` (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";
  $db->query($applicationSql);
  // Insert a default user after table creation
  $insertUserSql = "
      INSERT INTO `$users_table` (`username`, `email`, `password`, `created_at`, `updated_at`)
      VALUES ('akhilv0777','akhilv0777@gmail.com','$2y$10$7Sj.lMUXpvAKF4GrUfTWA.MyuJzI86e.3eSbdumyLlPBiUSrcmq8y', CURRENT_TIMESTAMP,CURRENT_TIMESTAMP);
    ";
  $db->query($insertUserSql);
}

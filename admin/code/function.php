<?php
function sendResponse($status, $data = null, $message = null)
{
  $response = ['status' => $status];
  if ($data !== null) $response['data'] = $data;
  if ($message !== null) $response['message'] = $message;
  echo json_encode($response);
  exit();
}

$footer_items = [];
$header_items = [];
function add_footer_script($src)
{
  global $footer_items;
  $footer_items[] = $src;
}

function footer()
{
  global $footer_items;
  foreach ($footer_items as $item) {
    if (strpos($item, '<script') === false) {
      echo "<script src='$item'></script>\n";
    } else {
      echo $item . "\n";
    }
  }
}
function add_header_css($href)
{
  global $header_items;
  $header_items[] = $href;
}

function headerCss()
{
  global $header_items;
  foreach ($header_items as $item) {
    if (strpos($item, '<link') === false) {
      echo "<link rel='stylesheet' href='$item'>\n";
    } else {
      echo $item . "\n";
    }
  }
}

$users_table = 'users';
$contacts_table = 'contacts';
$checkTable = $db->query("SHOW TABLES LIKE '$users_table'");

if (is_array($checkTable) && count($checkTable) == 0) {
  $usersSql = "
    CREATE TABLE `" . DB_NAME . "`.`$users_table` (
      id INT AUTO_INCREMENT PRIMARY KEY,
      username VARCHAR(100) NOT NULL,
      email VARCHAR(255) NOT NULL UNIQUE,
      profile_picture VARCHAR(255) DEFAULT NULL,
      password VARCHAR(255) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";
  $db->query($usersSql);
  // Insert a default user after table creation
  $insertUserSql = "
    INSERT INTO `$users_table` (`username`, `email`, `password`, `created_at`, `updated_at`)
    VALUES ('akhilv0777','akhilv0777@gmail.com','$2y$10$7Sj.lMUXpvAKF4GrUfTWA.MyuJzI86e.3eSbdumyLlPBiUSrcmq8y', CURRENT_TIMESTAMP,CURRENT_TIMESTAMP);
    ";
  $db->query($insertUserSql);
}
$checkContactsTable = $db->query("SHOW TABLES LIKE '$contacts_table'");
if (is_array($checkContactsTable) && count($checkContactsTable) == 0) {
  $contactsSql = "
        CREATE TABLE `" . DB_NAME . "`.`$contacts_table` (
          id INT AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(100) NOT NULL,
          email VARCHAR(100) NOT NULL,
          phone VARCHAR(20),
          subject VARCHAR(100) NOT NULL,
          message TEXT,
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";
  $db->query($contactsSql);
}

$currentPage = pathinfo($_SERVER['REQUEST_URI'], PATHINFO_FILENAME);
if ($currentPage == 'contact') {
  add_footer_script('assets/bundles/datatables/datatables.min.js');
  add_footer_script('assets/js/contact.js');
  add_header_css('assets/bundles/datatables/datatables.min.css');
}
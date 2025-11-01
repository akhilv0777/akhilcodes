<?php
require 'config.php';
require 'function.php';
header('Content-Type: application/json');

$user = $db->selectOne('users', ['id' => 1]);
if ($user) {
  unset($user['id']);
  unset($user['password']);
  echo json_encode($user);
} else {
  echo json_encode(['error' => 'User not found']);
}

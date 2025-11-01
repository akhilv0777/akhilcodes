<?php
require_once 'config.php';
require_once 'function.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($method) {
  case 'GET':
    // handleGetRequests();
    break;
  case 'POST':
    // handlePostRequests();
    break;
  case 'PUT':
    handlePutRequests();
    break;
  case 'DELETE':
    // handleDeleteRequests();
    break;
  default:
    sendResponse('error', null, 'Method not allowed');
}

/*
function handleGetRequests()
{
  global $pdo;
  $action = $_GET['action'] ?? '';
  switch ($action) {
    case 'profile':
      getUserProfile();
      break;
    default:
     sendResponse('error', null, 'Action not specified');
  }
}

function handlePostRequests()
{
  global $pdo;
  $action = $_GET['action'] ?? '';

  switch ($action) {
    case 'promote':
      promoteUser();
      break;
    default:
      // Default is to create new user
      createUser();
  }
}
  */

function handlePutRequests()
{
  updateUser();
}

function updateUser()
{
  global $db;
  $userId = $_SESSION['user_id'] ?? null;
  if (!$userId) {
    sendResponse('error', null, 'Please login first!');
  }
  parse_str(file_get_contents('php://input'), $data);

  try {
    $params = [];
    $updateFields = [];

    $user = $db->selectOne('users', ['id' => $userId]);
    if (!$user) {
      sendResponse('error', null, 'User not found');
    }

    if (isset($data['old_password']) && isset($data['new_password']) && isset($data['confirm_password'])) {
      $oldPassword = $data['old_password'] ?? '';
      $newPassword = $data['new_password'] ?? '';
      $confirmPassword = $data['confirm_password'] ?? '';
      if (!$oldPassword || !$newPassword || !$confirmPassword) {
        sendResponse('error', null, 'All fields are required.');
      }
      if ($newPassword !== $confirmPassword) {
        sendResponse('error', null, 'New password and confirm password do not match.');
      }
      if (!password_verify($oldPassword, $user['password'])) {
        sendResponse('error', null, 'Old password does not match.');
      }
      $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
      $updateFields[] = 'password = ?';
      $params[] = $newHashedPassword;
    } elseif (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
      $targetDir = '../uploads/';
      $fileName = basename($_FILES['profile_picture']['name']);
      $targetFilePath = $targetDir . $fileName;

      if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath)) {
        $updateFields[] = 'profile_picture = ?';
        $params[] = $fileName;
        echo json_encode(['success' => true, 'new_image' => $fileName]);
        exit;
      } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload file.']);
        exit;
      }
    } else {
      echo json_encode(['success' => false, 'message' => 'invalid request' ]);
      exit;
    }

    if (!$updateFields) {
      sendResponse('error', null, 'No fields to update');
    }

    $params[] = $userId;
    $sql = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE id = ?";
    if ($db->query($sql, $params) === false) {
      sendResponse('error', null, 'Failed to update user');
    }

    header('Content-Type: application/json');
    sendResponse('success', null, 'Password updated successfully.');
  } catch (Exception $e) {
    sendResponse('error', null, 'Failed to update user: ' . $e->getMessage());
  }
}



/*
function handleDeleteRequests()
{
  deleteUser();
}

function getUserProfile()
{
  global $pdo;

  $userId = $_GET['user_id'] ?? null;
  // If no user_id provided, use the current logged-in user
  if (!$userId && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
  }
  if (!$userId) {
    sendResponse('error', null, 'User ID is required');
  }

  try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    if (!$user) {
      sendResponse('error', null, 'User not found');
    }
    // Remove password from response
    unset($user['password']);
    sendResponse('success', $user, 'User profile retrieved successfully');
  } catch (Exception $e) {
    sendResponse('error', null, 'Failed to get user profile: ' . $e->getMessage());
  }
}
  */

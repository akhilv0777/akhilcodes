<?php
include 'config.php';



// if (isset($_POST['login_button'])) {
//   if (empty($_SESSION['csrf_token'])) {
//     $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
//     header('Location: ' . $_SERVER['HTTP_REFERER']);
//     exit();
//   }
//   if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
//     $_SESSION['err'] = "Invalid CSRF token";
//     header('Location: ' . $_SERVER['HTTP_REFERER']);
//     exit();
//   }
//   $login = $_POST['login'];
//   $password = $_POST['password'];

//   $user = $db->selectOne('users', ['email' => $login]);
//   if (!$user) {
//     $_SESSION['err'] = "Invalid username/email or password.";
//     header('Location: ' . $_SERVER['HTTP_REFERER']);
//     exit();
//   }else{
//     $user_id = $user['id'];
//     $user_name = $user['username'];
//     $hashed_password = $user['password'];
//     if (password_verify($password, $hashed_password)) {
//       $_SESSION['user_id']  = $user_id;
//       $_SESSION['username'] = $user_name;
//       header("Location: ../index.php");
//     } else {
//       $_SESSION['err'] = "Invalid username/email or password.";
//     }
//   }
//   header('Location: ' . $_SERVER['HTTP_REFERER']);
//   exit();
// }



// if (isset($_POST['change_password'])) {
//   $old = $_POST['old_password'];
//   $new = $_POST['new_password'];
//   $confirm = $_POST['confirm_password'];

//   if (empty($old) || empty($new) || empty($confirm)) {
//     $_SESSION['err'] = "Fill all fields.";
//   } elseif ($new !== $confirm) {
//     $_SESSION['err'] = "Passwords don't match.";
//   } elseif (strlen($new) < 8) {
//     $_SESSION['err'] = "Password must be at least 8 characters.";
//   } elseif (!preg_match('/[A-Z]/', $new)) {
//     $_SESSION['err'] = "Uppercase required.";
//   } elseif (!preg_match('/[a-z]/', $new)) {
//     $_SESSION['err'] = "Lowercase required.";
//   } elseif (!preg_match('/[0-9]/', $new)) {
//     $_SESSION['err'] = "Number required.";
//   } else {
//     $stmt = $conn->prepare("SELECT password FROM user");
//     $stmt->execute();
//     $stmt->bind_result($current_pass);
//     $stmt->fetch();
//     $stmt->close();

//     if (!password_verify($old, $current_pass)) {
//       $_SESSION['err'] = "Incorrect old password.";
//     } else {
//       $new_hash = password_hash($new, PASSWORD_DEFAULT);
//       $stmt = $conn->prepare("UPDATE user SET password = ?");
//       $stmt->bind_param("s", $new_hash);
//       if ($stmt->execute()) {
//         $_SESSION['msg'] = "Password updated successfully.";
//       } else {
//         $_SESSION['err'] = "Password update failed. Please try again.";
//       }
//     }
//     header('Location: ' . $_SERVER['HTTP_REFERER']);
//     exit();
//   }
// }

// $conn->close();

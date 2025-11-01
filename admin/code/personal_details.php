<?php
session_start();
require_once 'db_connection.php';

if (isset($_POST['submit']) && $_POST['submit'] == 'contact_details') {
  if (empty($_POST['first_name']) || empty($_POST['last_name'])) {
    echo "First name and Last name are required.";
    exit();
  }

  $query = "SELECT contact_details FROM user";
  $result = $conn->query($query);

  if ($result && $row = $result->fetch_assoc()) {
    $unserialized_data = isset($row['contact_details']) ? unserialize($row['contact_details']) : [];

    $unserialized_data['phone'] = !isset($unserialized_data['phone']) ? (!empty($_POST['phone']) ? $_POST['phone'] : ['phone'])  : (!empty($_POST['phone']) ? $_POST['phone'] : $unserialized_data['phone']);
    $unserialized_data['dob'] = !isset($unserialized_data['dob']) ? (!empty($_POST['dob']) ? $_POST['dob'] : '') : (!empty($_POST['dob']) ? $_POST['dob'] : $unserialized_data['dob']);
    $unserialized_data['village'] = !isset($unserialized_data['village']) ? (!empty($_POST['village']) ? $_POST['village'] : '') : (!empty($_POST['village']) ? ucwords($_POST['village']) : $unserialized_data['village']);
    $unserialized_data['city'] = !isset($unserialized_data['city']) ? (!empty($_POST['city']) ? $_POST['city'] : '') : (!empty($_POST['city']) ? ucwords($_POST['city']) : $unserialized_data['city']);
    $unserialized_data['state'] = !isset($unserialized_data['state']) ? (!empty($_POST['state']) ? $_POST['state'] : '') : (!empty($_POST['state']) ? ucwords($_POST['state']) : $unserialized_data['state']);
    $unserialized_data['zip'] = !isset($unserialized_data['zip']) ? (!empty($_POST['zip']) ? $_POST['zip'] : '') : (!empty($_POST['zip']) ? $_POST['zip'] : $unserialized_data['zip']);
    $unserialized_data['facebook'] = !isset($unserialized_data['facebook']) ? (!empty($_POST['facebook']) ? $_POST['facebook'] : '') : (!empty($_POST['facebook']) ? $_POST['facebook'] : $unserialized_data['facebook']);
    $unserialized_data['twitter'] = !isset($unserialized_data['twitter']) ? (!empty($_POST['twitter']) ? $_POST['twitter'] : '') : (!empty($_POST['twitter']) ? $_POST['twitter'] : $unserialized_data['twitter']);
    $unserialized_data['linkedin'] = !isset($unserialized_data['linkedin']) ? (!empty($_POST['linkedin']) ? $_POST['linkedin'] : '')  : (!empty($_POST['linkedin']) ? $_POST['linkedin'] : $unserialized_data['linkedin']);
    $unserialized_data['location'] = !empty($_POST['location'])  ? htmlspecialchars($_POST['location'], ENT_QUOTES, 'UTF-8')  : (!isset($unserialized_data['location']) ? '' : $unserialized_data['location']);
  }


  $contact_types = isset($_POST['contact_type']) ? array_map('strtolower', $_POST['contact_type']) : [];
  $contact_values = isset($_POST['contact_value']) ? $_POST['contact_value'] : [];


  for ($i = 0; $i < count($contact_types); $i++) {
    if (!empty($contact_types[$i]) && !empty($contact_values[$i])) {
      $contact_type = $contact_types[$i];
      $contact_value = $contact_values[$i];

      $contact_exists = false;
      if (isset($unserialized_data['additional_contacts'])) {
        foreach ($unserialized_data['additional_contacts'] as &$existing_contact) {
          if (array_key_exists($contact_type, $existing_contact)) {
            $existing_contact[$contact_type] = $contact_value;
            $contact_exists = true;
            break;
          }
        }
      }
      if (!$contact_exists) {
        $unserialized_data['additional_contacts'][] = [$contact_type => $contact_value];
      }
    }
  }

  $serialized_contact_details = serialize($unserialized_data);
  $full_name = ucwords(trim($_POST['first_name']) . ' ' . trim($_POST['last_name']));

  $email = (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? strtolower($_POST['email']) : '');
  $update_query = "UPDATE user SET name = '$full_name', email = '$email', contact_details = '$serialized_contact_details' ";
  if ($conn->query($update_query)) {
    $_SESSION['msg'] = "Profile updated successfully.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
  } else {
    $_SESSION['err'] = "Error updating profile.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
}



if (isset($_POST['submit']) && $_POST['submit'] == 'profile_picture' && isset($_FILES['profile_picture'])) {
  $file = $_FILES['profile_picture'];
  $targetDir = '../uploads/';
  $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

  if (!in_array($file['type'], $allowedTypes)) {
    $_SESSION['err'] = "Invalid file type.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }
  $query = "SELECT profile_picture FROM user";
  $result = $conn->query($query);

  if ($result && $row = $result->fetch_assoc()) {
    $oldFile = $row['profile_picture'];
    if (!empty($oldFile) && file_exists($oldFile)) {
      unlink($oldFile);
    }
  }
  $fileName = 'profile_' . uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
  $targetFile = $targetDir . $fileName;
  if (move_uploaded_file($file['tmp_name'], $targetFile)) {
    $updateQuery = "UPDATE user SET profile_picture = '$fileName'";
    if ($conn->query($updateQuery)) {
      $_SESSION['msg'] = "Image uploaded and old image deleted successfully!";
      header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
      $_SESSION['err'] = "Error updating database.";
      header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
  } else {
    $_SESSION['err'] = "Error uploading file.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
}


if (isset($_POST['key'])) {
  $key = $_POST['key'];
  $query = "SELECT contact_details FROM user ";
  $result = $conn->query($query);
  if ($result && $row = $result->fetch_assoc()) {
    $unserialized_data = unserialize($row['contact_details']);
    if (isset($unserialized_data['additional_contacts'])) {
      $additional_contacts = &$unserialized_data['additional_contacts'];
      if (isset($unserialized_data['additional_contacts'])) {
        foreach ($unserialized_data['additional_contacts'] as &$contact) {
          if (isset($contact[$key])) {
            unset($contact[$key]);
          }
        }
      }
      $updated_contact_details = serialize($unserialized_data);
      $update_query = "UPDATE user SET contact_details = '$updated_contact_details'";
      if ($conn->query($update_query)) {
        echo json_encode(['success' => true]);
      } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update the database']);
      }
    }
  }
}



if (isset($_POST['about_us'])) {
  $title = ucwords($_POST['title'] ?? '');
  $about = htmlspecialchars($_POST['about']) ?? '';
  $resumePath = '';

  // Handle resume upload
  if ($_FILES['resume']['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['pdf', 'docx', 'doc'];
    $fileType = strtolower(pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION));

    if (in_array($fileType, $allowedTypes) && $_FILES['resume']['size'] <= 2 * 1024 * 1024) {
      $uploadDir = '../uploads/';
      $resumePath = 'resume_' . uniqid() . '.' . $fileType;
      if (!move_uploaded_file($_FILES['resume']['tmp_name'], $uploadDir . $resumePath)) {
        $_SESSION['err'] = "File upload failed.";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
    } else {
      $_SESSION['err'] = "Invalid file type or size exceeds limit.";
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
  }

  $sql = "SELECT resume FROM about_us LIMIT 1";
  $result = $conn->query($sql);
  if ($result && $row = $result->fetch_assoc()) {
    $oldResumePath = $row['resume'];
    if ($oldResumePath && file_exists("../uploads/$oldResumePath")) unlink("../uploads/$oldResumePath");
  }

  $sql = "INSERT INTO about_us (title, about, resume) VALUES ('$title', '$about', '$resumePath') 
            ON DUPLICATE KEY UPDATE title = '$title', about = '$about', resume = '$resumePath'";
  if ($conn->query($sql)) {
    $_SESSION['msg'] = "Update successful.";
  } else {
    $_SESSION['err'] = "Error: " . $conn->error;
  }
  header('Location: ' . $_SERVER['HTTP_REFERER']);
  exit;
}



if (isset($_POST['resume_details'])) {

  // Education details
  $education_types = isset($_POST['education_type']) ? $_POST['education_type'] : [];
  $education_values = isset($_POST['education_value']) ? $_POST['education_value'] : [];
  $education_start = isset($_POST['education_start']) ? $_POST['education_start'] : [];
  $education_end = isset($_POST['education_end']) ? $_POST['education_end'] : [];

  // Experience details
  $experience_types = isset($_POST['experience_type']) ? $_POST['experience_type'] : [];
  $experience_values = isset($_POST['experience_value']) ? $_POST['experience_value'] : [];
  $experience_start = isset($_POST['experience_start']) ? $_POST['experience_start'] : [];
  $experience_end = isset($_POST['experience_end']) ? $_POST['experience_end'] : [];

  // Skills details
  $skills_types = isset($_POST['skills_type']) ? $_POST['skills_type'] : [];
  $skills_values = isset($_POST['skills_value']) ? $_POST['skills_value'] : [];

  $resume_details = array(
    'education' => array(),
    'experience' => array(),
    'skills' => array()
  );

  // Process Education details
  foreach ($education_types as $key => $value) {
    if (strlen($value) > 0 && strlen($education_values[$key]) > 0) {
      $resume_details['education'][] = array(
        'type' => $value,
        'value' => $education_values[$key],
        'start' => isset($education_start[$key]) ? $education_start[$key] : '',
        'end' => isset($education_end[$key]) ? $education_end[$key] : ''
      );
    }
  }

  // Process Experience details
  foreach ($experience_types as $key => $value) {
    if (strlen($value) > 0 && strlen($experience_values[$key]) > 0) {
      $resume_details['experience'][] = array(
        'type' => $value,
        'value' => $experience_values[$key],
        'start' => isset($experience_start[$key]) ? $experience_start[$key] : '',
        'end' => isset($experience_end[$key]) ? $experience_end[$key] : ''
      );
    }
  }

  // Process Skills details
  foreach ($skills_types as $key => $value) {
    if (strlen($value) > 0 && strlen($skills_values[$key]) > 0) {
      $resume_details['skills'][] = array(
        'type' => $value,
        'value' => $skills_values[$key],
      );
    }
  }

  // Serialize the resume details
  $serialized_data = serialize($resume_details);

  // Update the database with serialized data
  if ($stmt = $conn->prepare("UPDATE about_us SET resume_details = ?")) {
    $stmt->bind_param("s", $serialized_data);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
      $_SESSION['msg'] = "Data updated successfully!";
    } else {
      $_SESSION['err'] = "No changes found!";
    }
    $stmt->close();
  } else {
    $_SESSION['err'] = "Database error: Unable to prepare the update statement.";
  }
  header('Location: ' . $_SERVER['HTTP_REFERER']);
  exit;
}


if (isset($_POST['resume_type']) && isset($_POST['resume_category'])) {
  $resume_type = $_POST['resume_type'];
  $resume_category = $_POST['resume_category'];
  $query = "SELECT resume_details FROM about_us";
  $result = $conn->query($query);
  if ($result && $row = $result->fetch_assoc()) {
    $unserialized_data = unserialize($row['resume_details']);
    foreach ($unserialized_data as $category => &$items) {
      foreach ($items as $index => $entry) {
        if ($category == $resume_category && $entry['type'] == $resume_type) {
          unset($items[$index]);
        }
      }
    }
    $updated_resume_details = serialize($unserialized_data);
    $update_query = "UPDATE about_us SET resume_details = '$updated_resume_details'";
    if ($conn->query($update_query)) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['success' => false, 'message' => 'Failed to update the database']);
    }
  }
}
$conn->close();

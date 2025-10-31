<?php
session_start();
require_once 'db_connection.php';

$upload_dir = '../uploads/projects/';

if (
    isset($_POST['title'], $_POST['category'], $_POST['category_name'], $_POST['content'], $_FILES['thumbnail'], $_FILES['images']) &&
    !empty($_POST['title']) && (!empty($_POST['category']) || !empty($_POST['category_name'])) && !empty($_POST['content']) &&
    !empty($_FILES['thumbnail']['name']) && !empty($_FILES['images']['name'][0])
) {

    $title = $_POST['title'];
    $category = $_POST['category'];
    $category_name = $_POST['category_name'];
    $content = $_POST['content'];

    $thumbnail = 'thumbnail_' . uniqid() . '.' . pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
    move_uploaded_file($_FILES['thumbnail']['tmp_name'], $upload_dir . $thumbnail);

    $images = [];
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $image_name = 'image_' . uniqid() . '.' . pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
        move_uploaded_file($tmp_name, $upload_dir . $image_name);
        $images[] = $image_name;
    }

    $details = serialize(['title' => $title, 'category' => $category, 'other_category' => $category_name, 'content' => $content]);
    $images_serialize = serialize(['thumbnail' => $thumbnail, 'images' => $images]);

    $stmt = $conn->prepare("INSERT INTO projects (details, images) VALUES (?, ?)");
    $stmt->bind_param("ss", $details, $images_serialize);
    $stmt->execute() ? $_SESSION['msg'] = "Project inserted successfully!" : $_SESSION['err'] = "Error: " . $stmt->error;
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    $_SESSION['err'] = "Required fields are missing.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}




if (isset($_POST['project_delete']) && $_POST['project_delete'] == 'project_delete' && !empty($_POST['selected_ids']) && is_array($_POST['selected_ids'])) {
    $ids = implode(',', array_map('intval', $_POST['selected_ids']));
    $query = "SELECT * FROM projects WHERE id IN ($ids)";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $images = @unserialize($row['images']);
            if ($images) {
                $files = array_merge([$images['thumbnail']], $images['images'] ?? []);
                foreach ($files as $file) {
                    if ($file && file_exists("../uploads/projects/$file")) {
                        unlink("../uploads/projects/$file");
                    }
                }
            }
        }
        $deleteQuery = "DELETE FROM projects WHERE id IN ($ids)";
        if ($conn->query($deleteQuery)) {
            $_SESSION['msg'] = "Project deleted successfully";
        }
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

$conn->close();

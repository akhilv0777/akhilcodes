<?php
function getUserData($conn)
{
    include 'db_connection.php';
    $query = "SELECT * FROM user";
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        if ($row) {
            return $row;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
$user_data = getUserData($conn);
$contact_details = unserialize(getUserData($conn)['contact_details']);

function getAboutUs()
{
    include 'db_connection.php';
    $query = "SELECT * FROM about_us";
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        if ($row) {
            return $row;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
$aboutData = getAboutUs($conn);
$resumeDetails = unserialize($aboutData['resume_details']);


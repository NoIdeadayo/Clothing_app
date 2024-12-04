<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo 'fail';
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo 'fail';
    exit;
}

$stmt = $conn->prepare("DELETE FROM clothes WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $_SESSION['user_id']);

if ($stmt->execute()) {
    echo 'success';
} else {
    echo 'fail';
}

$stmt->close();
$conn->close();
?>

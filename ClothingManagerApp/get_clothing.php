<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$category = isset($_GET['category']) ? $_GET['category'] : '';
$subcategory = isset($_GET['subcategory']) ? $_GET['subcategory'] : '';

if (empty($category) || empty($subcategory)) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM clothes WHERE user_id = ? AND category = ? AND subcategory = ?");
$stmt->bind_param("iss", $_SESSION['user_id'], $category, $subcategory);

$stmt->execute();
$result = $stmt->get_result();

$clothes = [];
while ($row = $result->fetch_assoc()) {
    $clothes[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($clothes);
?>

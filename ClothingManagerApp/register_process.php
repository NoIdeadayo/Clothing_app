<?php
session_start();
include 'db.php'; // データベース接続設定

// エラーメッセージを表示する設定
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // フォームから送信されたデータを取得
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 入力データの検証
    if (empty($username) || empty($password)) {
        $_SESSION['message'] = "Username and password are required.";
        header("Location: register.php");
        exit;
    }

    // パスワードをハッシュ化する（セキュリティのため）
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // データベースにユーザーを挿入
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        // 登録成功時の処理
        $_SESSION['message'] = "Registration successful. You can now login.";
        header("Location: index.php");
        exit;
    } else {
        // エラー処理
        $_SESSION['message'] = "Registration failed. Please try again. Error: " . htmlspecialchars($stmt->error);
        header("Location: register.php");
        exit;
    }

    $stmt->close();
}
?>

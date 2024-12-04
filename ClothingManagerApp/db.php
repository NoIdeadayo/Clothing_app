<?php
$servername = "localhost"; // データベースのホスト名
$username = "sample"; // データベースのユーザー名
$password = "password"; // データベースのパスワード
$dbname = "clothingapp"; // データベース名

// データベースへの接続
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

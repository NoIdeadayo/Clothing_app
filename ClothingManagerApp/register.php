<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>
    <style>
        body {
            background-color: #CCFFFF; /* 水色の背景色 */
            display: flex;
            justify-content: center; /* コンテンツを中央に配置する */
            align-items: center; /* コンテンツを中央に配置する */
            height: 100vh; /* ビューポートの高さいっぱいに表示 */
            margin: 0;
        }
        form {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center; /* フォーム内の要素を中央に揃える */
            margin-top: 20px; /* 上部の余白を設定 */
        }
        h1 {
            text-align: center; /* タイトルを中央揃え */
            margin-bottom: 20px; /* ボタンとの間隔を調整 */
            margin-top: 20px; /* 上部の余白を設定 */
        }
        button[type="submit"] {
            background-color: #D7EEFF; /* ボタンの背景色 */
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        button[type="submit"]:hover {
            background-color: #A4C6FF; /* ホバー時の背景色 */
        }
    </style>
</head>
<body>
    
    <form method="POST" action="register_process.php">
        <label for="username">ユーザーネーム:</label>
        <input type="text" id="username" name="username" required>
        <br><br>
        <label for="password">パスワード:</label>
        <input type="password" id="password" name="password" required>
        <br><br>
        <button type="submit">登録</button>
    </form>
</body>
</html>

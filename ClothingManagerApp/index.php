<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clothing Management App</title>
    <style>
        body {
            background-color: #CCFFFF;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        nav {
            margin-top: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin: 10px 0;
        }
        a {
            text-decoration: none;
            color: white;
            background-color: #FFD5EC;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease; /* トランジションを追加 */
            display: inline-block;
        }
        a:hover {
            background-color: #FF97C2;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* ホバー時のボックスシャドウを追加 */
        }
    </style>
</head>
<body>
    <div>
        <h1>Clothing Managerへようこそ</h1>
        <nav>
            <ul>
                <li><a href="register.php">新規登録</a></li>
                <li><a href="login.php">Loginはこちらから</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>

<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $size = $_POST['size'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    $price = $_POST['price'];
    $notes = $_POST['notes'];

    // 入力データの検証
    if (empty($name) || empty($size) || empty($category) || empty($subcategory) || empty($price)) {
        $_SESSION['message'] = "All fields except notes are required.";
        header("Location: add_clothing.php");
        exit;
    }

    // データベースに洋服情報を挿入
    $stmt = $conn->prepare("INSERT INTO clothes (user_id, name, size, category, subcategory, price, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("issssis", $_SESSION['user_id'], $name, $size, $category, $subcategory, $price, $notes);

    if ($stmt->execute()) {
        // 登録成功時の処理
        error_log("Clothing registered successfully. Redirecting to mypage.php");
        header("Location: mypage.php");
        exit;
    } else {
        // エラー処理
        $_SESSION['message'] = "Registration failed. Please try again. Error: " . htmlspecialchars($stmt->error);
        error_log("Registration failed. Error: " . htmlspecialchars($stmt->error));
        header("Location: add_clothing.php");
        exit;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>洋服追加ページ</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #CCFFFF;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: lightblue;
            color: white;
        }
        .btn-custom:hover {
            background-color: #87ceeb;
        }
    </style>
    <script>
        const subcategories = {
            Tops: ["Tシャツ", "ポロシャツ","タンクトップ","セーター/ニット","トレーナー/スウェット","パーカー","フリース","カーディガン","ベスト","シャツ", "ジャケット"],
            Pants: ["ワイドパンツ", "スキニーパンツ","ストレートパンツ","フレアパンツ","カーゴパンツ","デニムパンツ","コーデュロイパンツ"],
            Shoes: ["スニーカー", "革靴","ブーツ", "サンダル"],
            Accessories: ["ネクタイ", "下着","帽子","ベルト", "ネックレス","イヤリング/ピアス","ネックレス","バッグ","サングラス"]
        };

        function updateSubcategories() {
            const mainCategory = document.getElementById('mainCategory').value;
            const subCategorySelect = document.getElementById('subCategory');
            subCategorySelect.innerHTML = ''; // 現在のサブカテゴリーをクリア

            if (mainCategory) {
                subcategories[mainCategory].forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub;
                    option.textContent = sub;
                    subCategorySelect.appendChild(option);
                });
                subCategorySelect.disabled = false;
            } else {
                subCategorySelect.disabled = true;
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('mainCategory').addEventListener('change', updateSubcategories);
        });
    </script>
</head>
<body>
    <div class="container">
        <h1 class="text-center my-4">新しく洋服を追加</h1>
        <div class="form-container">
            <form method="POST" action="add_clothing.php">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                </div>
                <div class="form-group">
                    <label for="size">Size</label>
                    <input type="text" class="form-control" id="size" name="size" placeholder="Size" required>
                </div>
                <div class="form-group">
                    <label for="mainCategory">カテゴリー</label>
                    <select class="form-control" id="mainCategory" name="category" required>
                        <option value="" disabled selected>カテゴリーを選択</option>
                        <option value="Tops">トップス</option>
                        <option value="Pants">パンツ</option>
                        <option value="Shoes">シューズ</option>
                        <option value="Accessories">アクセサリー</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="subCategory">サブカテゴリー</label>
                    <select class="form-control" id="subCategory" name="subcategory" required disabled>
                        <option value="" disabled selected>サブカテゴリーを選択</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">価格</label>
                    <input type="text" class="form-control" id="price" name="price" placeholder="価格" required>
                </div>
                <div class="form-group">
                    <label for="notes">メモ</label>
                    <textarea class="form-control" id="notes" name="notes" placeholder="メモ"></textarea>
                </div>
                <button type="submit" class="btn btn-custom btn-block">洋服を追加</button>
            </form>
            <?php
            if (isset($_SESSION['message'])) {
                echo "<div class='alert alert-info mt-3'>" . $_SESSION['message'] . "</div>";
                unset($_SESSION['message']);
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

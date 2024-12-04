<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// 洋服情報を取得
$stmt = $conn->prepare("SELECT * FROM clothes WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$clothes = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #CCFFFF;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .button-group {
            margin-bottom: 20px;
            text-align: center;
        }
        .button-group form {
            display: inline-block;
        }
        .category-button, .subcategory-button, .delete-button, .edit-button {
            margin: 5px;
            padding: 10px 20px;
            background-color: #D7EEFF;
            border: 1px solid #ccc;
            cursor: pointer;
            border-radius: 4px;
            font-size: 14px;
        }
        .category-button:hover, .subcategory-button:hover, .delete-button:hover, .edit-button:hover {
            background-color: #A4C6FF;
        }
        .clothing-item {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .logout-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #EAD9FF;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-size: 14px;
        }
        .logout-button:hover {
            background-color: #D0B0FF;
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
            subCategorySelect.innerHTML = '';

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

        function fetchClothing() {
            const category = document.getElementById('mainCategory').value;
            const subcategory = document.getElementById('subCategory').value;

            if (!category || !subcategory) {
                alert("カテゴリーとサブカテゴリーを選択してください");
                return;
            }

            fetch(`get_clothing.php?category=${category}&subcategory=${subcategory}`)
                .then(response => response.json())
                .then(data => {
                    const clothingList = document.getElementById('clothingList');
                    clothingList.innerHTML = '';

                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.classList.add('clothing-item');
                        div.innerHTML = `
                            <p>Name: ${item.name}</p>
                            <p>Size: ${item.size}</p>
                            <p>Category: ${item.category}</p>
                            <p>Subcategory: ${item.subcategory}</p>
                            <p>Price: ${item.price}</p>
                            <p>Notes: ${item.notes}</p>
                            <button class="edit-button" onclick="editClothing(${item.id})">編集</button>
                            <button class="delete-button" onclick="deleteClothing(${item.id})">削除</button>
                        `;
                        clothingList.appendChild(div);
                    });
                });
        }

        function deleteClothing(id) {
            if (confirm("本当に削除しますか？")) {
                fetch(`delete_clothing.php?id=${id}`, { method: 'POST' })
                    .then(response => response.text())
                    .then(data => {
                        if (data === 'success') {
                            alert("削除しました");
                            fetchClothing();
                        } else {
                            alert("削除に失敗しました");
                        }
                    });
            }
        }

        function editClothing(id) {
            window.location.href = `edit_clothing.php?id=${id}`;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>マイページ</h1>

        <div class="button-group">
            <form method="GET" action="add_clothing.php">
                <button type="submit" class="category-button">新しく洋服を追加</button>
            </form>
        </div>

        <h2>あなたの洋服</h2>

        <div class="button-group">
            <select id="mainCategory" class="form-control" onchange="updateSubcategories()">
                <option value="" disabled selected>カテゴリーを選択</option>
                <option value="Tops">トップス</option>
                <option value="Pants">パンツ</option>
                <option value="Shoes">シューズ</option>
                <option value="Accessories">アクセサリー</option>
            </select>
            <select id="subCategory" class="form-control mt-2" disabled>
                <option value="" disabled selected>サブカテゴリーを選択</option>
            </select>
            <button class="subcategory-button mt-2" onclick="fetchClothing()">表示</button>
        </div>

        <div id="clothingList" class="mt-4"></div>

        <form method="POST" action="logout.php">
            <button type="submit" class="logout-button">ログアウト</button>
        </form>
    </div>

    <?php
    if (isset($_SESSION['message'])) {
        echo "<p class='text-center text-info'>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }
    ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

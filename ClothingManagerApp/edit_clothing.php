<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: mypage.php");
    exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// 洋服情報を取得
$stmt = $conn->prepare("SELECT * FROM clothes WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$clothing = $result->fetch_assoc();
$stmt->close();

if (!$clothing) {
    $_SESSION['message'] = "指定された洋服は存在しません。";
    header("Location: mypage.php");
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
        header("Location: edit_clothing.php?id=$id");
        exit;
    }

    // 洋服情報を更新
    $stmt = $conn->prepare("UPDATE clothes SET name = ?, size = ?, category = ?, subcategory = ?, price = ?, notes = ? WHERE id = ? AND user_id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("sssssiii", $name, $size, $category, $subcategory, $price, $notes, $id, $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "アイテムは更新されました！";
        error_log("Clothing updated successfully. Redirecting to mypage.php");
        header("Location: mypage.php");
        exit;
    } else {
        $_SESSION['message'] = "更新に失敗しました。もう一度お試しください。 Error: " . htmlspecialchars($stmt->error);
        error_log("Update failed. Error: " . htmlspecialchars($stmt->error));
        header("Location: edit_clothing.php?id=$id");
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
    <title>洋服編集ページ</title>
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
            updateSubcategories();
            const subcategory = "<?php echo $clothing['subcategory']; ?>";
            const subCategorySelect = document.getElementById('subCategory');
            const options = subCategorySelect.options;
            for (let i = 0; i < options.length; i++) {
                if (options[i].value === subcategory) {
                    options[i].selected = true;
                    break;
                }
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <h1 class="text-center my-4">洋服情報を編集</h1>
        <div class="form-container">
            <form method="POST" action="edit_clothing.php?id=<?php echo $id; ?>">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="<?php echo htmlspecialchars($clothing['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="size">Size</label>
                    <input type="text" class="form-control" id="size" name="size" placeholder="Size" value="<?php echo htmlspecialchars($clothing['size']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="mainCategory">カテゴリー</label>
                    <select class="form-control" id="mainCategory" name="category" required onchange="updateSubcategories()">
                        <option value="" disabled>カテゴリーを選択</option>
                        <option value="Tops" <?php echo $clothing['category'] == 'Tops' ? 'selected' : ''; ?>>トップス</option>
                        <option value="Pants" <?php echo $clothing['category'] == 'Pants' ? 'selected' : ''; ?>>パンツ</option>
                        <option value="Shoes" <?php echo $clothing['category'] == 'Shoes' ? 'selected' : ''; ?>>シューズ</option>
                        <option value="Accessories" <?php echo $clothing['category'] == 'Accessories' ? 'selected' : ''; ?>>アクセサリー</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="subCategory">サブカテゴリー</label>
                    <select class="form-control" id="subCategory" name="subcategory" required disabled>
                        <option value="" disabled>サブカテゴリーを選択</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">価格</label>
                    <input type="text" class="form-control" id="price" name="price" placeholder="価格" value="<?php echo htmlspecialchars($clothing['price']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="notes">メモ</label>
                    <textarea class="form-control" id="notes" name="notes" placeholder="メモ"><?php echo htmlspecialchars($clothing['notes']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-custom btn-block">更新する</button>
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

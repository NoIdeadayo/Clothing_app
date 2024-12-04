create user 'sample'@'localhost' identified by 'password';
grant all privileges on * . * to 'sample'@'localhost';
flush privileges;

mysql -u sample -p password

//データベース
CREATE DATABASE clothingApp;

//clothingAppテーブルを使用
use clothingApp;

//userテーブルの作成

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

//clotesテーブルの作成

CREATE TABLE clothes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    size VARCHAR(50) NOT NULL,
    category VARCHAR(50) NOT NULL,
    price INT NOT NULL,
    notes TEXT
);

//サブカテゴリーの追加
ALTER TABLE clothes ADD COLUMN subcategory VARCHAR(255);

http://localhost/CLOthingManagerApp/login.php

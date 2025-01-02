<?php
require '../includes/config.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $url = $_POST['url'];
        $category = $_POST['category'];

        $stmt = $pdo->prepare('INSERT INTO books (title, author, url, category, created_at) VALUES (?, ?, ?, ?, NOW())');
        $stmt->execute([$title, $author, $url, $category]);

        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    die('データベースエラー: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>本を追加</title>
    <link rel="stylesheet" href="./add.css">
</head>
<body>
    <div class="form-container">
        <h1>新しい本を追加</h1>
        <form action="add.php" method="POST">
            <div class="form-group">
                <label for="title">タイトル</label>
                <input type="text" id="title" name="title" placeholder="本のタイトルを入力" required>
            </div>
            <div class="form-group">
                <label for="author">著者</label>
                <input type="text" id="author" name="author" placeholder="著者名を入力" required>
            </div>
            <div class="form-group">
                <label for="url">URL</label>
                <input type="url" id="url" name="url" placeholder="本のリンクを入力 (任意)">
            </div>
            <div class="form-group">
                <label for="category">カテゴリ</label>
                <input type="text" id="category" name="category" placeholder="カテゴリを入力 (例: Fiction, Non-fiction)">
            </div>
            <button type="submit" class="button">追加する</button>
        </form>
        <a href="index.php" class="button">戻る</a>
    </div>
</body>
</html>


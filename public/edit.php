<?php
require '../includes/config.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT * FROM books WHERE id = ?');
    $stmt->execute([$id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        header('Location: index.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $url = $_POST['url'];
        $category = $_POST['category'];

        $updateStmt = $pdo->prepare('UPDATE books SET title = ?, author = ?, url = ?, category = ? WHERE id = ?');
        $updateStmt->execute([$title, $author, $url, $category, $id]);

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
    <title>本を編集</title>
    <link rel="stylesheet" href="./add.css">
</head>
<body>
    <div class="form-container">
        <h1>本を編集</h1>
        <form action="" method="POST">
            <div class="form-group">
                <label for="title">タイトル</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="author">著者</label>
                <input type="text" id="author" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
            </div>
            <div class="form-group">
                <label for="url">URL</label>
                <input type="url" id="url" name="url" value="<?= htmlspecialchars($book['url']) ?>">
            </div>
            <div class="form-group">
                <label for="category">カテゴリ</label>
                <input type="text" id="category" name="category" value="<?= htmlspecialchars($book['category']) ?>" required>
            </div>
            <button type="submit" class="button">保存する</button>
        </form>
        <a href="index.php" class="button">戻る</a>
    </div>
</body>
</html>

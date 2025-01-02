<?php
require '../includes/config.php';

try {
    $search = $_GET['search'] ?? '';
    $category = $_GET['category'] ?? '';
    $author = $_GET['author'] ?? '';
    $limit = 5;
    $page = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT, [
        'options' => ['default' => 1, 'min_range' => 1]
    ]);
    $offset = ($page - 1) * $limit;

    // ユニークなカテゴリと著者を取得
    $categories = $pdo->query('SELECT DISTINCT category FROM books WHERE category IS NOT NULL')->fetchAll(PDO::FETCH_ASSOC);
    $authors = $pdo->query('SELECT DISTINCT author FROM books')->fetchAll(PDO::FETCH_ASSOC);

    // 本のデータ取得クエリ
    $sql = 'SELECT * FROM books WHERE 1=1';
    $params = [];

    if (!empty($search)) {
        $sql .= ' AND (title LIKE :search OR author LIKE :search)';
        $params[':search'] = '%' . $search . '%';
    }

    if (!empty($category)) {
        $sql .= ' AND category = :category';
        $params[':category'] = $category;
    }

    if (!empty($author)) {
        $sql .= ' AND author = :author';
        $params[':author'] = $author;
    }

    $sql .= ' ORDER BY created_at DESC LIMIT :limit OFFSET :offset';
    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();

    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 総件数取得
    $countSql = 'SELECT COUNT(*) FROM books WHERE 1=1';
    $countParams = [];

    if (!empty($search)) {
        $countSql .= ' AND (title LIKE :search OR author LIKE :search)';
        $countParams[':search'] = '%' . $search . '%';
    }

    if (!empty($category)) {
        $countSql .= ' AND category = :category';
        $countParams[':category'] = $category;
    }

    if (!empty($author)) {
        $countSql .= ' AND author = :author';
        $countParams[':author'] = $author;
    }

    $countStmt = $pdo->prepare($countSql);

    foreach ($countParams as $key => $value) {
        $countStmt->bindValue($key, $value);
    }
    $countStmt->execute();
    $totalBooks = $countStmt->fetchColumn();
    $totalPages = ceil($totalBooks / $limit);
} catch (PDOException $e) {
    die('データベースエラー: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ブックマーク一覧</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div class="container">
        <h1>お気に入りの本</h1>

        <!-- 検索フォーム -->
        <form action="index.php" method="GET" class="search-form">
            <input type="text" name="search" placeholder="本を検索" value="<?= htmlspecialchars($search) ?>">
            <select name="category">
                <option value="">すべてのカテゴリ</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat['category']) ?>" <?= $category === $cat['category'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['category']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="author">
                <option value="">すべての著者</option>
                <?php foreach ($authors as $auth): ?>
                    <option value="<?= htmlspecialchars($auth['author']) ?>" <?= $author === $auth['author'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($auth['author']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="button">検索</button>
        </form>

        <!-- 本を追加するボタン -->
        <div class="add-button-container">
            <a href="add.php" class="button add-button">本を追加する</a>
        </div>

        <!-- 本リスト -->
        <ul class="list-group">
            <?php if (empty($books)): ?>
                <li class="list-group-item" style="text-align: center; color: #CDA04F;">
                    データが見つかりません。
                </li>
            <?php else: ?>
                <?php foreach ($books as $book): ?>
                    <li class="list-group-item">
                        <div>
                            <a href="<?= htmlspecialchars($book['url']) ?>" target="_blank">
                                <strong><?= htmlspecialchars($book['title']) ?></strong> by <?= htmlspecialchars($book['author']) ?>
                            </a>
                        </div>
                        <div>
                            <a href="edit.php?id=<?= htmlspecialchars($book['id']) ?>" class="button" style="margin-right: 10px;">編集</a>
                            <button class="button" onclick="showModal(<?= $book['id'] ?>)">削除</button>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <!-- ページネーション -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="index.php?page=<?= $i ?>&search=<?= htmlspecialchars($search) ?>&category=<?= htmlspecialchars($category) ?>&author=<?= htmlspecialchars($author) ?>" 
                   class="button <?= $i == $page ? 'current' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>

    <div id="modal" class="modal" style="display: none;">
        <div class="modal-content">
            <p>本当に削除しますか？</p>
            <form id="deleteForm" method="POST" action="delete.php">
                <input type="hidden" name="id" id="deleteId">
                <button type="submit" class="button" style="background-color: #CDA04F;">削除する</button>
                <button type="button" class="button" onclick="closeModal()">キャンセル</button>
            </form>
        </div>
    </div>

    <script>
        function showModal(id) {
            document.getElementById('modal').style.display = 'flex';
            document.getElementById('deleteId').value = id;
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }
    </script>
</body>
</html>



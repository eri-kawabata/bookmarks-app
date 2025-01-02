<?php
require '../includes/config.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $stmt = $pdo->prepare('DELETE FROM books WHERE id = ?');
        $stmt->execute([$id]);
    }
    header('Location: index.php');
} catch (PDOException $e) {
    die('データベースエラー: ' . $e->getMessage());
}
?>

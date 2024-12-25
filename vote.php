<?php
require_once './auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Неверный метод запроса.']);
    exit;
}

if (!isset($_POST['photo_id']) || !is_numeric($_POST['photo_id'])) {
    echo json_encode(['success' => false, 'message' => 'Некорректный ID фотографии.']);
    exit;
}

// Голосование.
$photo_id = (int)$_POST['photo_id'];
$result = vote($photo_id);

echo json_encode($result);
?>

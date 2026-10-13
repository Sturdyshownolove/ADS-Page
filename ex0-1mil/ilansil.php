<?php
session_start();
if (!isset($_SESSION['site_user'])) {
    http_response_code(403);
    exit('Yetkisiz erişim');
}

require_once(__DIR__ . '/../include/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM uyeler WHERE id = ?");
        $stmt->execute([$id]);
        echo "OK";
    }
}

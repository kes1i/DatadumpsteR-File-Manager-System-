<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Not authenticated";
    exit;
}

$fileId = $_GET['id'] ?? null;

if (!$fileId) {
    http_response_code(400);
    echo "File ID required";
    exit;
}

try {
    // Get file info and verify ownership
    $stmt = $pdo->prepare("SELECT filename, original_name, file_path, mime_type FROM files WHERE id = ? AND user_id = ?");
    $stmt->execute([$fileId, $_SESSION['user_id']]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$file) {
        http_response_code(404);
        echo "File not found";
        exit;
    }
    
    if (!file_exists($file['file_path'])) {
        http_response_code(404);
        echo "Physical file not found";
        exit;
    }
    
    // Set headers for file download
    header('Content-Type: ' . $file['mime_type']);
    header('Content-Disposition: attachment; filename="' . $file['original_name'] . '"');
    header('Content-Length: ' . filesize($file['file_path']));
    
    // Output file
    readfile($file['file_path']);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo "Download failed";
}
?>
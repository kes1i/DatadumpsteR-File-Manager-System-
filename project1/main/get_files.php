<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

function formatFileSize($bytes) {
    if ($bytes === 0) return '0 Bytes';
    $k = 1024;
    $sizes = ['Bytes', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes) / log($k));
    return round(($bytes / pow($k, $i)), 2) . ' ' . $sizes[$i];
}

try {
    $stmt = $pdo->prepare("SELECT id, original_name, file_size, mime_type, created_at FROM files WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $files = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $formattedFiles = [];
    foreach ($files as $file) {
        $formattedFiles[] = [
            'id' => $file['id'],
            'name' => $file['original_name'],
            'size' => formatFileSize($file['file_size']),
            'type' => $file['mime_type'],
            'uploadDate' => date('M j, Y', strtotime($file['created_at']))
        ];
    }
    
    echo json_encode(['success' => true, 'files' => $formattedFiles]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to retrieve files: ' . $e->getMessage()]);
}
?>
<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_FILES['files'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No files uploaded']);
    exit;
}

$userId = $_SESSION['user_id'];
$uploadedFiles = [];
$uploadDir = '../uploads/';

function formatFileSize($bytes) {
    if ($bytes === 0) return '0 Bytes';
    $k = 1024;
    $sizes = ['Bytes', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes) / log($k));
    return round(($bytes / pow($k, $i)), 2) . ' ' . $sizes[$i];
}
// Create uploads directory if it doesn't exist
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

try {
    $files = $_FILES['files'];
    $fileCount = count($files['name']);

    for ($i = 0; $i < $fileCount; $i++) {
        if ($files['error'][$i] === UPLOAD_ERR_OK) {
            $originalName = $files['name'][$i];
            $fileSize = $files['size'][$i];
            $mimeType = $files['type'][$i];
            $tempPath = $files['tmp_name'][$i];
            
            // Generate unique filename
            $fileExtension = pathinfo($originalName, PATHINFO_EXTENSION);
            $uniqueFilename = uniqid() . '_' . time() . '.' . $fileExtension;
            $filePath = $uploadDir . $uniqueFilename;
            
            // Move uploaded file
            if (move_uploaded_file($tempPath, $filePath)) {
                // Save to database
                $stmt = $pdo->prepare("INSERT INTO files (user_id, filename, original_name, file_path, file_size, mime_type) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$userId, $uniqueFilename, $originalName, $filePath, $fileSize, $mimeType]);
                
                $uploadedFiles[] = [
                'id' => $pdo->lastInsertId(),
                'name' => $originalName,
                'size' => formatFileSize($fileSize),
                'type' => $mimeType,
                'uploadDate' => date('Y-m-d H:i:s')
                ];
            }
        }
    }
    
    echo json_encode(['success' => true, 'files' => $uploadedFiles]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Upload failed']);
}
?>
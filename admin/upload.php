<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthenticated access.']);
    exit;
}

// Support GET for listing uploaded images
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['list'])) {
    $listDir = __DIR__ . '/../uploads/';
    $images = [];
    if (is_dir($listDir)) {
        $files = scandir($listDir);
        foreach ($files as $f) {
            if ($f === '.' || $f === '..') continue;
            $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                $images[] = 'uploads/' . $f;
            }
        }
    }
    echo json_encode(['success' => true, 'images' => $images]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    $errorCode = $_FILES['file']['error'] ?? -1;
    echo json_encode(['success' => false, 'message' => 'File upload failed with error code: ' . $errorCode]);
    exit;
}

$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
$allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

$file = $_FILES['file'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowedExts)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Allowed: ' . implode(', ', $allowedExts)]);
    exit;
}

$maxSize = 10 * 1024 * 1024;
if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'message' => 'File exceeds maximum size of 5MB.']);
    exit;
}

$uploadDir = __DIR__ . '/../uploads/';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        echo json_encode(['success' => false, 'message' => 'Failed to create uploads directory.']);
        exit;
    }
}

$filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._\-]/', '_', $file['name']);
$destPath = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $destPath)) {
    echo json_encode(['success' => false, 'message' => 'Failed to save uploaded file.']);
    exit;
}

echo json_encode(['success' => true, 'url' => 'uploads/' . $filename]);

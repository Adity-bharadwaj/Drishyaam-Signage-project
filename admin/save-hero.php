<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthenticated access.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON data.']);
    exit;
}

$dataFile = __DIR__ . '/../data/hero.json';

$existing = [];
if (file_exists($dataFile)) {
    $content = file_get_contents($dataFile);
    $parsed = json_decode($content, true);
    if (is_array($parsed)) {
        $existing = $parsed;
    }
}

$fields = ['badge', 'title_line1', 'title_line2', 'subtitle', 'chips', 'btn1_text', 'btn1_link', 'btn2_text', 'btn2_link', 'stats', 'bg_image', 'slider_images'];
foreach ($fields as $field) {
    if (array_key_exists($field, $input)) {
        $existing[$field] = $input[$field];
    }
}

$written = file_put_contents($dataFile, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
if ($written === false) {
    echo json_encode(['success' => false, 'message' => 'Failed to write hero data.']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Hero section updated!']);

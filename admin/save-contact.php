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

$dataFile = __DIR__ . '/../data/contact.json';

$existing = [];
if (file_exists($dataFile)) {
    $content = file_get_contents($dataFile);
    $parsed = json_decode($content, true);
    if (is_array($parsed)) {
        $existing = $parsed;
    }
}

// Map social fields back to nested object
$socialFields = ['social_instagram', 'social_facebook', 'social_linkedin', 'social_whatsapp'];
$socialMap = [
    'social_instagram' => 'instagram',
    'social_facebook' => 'facebook',
    'social_linkedin' => 'linkedin',
    'social_whatsapp' => 'whatsapp'
];

$social = $existing['social'] ?? [];

// Top-level fields
foreach (['phone_primary', 'phone_secondary', 'whatsapp', 'email', 'address', 'map_embed'] as $field) {
    if (array_key_exists($field, $input)) {
        $existing[$field] = $input[$field];
    }
}

// Social fields - extract from flat input
foreach ($socialFields as $sf) {
    if (array_key_exists($sf, $input)) {
        $social[$socialMap[$sf]] = $input[$sf];
    }
}
$existing['social'] = $social;

$written = file_put_contents($dataFile, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
if ($written === false) {
    echo json_encode(['success' => false, 'message' => 'Failed to write contact data.']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Contact settings saved!']);

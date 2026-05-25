<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, no-store, must-revalidate');

$dataFile = __DIR__ . '/../data/feed.json';
if (!file_exists($dataFile)) {
    echo json_encode([]);
    exit;
}
$content = file_get_contents($dataFile);
echo $content ?: '[]';

<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthenticated access.']);
    exit;
}

// Support GET for fetching single feed post details
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['get'])) {
    $getFile = __DIR__ . '/../data/feed.json';
    $allFeed = [];
    if (file_exists($getFile)) {
        $allFeed = json_decode(file_get_contents($getFile), true) ?? [];
    }
    $getId = intval($_GET['get']);
    $found = null;
    foreach ($allFeed as $f) {
        if (intval($f['id'] ?? 0) === $getId) {
            $found = $f;
            break;
        }
    }
    echo json_encode(['success' => !!$found, 'feed' => $found]);
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

$dataFile = __DIR__ . '/../data/feed.json';

$feed = [];
if (file_exists($dataFile)) {
    $content = file_get_contents($dataFile);
    $parsed = json_decode($content, true);
    if (is_array($parsed)) {
        $feed = $parsed;
    }
}

$hasDelete = isset($input['delete']) && $input['delete'] === true;
$hasId = isset($input['id']) && $input['id'] !== '' && $input['id'] !== null;

if ($hasDelete) {
    // DELETE
    $deleteId = intval($input['id']);
    $feed = array_values(array_filter($feed, function ($f) use ($deleteId) {
        return intval($f['id'] ?? 0) !== $deleteId;
    }));
    foreach ($feed as $idx => &$f) {
        $f['order'] = $idx + 1;
    }
    unset($f);
} elseif ($hasId) {
    // EDIT
    $editId = intval($input['id']);
    $found = false;
    foreach ($feed as &$f) {
        if (intval($f['id'] ?? 0) === $editId) {
            foreach ($input as $key => $value) {
                if ($key !== 'delete' && $key !== 'id') {
                    $f[$key] = $value;
                }
            }
            $found = true;
            break;
        }
    }
    unset($f);
    if (!$found) {
        echo json_encode(['success' => false, 'message' => 'Feed post not found.']);
        exit;
    }
} else {
    // ADD
    $maxId = 0;
    foreach ($feed as $f) {
        if (isset($f['id']) && intval($f['id']) > $maxId) {
            $maxId = intval($f['id']);
        }
    }
    $newPost = $input;
    $newPost['id'] = $maxId + 1;
    if (!isset($newPost['order'])) {
        $newPost['order'] = count($feed) + 1;
    }
    $feed[] = $newPost;
}

$written = file_put_contents($dataFile, json_encode($feed, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
if ($written === false) {
    echo json_encode(['success' => false, 'message' => 'Failed to write feed data.']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Feed updated successfully!', 'feed' => $feed]);

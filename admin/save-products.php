<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthenticated access.']);
    exit;
}

// Support GET for fetching single product details
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['get'])) {
    $getFile = __DIR__ . '/../data/products.json';
    $allProducts = [];
    if (file_exists($getFile)) {
        $allProducts = json_decode(file_get_contents($getFile), true) ?? [];
    }
    $getId = intval($_GET['get']);
    $found = null;
    foreach ($allProducts as $p) {
        if (intval($p['id'] ?? 0) === $getId) {
            $found = $p;
            break;
        }
    }
    echo json_encode(['success' => !!$found, 'product' => $found]);
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

$dataFile = __DIR__ . '/../data/products.json';

$products = [];
if (file_exists($dataFile)) {
    $content = file_get_contents($dataFile);
    $parsed = json_decode($content, true);
    if (is_array($parsed)) {
        $products = $parsed;
    }
}

// Determine action from flat input
$hasDelete = isset($input['delete']) && $input['delete'] === true;
$hasId = isset($input['id']) && $input['id'] !== '' && $input['id'] !== null;

if ($hasDelete) {
    // DELETE
    $deleteId = intval($input['id']);
    $products = array_values(array_filter($products, function ($p) use ($deleteId) {
        return intval($p['id'] ?? 0) !== $deleteId;
    }));
    foreach ($products as $idx => &$p) {
        $p['order'] = $idx + 1;
    }
    unset($p);
} elseif ($hasId) {
    // EDIT
    $editId = intval($input['id']);
    $found = false;
    foreach ($products as &$p) {
        if (intval($p['id'] ?? 0) === $editId) {
            foreach ($input as $key => $value) {
                if ($key !== 'delete' && $key !== 'id') {
                    $p[$key] = $value;
                }
            }
            $found = true;
            break;
        }
    }
    unset($p);
    if (!$found) {
        echo json_encode(['success' => false, 'message' => 'Product not found.']);
        exit;
    }
} else {
    // ADD
    $maxId = 0;
    foreach ($products as $p) {
        if (isset($p['id']) && intval($p['id']) > $maxId) {
            $maxId = intval($p['id']);
        }
    }
    $newProduct = $input;
    $newProduct['id'] = $maxId + 1;
    if (!isset($newProduct['order'])) {
        $newProduct['order'] = count($products) + 1;
    }
    $products[] = $newProduct;
}

$written = file_put_contents($dataFile, json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
if ($written === false) {
    echo json_encode(['success' => false, 'message' => 'Failed to write products data.']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Products updated successfully!', 'products' => $products]);

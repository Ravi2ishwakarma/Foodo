<?php
session_start();
require_once '../config.php';

// Basic admin session check (customize as needed)
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Access denied']);
    exit;
}

// Receive JSON data
$data = json_decode(file_get_contents('php://input'), true);

$name = trim($data['name'] ?? '');
$description = trim($data['description'] ?? '');
$price = floatval($data['price'] ?? 0);
$image = trim($data['image'] ?? '');

if (!$name || !$price || !$image) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO foods (name, description, price, image) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssds", $name, $description, $price, $image);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Food added successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}

$stmt->close();
$conn->close();

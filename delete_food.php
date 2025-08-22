<?php
// delete_food.php
header('Content-Type: application/json');
require_once '../config.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'Missing food ID']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM foods WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Food item deleted']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Delete failed']);
}
$stmt->close();
$conn->close();

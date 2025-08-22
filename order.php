<?php
// order.php
header('Content-Type: application/json');
session_start();
require_once '../config.php';

// Check if user is logged in (this assumes session is set during login)
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$food_id = $_POST['food_id'] ?? null;
$quantity = $_POST['quantity'] ?? 1;
$address = trim($_POST['address'] ?? '');
$location = trim($_POST['location'] ?? '');
$is_subscribed = isset($_POST['is_subscribed']) ? 1 : 0;

// Validate inputs
if (!$food_id || !$address || !$location) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit;
}

// Get food price
$stmt = $conn->prepare("SELECT price FROM foods WHERE id = ?");
$stmt->bind_param("i", $food_id);
$stmt->execute();
$stmt->bind_result($price);
if (!$stmt->fetch()) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid food ID']);
    exit;
}
$stmt->close();

// Calculate total price
$total_price = $price * $quantity;
if ($is_subscribed) {
    $total_price *= 0.9; // Apply 10% discount
}

// Save order
$stmt = $conn->prepare("INSERT INTO orders (user_id, food_id, quantity, is_subscribed, total_price, delivery_address, delivery_location)
                        VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iiidsss", $user_id, $food_id, $quantity, $is_subscribed, $total_price, $address, $location);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Order placed successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}

$stmt->close();
$conn->close();


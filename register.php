<?php
// register.php
header('Content-Type: application/json');
require_once '../config.php';

$data = json_decode(file_get_contents('php://input'), true);

$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if (!$name || !$email || !$password) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
    exit;
}

// Check if user exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Email already registered.']);
    exit;
}
$stmt->close();

// Hash password
$hash = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$stmt = $conn->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hash);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error.']);
}
$stmt->close();
$conn->close();

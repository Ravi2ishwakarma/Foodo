<?php
// login.php
header('Content-Type: application/json');
session_start();
require_once '../config.php';

$data = json_decode(file_get_contents('php://input'), true);

$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(['status' => 'error', 'message' => 'Email and password required.']);
    exit;
}

$stmt = $conn->prepare("SELECT id, name, password_hash FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'User not found.']);
    exit;
}

$stmt->bind_result($id, $name, $hash);
$stmt->fetch();

if (password_verify($password, $hash)) {
    // Save user session
    $_SESSION['user_id'] = $id;
    $_SESSION['user_name'] = $name;
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid password.']);
}

$stmt->close();
$conn->close();

<?php
// foods_list_admin.php
header('Content-Type: application/json');
require_once '../config.php';

$sql = "SELECT * FROM foods ORDER BY id DESC";
$result = $conn->query($sql);

$foods = [];
while ($row = $result->fetch_assoc()) {
    $foods[] = $row;
}

echo json_encode($foods);
$conn->close();

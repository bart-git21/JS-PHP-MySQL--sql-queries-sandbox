<?php
require_once __DIR__ . '/db/connection.php';

$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();
$result = $stmt->fetchAll();

header("Content-Type: application/json;charset=UTF-8");
echo json_encode($result);
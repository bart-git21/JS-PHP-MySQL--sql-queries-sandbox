<?php
// require_once __DIR__ . '/db/connection.php';

$url = $_SERVER["REQUEST_URI"];
$urlComponents = parse_url($_SERVER['REQUEST_URI'],  PHP_URL_PATH);
header("Content-Type: application/json;charset=UTF-8");
echo json_encode(["success"=> $urlComponents]);

// $stmt = $conn->prepare("DELETE * FROM users WHERE id = 4");
// $stmt = $conn->prepare("INSERT INTO users (login, password, role) VALUES ('a', 'b', 'user')");
// $stmt = $conn->prepare('INSERT INTO queries (name, query, user_id) VALUES ("Get visited planets", "SELECT * FROM test_data WHERE first_visited_year IS NOT NULL", 1)');
// $stmt = $conn->prepare('SELECT * FROM queries');
// $stmt = $conn->prepare('UPDATE queries SET user_id = 2 WHERE id = 3');
// $stmt->execute();
// $result = $stmt->fetchAll();

// header("Content-Type: application/json;charset=UTF-8");
// echo json_encode($stmt);
<?php
include "db/connection.php";
$stmt = $conn->prepare('SELECT q.name, q.query, q.user_id, users.login FROM queries AS q LEFT JOIN users ON q.user_id = users.id ORDER BY users.login ASC');
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
header("Content-Type: application/json;charset=UTF-8");
echo json_encode($result);
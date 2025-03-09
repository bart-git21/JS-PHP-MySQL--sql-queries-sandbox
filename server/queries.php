<?php
include "db/connection.php";

$requestMethod = $_SERVER["REQUEST_METHOD"];

switch ($requestMethod) {
    case "GET":
        session_start();
        $userId = $_SESSION['userId'];
        $stmt = $conn->prepare("SELECT * FROM queries WHERE user_id = :userId");
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($result);
        break;
    case "POST":
        break;
    default:
        break;
}

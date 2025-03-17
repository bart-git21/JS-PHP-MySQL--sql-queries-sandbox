<?php
include "../db/connection.php";
$requestMethod = $_SERVER["REQUEST_METHOD"];

switch ($requestMethod) {
    case "GET":
        // read all users
        $stmt = $conn->prepare("SELECT * FROM users");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($result);
        break;
    case "POST":
        // log in. read a user with a specific id
        session_start();
        $json = file_get_contents("php://input");
        // interface $user {
        //     id: number,
        //     login: string,
        //     password: string,
        // }
        $json && $user = json_decode($json, true);
        $_SESSION['userId'] = $userId;

        $stmt = $conn->prepare("SELECT (login) FROM users WHERE id = :id");
        $stmt->bindParam(":id", $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $result['userId'] = $userId;
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($result);
        break;
    default:
        break;
}
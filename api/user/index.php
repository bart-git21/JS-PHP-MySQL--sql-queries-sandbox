<?php
include "../db/connection.php";
$requestMethod = $_SERVER["REQUEST_METHOD"];

switch ($requestMethod) {
    case "POST":
        $json = file_get_contents("php://input");
        // interface $user {
        //     login: string,
        //     password: string,
        // }
        $json && $user = json_decode($json, true);
        $userName = $user["login"];
        $userPass = $user["password"];
        $hashedPassword = password_hash($userPass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (login, password) VALUES (:login, :password)");
        $stmt->bindParam(":login", $userName);
        $stmt->bindParam(":password", $userPass);
        $stmt->execute();

        $lastInsertedId = $pdo->lastInsertId();
        header("Content-Type: application/json");
        http_response_code(201);
        echo json_encode([
            "id" => $lastInsertedId,
            "login" => $userName
        ]);
        break;
    default:
        break;
}
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
        // log in.
        session_start();
        $json = file_get_contents("php://input");
        // interface $user {
        //     id: number,
        //     login: string,
        //     password: string,
        // }
        $json && $user = json_decode($json, true);
        $name = $user['login'];
        $pass = $user['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE login = :login");
        $stmt->bindParam(":login", $name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // $hashedPassword = password_hash($result["password"], PASSWORD_DEFAULT);

        if (password_verify($pass, $result["password"])) {
            $_SESSION['userId'] = $result["id"];
            unset($result["password"]);
            header("Content-Type: application/json;charset=UTF-8");
            echo json_encode([
                "userId" => $result["id"],
                "login" => $result["login"]
            ]);
        } else {
            http_response_code(401);
            echo json_encode(["error" => "Invalid login credentials"]);
        }

        break;
    default:
        break;
}
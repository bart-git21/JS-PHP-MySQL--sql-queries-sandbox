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
        //     login: string,
        //     password: string,
        // }
        $json && $user = json_decode($json, true);
        $name = strip_tags($user['login']);
        $pass = strip_tags($user['password']);

        $stmt = $conn->prepare("SELECT * FROM users WHERE login = :login");
        $stmt->bindParam(":login", $name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashedPassword = password_hash($result["password"], PASSWORD_DEFAULT);

        // if (password_verify($pass, $result["password"])) {
        if (password_verify($pass, $hashedPassword)) {
            session_regenerate_id(true);
            $_SESSION['userId'] = $result["id"];
            unset($result["password"]);
            header("Content-Type: application/json;charset=UTF-8");
            echo json_encode([
                "userId" => $result["id"],
                "login" => $result["login"]
            ]);
        } else {
            http_response_code(401);
        }
        break;
    case "DELETE":
        session_start();
        session_unset();
        session_destroy();
        // header("Location: login.html"); // Redirect to login page
        http_response_code(200);
        echo json_encode(["success" => "You are successfully logged out"]);
        break;
    default:
        break;
}
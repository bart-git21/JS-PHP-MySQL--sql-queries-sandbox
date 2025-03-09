<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];

switch ($requestMethod) {
    case "GET":
        break;
    case "POST":
        session_start();
        $json = file_get_contents("php://input");
        $json && $userId = json_decode($json, true)['userId'];
        $_SESSION['userId'] = $userId;
        header("Content-Type: application/json");
        echo json_encode(["success" => "You are now logged in."]);
        break;
    default:
        break;
}
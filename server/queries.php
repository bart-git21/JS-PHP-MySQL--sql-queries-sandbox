<?php
include "db/connection.php";

$requestMethod = $_SERVER["REQUEST_METHOD"];

switch ($requestMethod) {
    case "GET":
        session_start();
        $userId = $_SESSION['userId'];
        if ($userId === "1") {
            $stmt = $conn->prepare('SELECT q.name, q.query, q.user_id, users.login FROM queries AS q LEFT JOIN users ON q.user_id = users.id ORDER BY users.login ASC');
        } else {
            $stmt = $conn->prepare('SELECT q.*, users.login FROM queries AS q LEFT JOIN users ON q.user_id = users.id WHERE user_id = :userId');
            $stmt->bindParam(":userId", $userId);
        }
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($result);
        break;
    case "POST":
        if (isset($_GET["id"])) {
            $data = json_decode(file_get_contents('php://input'));
            $id = $data->id;
            $stmt = $conn->prepare("SELECT * FROM queries WHERE id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $sql = $result['query'];
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $queryResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

            header("Content-Type: application/json");
            echo json_encode([
                "query" => $result,
                "queryResult" => $queryResult
            ]);
        } else {
            $data = json_decode(file_get_contents('php://input'));
            $stmt = $conn->prepare("INSERT INTO queries (name, query, user_id) VALUES (:name, :query, :userID)");
            $stmt->bindParam(":name", $data->name);
            $stmt->bindParam(":query", $data->query);
            $stmt->bindParam(":userID", $data->userID);
            $stmt->execute();
            $lastInsertId = $conn->lastInsertId();

            header("Content-Type: application/json");
            echo json_encode(["newQueryId" => $lastInsertId]);
        }
        break;
    case "PUT":
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
        $stmt = $conn->prepare("UPDATE queries SET name = :name, query = :query WHERE id = :id");
        $stmt->bindParam(":id", $data['id']);
        $stmt->bindParam(":name", $data['name']);
        $stmt->bindParam(":query", $data['query']);
        $stmt->execute();

        header("Content-Type: application/json");
        echo json_encode(["success" => "query successfully updated"]);
        break;
    default:
        break;
}

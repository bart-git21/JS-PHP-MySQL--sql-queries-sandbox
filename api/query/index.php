<?php
include "../db/connection.php";

$requestMethod = $_SERVER["REQUEST_METHOD"];

switch ($requestMethod) {
    case "GET":
        if (isset($_GET["id"])) {
            // read the query with a specific id
            $stmt = $conn->prepare("SELECT * FROM queries WHERE id = :id");
            $stmt->bindParam(":id", $_GET["id"]);
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
            // read all queries from the database, ordered by user login
            session_start();
            $userId = $_SESSION['userId'];
            if ($userId === 1) {
                $stmt = $conn->prepare('SELECT q.*, users.login FROM queries AS q LEFT JOIN users ON q.user_id = users.id ORDER BY users.login ASC');
            } else {
                $stmt = $conn->prepare('SELECT q.*, users.login FROM queries AS q LEFT JOIN users ON q.user_id = users.id WHERE user_id = :userId');
                $stmt->bindParam(":userId", $userId);
            }
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header("Content-Type: application/json;charset=UTF-8");
            // interface Result {
            //     login: string, // user login
            //     user_id: number, // user id
            //     id: number, // query id
            //     name: string, // query name
            //     query: string, // query text
            // }
            echo json_encode($result);
        }
        break;
    case "POST":
        // create a new query
        $data = json_decode(file_get_contents('php://input'));
        $name = strip_tags($data->name);
        $query = strip_tags($data->query);

        $stmt = $conn->prepare("INSERT INTO queries (name, query, user_id) VALUES (:name, :query, :userID)");
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":query", $query);
        $stmt->bindParam(":userID", $data->userID);
        $stmt->execute();
        $lastInsertId = $conn->lastInsertId();

        header("Content-Type: application/json");
        echo json_encode(["newQueryId" => $lastInsertId]);
        break;
    case "PUT":
        if (isset($_GET["id"])) {
            // update a query with a specific id
            $json = file_get_contents("php://input");
            $data = json_decode($json, true);
            $name = strip_tags($data["name"]);
            $query = strip_tags($data["query"]);
    
            $stmt = $conn->prepare("UPDATE queries SET name = :name, query = :query WHERE id = :id");
            $stmt->bindParam(":id", $data['id']);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":query", $query);
            $stmt->execute();

            header("Content-Type: application/json");
            echo json_encode(["success" => "query successfully updated"]);
        }
        break;
    default:
        break;
}

<?php
// access the environment variables
require_once __DIR__ . '/../../vendor/autoload.php';
(new App\Controller\DotEnvEnvironment)->load(__DIR__ . '/../../');

// connect to the database
try {
    $host = $_ENV['HOST'];
    $db = $_ENV['DATABASE'];
    $user = $_ENV['USER'];
    $password = $_ENV['PASSWORD'];
    
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["Connection error: " => $e->getMessage()]);
}
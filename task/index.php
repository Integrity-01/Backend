<?php
header("Content-Type: application/json");
require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Fetch all tasks
        $stmt = $conn->query("SELECT * FROM tasks");
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tasks);
        break;

    case 'POST':
        // Create a new task
        $data = json_decode(file_get_contents("php://input"), true);
        $title = $data['title'];
        $description = $data['description'];

        $stmt = $conn->prepare("INSERT INTO tasks (title, description) VALUES (:title, :description)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        echo json_encode(['message' => 'Task created successfully']);
        break;

    case 'PUT':
        // Update a task
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'];
        $title = $data['title'];
        $description = $data['description'];

        $stmt = $conn->prepare("UPDATE tasks SET title = :title, description = :description WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        echo json_encode(['message' => 'Task updated successfully']);
        break;

    case 'DELETE':
        // Delete a task
        $id = $_GET['id'];
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo json_encode(['message' => 'Task deleted successfully']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method not allowed']);
        break;
}
?>
<?php
include('../conn/db_connect.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the query to fetch the FAQ by ID
    $stmt = $conn->prepare("SELECT * FROM faq WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the FAQ exists and return it as JSON
    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'FAQ not found']);
    }
}
?>

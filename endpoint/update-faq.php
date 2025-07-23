<?php
include('../conn/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    // Prepare the update query
    $stmt = $conn->prepare("UPDATE faq SET question = ?, answer = ? WHERE id = ?");
    $stmt->bind_param("ssi", $question, $answer, $id);
    
    if ($stmt->execute()) {
        header("Location: ../A_FAQ.php");
    } else {
        header("Location: ../A_FAQ.php");
    }
}
?>

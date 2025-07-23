<?php
include('../conn/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    // Prepare the insert query
    $stmt = $conn->prepare("INSERT INTO faq (question, answer) VALUES (?, ?)");
    $stmt->bind_param("ss", $question, $answer);

    if ($stmt->execute()) {
        header("Location: ../A_FAQ.php");
    } else {
        header("Location: ../A_FAQ.php");
    }
}
?>

<?php

session_start();

include 'functions.php';

$question_id = $_GET['id'];

if (isset($question_id)) {

    $pdo = getDatabaseConnection();

    $statement = "SET FOREIGN_KEY_CHECKS = 0";
    $pdo->query($statement);

    $statement = "DELETE FROM questions WHERE question_id=$question_id";
    $pdo->query($statement);

    $statement = "DELETE FROM answers WHERE question_id=$question_id";
    $pdo->query($statement);

    $statement = "SET FOREIGN_KEY_CHECKS = 1";
    $pdo->query($statement);

    $_SESSION['deleted_question'] = $question_id;

    header("Location: questions.php?sort=new");
    exit;
}

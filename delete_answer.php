<?php

session_start();

include 'functions.php';

$answer_id = $_GET['id'];
$question_id = $_GET['q'];

if (isset($answer_id)) {

    $pdo = getDatabaseConnection();

    $sql = "DELETE FROM answers WHERE answer_id=$answer_id";
    $pdo->query($sql);

    $_SESSION["deleted_answer"] = $answer_id;

    header("Location: question.php?id=$question_id");
    exit;
}

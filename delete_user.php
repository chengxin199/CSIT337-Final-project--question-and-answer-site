<?php

session_start();

include 'functions.php';

$user_id = $_GET['id'];

if (isset($user_id)) {

    $pdo = getDatabaseConnection();

    $row = $pdo->query("SELECT username FROM users WHERE user_id = $user_id")->fetch();
    $username = $row['username'];

    // query to delete the user itself
    $delete_user = "DELETE FROM users WHERE user_id = $user_id";
    $pdo->query($delete_user);

    // queries to delete that user's questions and answers
    $delete_questions = "DELETE FROM questions WHERE username = '$username'";
    $pdo->query($delete_questions);
    $delete_answers = "DELETE FROM answers WHERE username = '$username'";
    $pdo->query($delete_answers);

    $_SESSION['deleted_user'] = $username;

    header('Location: questions.php?sort=new');
}

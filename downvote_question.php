<?php

session_start();

$question_id = $_GET['qid'];
$user_id = $_SESSION['user_id'];

if (isset($question_id)) {

    include 'functions.php';
    $pdo = getDatabaseConnection();

    $statement = $pdo->query("SELECT vote FROM question_votes WHERE voter=$user_id AND question_id=$question_id");
    $num_rows = $statement->rowCount();
    $row = $statement->fetch();

    if ($num_rows === 0) {
        $statement = "INSERT INTO question_votes (question_id, voter, vote) VALUES ($question_id, $user_id, -1)";
        $pdo->query($statement);

        $statement = "SELECT votes FROM questions WHERE question_id=$question_id";
        $result = $pdo->query($statement);
        $row2 = $result->fetch();
        $votes = $row2['votes'];

        echo 'fewfwefew';

        $newVotes = $votes - 1;

        $statement = "UPDATE questions set votes = $newVotes WHERE question_id=$question_id";
        $pdo->query($statement);

        header("Location: question.php?id=$question_id");
        exit;
    }

    if ($row['vote'] === -1) {

        $statement = "SELECT votes FROM questions WHERE question_id=$question_id";
        $result = $pdo->query($statement);
        $row2 = $result->fetch();
        $votes = $row2['votes'];

        $newVotes = $votes + 1;

        $statement = "DELETE FROM question_votes WHERE voter=$user_id AND question_id = $question_id";
        $pdo->query($statement);

        $statement = "UPDATE questions set votes = $newVotes WHERE question_id=$question_id";
        $pdo->query($statement);

        header("Location: question.php?id=$question_id");
        exit;
    }

    if ($row['vote'] == 1) {

        $statement = "SELECT votes FROM questions WHERE question_id=$question_id";
        $result = $pdo->query($statement);
        $row2 = $result->fetch();
        $votes = $row2['votes'];

        $newVotes = $votes - 2;

        $statement = "UPDATE question_votes SET vote = -1 WHERE voter=$user_id AND question_id = $question_id";
        $pdo->query($statement);

            $statement = "UPDATE questions set votes = $newVotes WHERE question_id=$question_id";
            $pdo->query($statement);

        header("Location: question.php?id=$question_id");
        exit;
    }

}

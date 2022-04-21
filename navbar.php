<?php

@session_start();

$username = $_SESSION['username'];

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-5">
  <div class="container-fluid">

    <span class="navbar-brand" href="#">Jupiter</span>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item mx-2">
          <a class="nav-link" href="profile.php">My profile (<?= $username ?>)</a>
        </li>
        <li class="nav-item mx-2">
          <a class="nav-link" href="questions.php?sort=new">Questions</a>
        </li>
        <li class="nav-item mx-2">
          <a class="nav-link" href="ask.php">Ask a question</a>
        </li>
      </ul>
    </div>
    <a class="link-light" href="logout.php">Sign out</a>
  </div>
</nav>

<?php
    require_once('../config/config.php');

    $stmt = $pdo->prepare("DELETE FROM posts WHERE id=". $_GET['id']);

    $stmt->execute();

    header("location: index.php");
?>
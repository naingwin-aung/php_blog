<?php
    require_once('../config/config.php');

    $stmt = $pdo->prepare("DELETE FROM users WHERE id=". $_GET['id']);

    $stmt->execute();

    header("location: user.php");
?>
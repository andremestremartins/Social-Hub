<?php

require_once "../config/database.php";
require_once "../config/session.php";

/** @var PDO $pdo */

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$pesquisa = trim($_POST["pesquisa"]);

$stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
$stmt->execute([":username" => $pesquisa]);

if ($user = $stmt->fetch()) {
    header("Location: ../profile.php?id=" . $user["id"]);
    exit();
} else {
    header("Location: ../index.php?q=" . urlencode($pesquisa));
    exit();
}
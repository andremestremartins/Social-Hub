<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

/** @var PDO $pdo */

if (!isset($_GET["id"])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$post_id = (int)$_GET["id"];

$stmt = $pdo->prepare("SELECT id FROM likes WHERE user_id = :user_id AND post_id = :post_id");
$stmt->execute([":user_id" => $user_id, ":post_id" => $post_id]);

if ($stmt->fetch() === false) {

    $insert = $pdo->prepare("INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)");
    $insert->execute([":user_id" => $user_id, ":post_id" => $post_id]);

} else {

    $delete = $pdo->prepare("DELETE FROM likes WHERE user_id = :user_id AND post_id = :post_id");
    $delete->execute([":user_id" => $user_id, ":post_id" => $post_id]);
}

header("Location: " . $_SERVER["HTTP_REFERER"]);
exit();
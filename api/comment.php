<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

/** @var PDO $pdo */

if (!isset($_POST["post_id"]) || !isset($_POST["content"])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$post_id = (int)$_POST["post_id"];
$content = trim($_POST["content"]);

if (empty($content)) {
    header("Location: ../post.php?id=" . $post_id);
    exit();
}

$stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (:post_id, :user_id, :content)");

if ($stmt->execute([
    ":post_id" => $post_id,
    ":user_id" => $user_id,
    ":content" => $content,
])) {
    header("Location: ../post.php?id=" . $post_id);
    exit();
} else {
    echo "Erro ao adicionar comentário.";
}
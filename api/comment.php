<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

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

$content = mysqli_real_escape_string($conn, $content);

$sql = "INSERT INTO comments (post_id, user_id, content) VALUES ('$post_id', '$user_id', '$content')";

if (mysqli_query($conn, $sql)) {
    header("Location: ../post.php?id=" . $post_id);
    exit();
} else {
    echo "Erro ao adicionar comentário.";
}


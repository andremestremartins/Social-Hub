<?php

require_once "../config/database.php";
require_once "../config/session.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"]; 
$content = trim($_POST["content"]);

$sql = "INSERT INTO posts(user_id, content) VALUES ('$user_id', '$content')";

if (mysqli_query($conn, $sql)) {
    header("Location: ../profile.php?id=" . $user_id);
    exit();
} else {
    echo "Erro ao publicar.";
}

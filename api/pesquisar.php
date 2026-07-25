<?php

require_once "../config/database.php";
require_once "../config/session.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$pesquisa = trim($_POST["pesquisa"]);

$sql = "SELECT id FROM users WHERE username = '$pesquisa' LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($user = mysqli_fetch_assoc($result)) {
    header("Location: ../profile.php?id=" . $user["id"]);
    exit();
} else {
    header("Location: ../index.php?q=" . urlencode($pesquisa));
    exit();
}

<?php

require_once "../config/database.php";
require_once "../config/session.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$pesquisa = trim($_POST["pesquisa"]);

$sql = "SELECT id FROM users WHERE username = ? LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $pesquisa);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {
    header("Location: ../profile.php?id=" . $user["id"]);
    exit();
} else {
    header("Location: ../index.php?q=" . urlencode($pesquisa));
    exit();
}
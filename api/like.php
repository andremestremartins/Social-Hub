<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

if (!isset($_GET["id"])) {
    header("Location: ../home.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$post_id = (int)$_GET["id"];

$sql = "SELECT * FROM likes
        WHERE user_id = '$user_id'
        AND post_id = '$post_id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {

    mysqli_query($conn, "INSERT INTO likes (user_id, post_id)
                         VALUES ('$user_id', '$post_id')");

} else {

    mysqli_query($conn, "DELETE FROM likes
                         WHERE user_id = '$user_id'
                         AND post_id = '$post_id'");
}

header("Location: " . $_SERVER["HTTP_REFERER"]);
exit();
<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

if (!isset($_GET["id"])) {
    header("Location: ../index.php");
    exit();
}

$userid = $_SESSION["user_id"];
$id = (int)$_GET["id"];

if ($userid == $id) {
    header("Location: ../profile.php?id=$userid");
    exit();
}

$sql = "SELECT * FROM followers
        WHERE follower_id='$userid'
        AND following_id='$id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {

    mysqli_query($conn, "INSERT INTO followers (follower_id, following_id) VALUES ('$userid', '$id')");

} else {

    mysqli_query($conn, "DELETE FROM followers WHERE follower_id='$userid' AND following_id='$id'");
}

header("Location: ../profile.php?id=$id");
exit();
<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

/** @var PDO $pdo */

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

$stmt = $pdo->prepare("SELECT id FROM followers WHERE follower_id = :userid AND following_id = :id");
$stmt->execute([":userid" => $userid, ":id" => $id]);

if ($stmt->fetch() === false) {

    $insert = $pdo->prepare("INSERT INTO followers (follower_id, following_id) VALUES (:userid, :id)");
    $insert->execute([":userid" => $userid, ":id" => $id]);

} else {

    $delete = $pdo->prepare("DELETE FROM followers WHERE follower_id = :userid AND following_id = :id");
    $delete->execute([":userid" => $userid, ":id" => $id]);
}

header("Location: ../profile.php?id=$id");
exit();
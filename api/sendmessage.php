<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

/** @var PDO $pdo */

$senderid = (int)$_POST["sender_id"];
$receiverid = (int)$_POST["receiver_id"];
$msg = trim($_POST["message"]);

$stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message)
                       VALUES (:sender_id, :receiver_id, :message)");
$stmt->execute([
    ":sender_id" => $senderid,
    ":receiver_id" => $receiverid,
    ":message" => $msg,
]);

header("Location: ../chatpage.php?id=$receiverid");
exit();
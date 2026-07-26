<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

$senderid = (int)$_POST["sender_id"];
$receiverid = (int)$_POST["receiver_id"];
$msg = mysqli_real_escape_string($conn, $_POST["message"]);

mysqli_query($conn, "
    INSERT INTO messages (sender_id, receiver_id, message)
    VALUES ($senderid, $receiverid, '$msg')
");

header("Location: ../chatpage.php?id=$receiverid");
exit();
<?php

require_once "includes/auth.php";
require_once "config/database.php";

/** @var PDO $pdo */

$id = $_SESSION["user_id"];

$sql = "SELECT *
FROM messages
WHERE sender_id = :sid OR receiver_id = :rid
ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([":sid" => $id, ":rid" => $id]);

$conversas = [];

foreach ($stmt->fetchAll() as $row) {

    if ($row["sender_id"] == $id) {
        $outro = $row["receiver_id"];
    } else {
        $outro = $row["sender_id"];
    }

    if (!in_array($outro, $conversas)) {
        $conversas[] = $outro;
    }
}

require_once "includes/html.php";
include "includes/header.php";
?>

<div class="containerLastUsers">

<?php if (empty($conversas)): ?>

    <div class="emptychatbox">
        <h2>Ainda não tens conversas.</h2>
        <h3>Mande uma mensagem apartir do perfil de uma pessoa</h3>
    </div>

<?php else: ?>

<?php foreach ($conversas as $userId):

    $stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmtUser->execute([":id" => $userId]);
    $user = $stmtUser->fetch();

?>

<a href="chatpage.php?id=<?php echo $user["id"]; ?>" class="chat-link">

    <div class="usercard">

        <div class="user-header">

            <div class="user-avatar">
                <?php echo mb_strtoupper($user["username"], "UTF-8")[0]; ?>
            </div>

            <div class="username">
                <?php echo htmlspecialchars($user["username"]); ?>
            </div>

        </div>

    </div>

</a>

<?php endforeach; ?>

<?php endif; ?>

</div>

</body>
</html>
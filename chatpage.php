<?php


require_once "includes/auth.php";
require_once "config/database.php";

if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET["id"];
$me = $_SESSION["user_id"];

    $sql = "SELECT *
    FROM messages
    WHERE
    (sender_id = $me AND receiver_id = $id)
    OR
    (sender_id = $id AND receiver_id = $me)
    ORDER BY created_at ASC";

    $query = mysqli_query($conn, $sql);
    require_once "includes/html.php";
    include "includes/header.php";
?>

<div class="chatpage">
    <div class="msgzone">
        <?php while ($msg = mysqli_fetch_assoc($query)): ?>

        <?php if ($msg["sender_id"] == $me): ?>

        <div class="my-message">
            <?= htmlspecialchars($msg["message"]) ?>
        </div>

        <?php else: ?>

        <div class="their-message">
            <?= htmlspecialchars($msg["message"]) ?>
        </div>

        <?php endif; ?>

        <?php endwhile; ?>
    </div>
    <form action="api/sendmessage.php" method="POST">

        <input type="hidden" name="sender_id" value="<?= $me ?>">
        <input type="hidden" name="receiver_id" value="<?= $id ?>">

        <input type="text" name="message" placeholder="Escreve uma mensagem...">

        <button type="submit">Enviar</button>

    </form>
</div>

</body>

</html>
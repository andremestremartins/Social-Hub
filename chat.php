<?php

require_once "includes/auth.php";
require_once "config/database.php";

$id = $_SESSION["user_id"]; 

$sql = "SELECT *
FROM messages
WHERE sender_id = $id OR receiver_id = $id
ORDER BY created_at DESC";

$query = mysqli_query($conn, $sql);

$conversas = [];

while ($row = mysqli_fetch_assoc($query)) {

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

    $sqlUser = "SELECT * FROM users WHERE id = $userId";
    $resultUser = mysqli_query($conn, $sqlUser);
    $user = mysqli_fetch_assoc($resultUser);

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
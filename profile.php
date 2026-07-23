<?php

require_once "includes/auth.php";
require_once "config/database.php";

if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET["id"];

$sql = "SELECT * FROM users WHERE id='$id'";
$query = mysqli_query($conn, $sql);

if (mysqli_num_rows($query) == 0) {
    die("Utilizador não encontrado.");
}

$user = mysqli_fetch_assoc($query);

$sqlPosts = "SELECT posts.*,
    (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) AS total_likes
FROM posts
WHERE user_id='$id'
ORDER BY created_at DESC";

$posts = mysqli_query($conn, $sqlPosts);

$totalPosts = mysqli_num_rows($posts);

$sqlSeguidores = "SELECT *
FROM followers
WHERE following_id='$id'";

$seguidores = mysqli_num_rows(mysqli_query($conn, $sqlSeguidores));

$sqlSeguindo = "SELECT *
FROM followers
WHERE follower_id='$id'";

$seguindo = mysqli_num_rows(mysqli_query($conn, $sqlSeguindo));


$segue = false;

if ($_SESSION["user_id"] != $id) {

    $userid = $_SESSION["user_id"];

    $sql = "SELECT * FROM followers
    WHERE follower_id='$userid'
    AND following_id='$id'";

    $resultado = mysqli_query($conn, $sql);

    $jaSegue = mysqli_num_rows($resultado) > 0;
}

$avatar = mb_strtoupper($user["username"], "UTF-8");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title><?php echo htmlspecialchars($user["username"]); ?></title>

    <link rel="stylesheet" href="assets/css/perfil.css">
</head>

<body>

    <?php include "includes/header.php"; ?>

    <div class="containerprofile">

        <div class="profileheader">

            <div class="profileavatar">

                <?php echo $avatar[0]; ?>

            </div>

            <div class="profileinfo">

                <div class="profiletop">

                    <h2><?php echo htmlspecialchars($user["username"]); ?></h2>

                    <?php if ($_SESSION["user_id"] == $id) { ?>

                        <a href="edit_profile.php" class="editprofile">

                            Editar Perfil

                        </a>

                    <?php } else { ?>

                <?php if ($jaSegue) { ?>

                    <a href="api/follow.php?id=<?php echo $id; ?>" class="editprofile">
                        Deixar de seguir
                    </a>

                <?php } else { ?>

                    <a href="api/follow.php?id=<?php echo $id; ?>" class="editprofile">
                        Seguir
                    </a>

                <?php } ?>

            <?php } ?>

                </div>

                <p class="username">

                    @<?php echo htmlspecialchars($user["username"]); ?>

                </p>

                <p class="bio">

                    <?php echo htmlspecialchars($user["bio"] ?? ""); ?>

                </p>

                <div class="profilestats">

                    <div>

                        <span><?php echo $totalPosts; ?></span>

                        <p>Publicações</p>

                    </div>

                    <div>

                        <span><?php echo $seguidores; ?></span>

                        <p>Seguidores</p>

                    </div>

                    <div>

                        <span><?php echo $seguindo; ?></span>

                        <p>A seguir</p>

                    </div>

                </div>

            </div>

        </div>

        <div class="profileposts">

            <h3>Publicações</h3>

            <div class="postsgrid">
                <?php if ($totalPosts === 0) { ?>
                    <div class="empty-posts">Ainda não há publicações.</div>
                <?php } ?>
                <?php while ($post = mysqli_fetch_assoc($posts)) { ?>

                    <div class="postcard">

                        <?php if (!empty($post["image"])) { ?>

                            <img class="post-img" src="<?php echo htmlspecialchars($post["image"]); ?>" alt="Post">

                        <?php } ?>

                        <div class="post-body">

                            <p><?php echo htmlspecialchars($post["content"]); ?></p>

                            <div class="post-footer">

                                <span class="post-time"><?php echo date("d/m/Y H:i", strtotime($post["created_at"])); ?></span>

                                <span class="post-likes">

                                    <span class="heart">&#9829;</span> <?php echo $post["total_likes"]; ?>

                                </span>

                            </div>

                        </div>

                    </div>

                <?php } ?>

            </div>

        </div>

    </div>

</body>

</html>
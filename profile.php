<?php

require_once "includes/auth.php";
require_once "config/database.php";

if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET["id"];
$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM users WHERE id='$id'";
$query = mysqli_query($conn, $sql);

if (mysqli_num_rows($query) == 0) {
    die("Utilizador não encontrado.");
}

$user = mysqli_fetch_assoc($query);

$sqlPosts = "
SELECT
    posts.*,

    COUNT(DISTINCT likes.id) AS total_likes,
    COUNT(DISTINCT comments.id) AS total_comments,

    EXISTS(
        SELECT 1
        FROM likes l2
        WHERE l2.post_id = posts.id
        AND l2.user_id = '$user_id'
    ) AS liked

FROM posts

LEFT JOIN likes
ON likes.post_id = posts.id

LEFT JOIN comments
ON comments.post_id = posts.id

WHERE posts.user_id = '$id'

GROUP BY posts.id

ORDER BY posts.created_at DESC
";

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

if ($user_id != $id) {

    $sql = "SELECT *
            FROM followers
            WHERE follower_id='$user_id'
            AND following_id='$id'";

    $resultado = mysqli_query($conn, $sql);

    $jaSegue = mysqli_num_rows($resultado) > 0;
}

$avatar = mb_strtoupper($user["username"], "UTF-8");
?>
<?php $pageTitle = htmlspecialchars($user["username"]); require_once "includes/html.php"; ?>

<?php include "includes/header.php"; ?>

<div class="containerprofile">

    <div class="profileheader">

        <div class="profileavatar">

            <?php echo $avatar[0]; ?> 

        </div>

        <div class="profileinfo">

            <div class="profiletop">

                <h2><?php echo htmlspecialchars($user["username"]); ?></h2>

                <?php if ($user_id == $id) { ?>

                    <a href="edit_profile.php" class="editprofile">
                        Editar Perfil
                    </a>

                <?php } else { ?>

                    <?php if ($jaSegue) { ?>
                    <div style="display: flex; gap:10px;">
                        
                        <a href="chatpage.php?id=<?php echo $user["id"]; ?>" class="editprofile">
                            Mandar Mensagem
                        </a>
                        <a href="api/follow.php?id=<?php echo $id; ?>" class="editprofile">
                            Deixar de seguir
                        </a>

                        
                    </div>
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

                <div class="empty-posts">
                    Ainda não há publicações.
                </div>

            <?php } ?>

            <?php while ($post = mysqli_fetch_assoc($posts)) { ?>


                        <div class="postcard" onclick="window.location.href='post.php?id=<?php echo $post['id']; ?>'">

                            <div class="post-body">

                                <p><?php echo nl2br(htmlspecialchars($post["content"])); ?></p>

                                <div class="post-footer">

                                    <span class="post-time">
                                        <?php echo date("d/m/Y H:i", strtotime($post["created_at"])); ?>
                                    </span>

                                    <a class="post-likes"
                                    href="post.php?id=<?php echo $post['id']; ?>"
                                    onclick="event.stopPropagation();">

                                        <button type="button">

                                            <i class="fa-solid fa-comment"></i>

                                            <?php echo $post["total_comments"]; ?>

                                        </button>

                                    </a>

                                    <a class="post-likes"
                                    href="api/like.php?id=<?php echo $post["id"]; ?>"
                                    onclick="event.stopPropagation();">

                                        <button
                                            type="button"
                                            class="<?php echo $post["liked"] ? "active" : ""; ?>">

                                            <i class="fa-solid fa-heart"></i>

                                            <?php echo $post["total_likes"]; ?>

                                        </button>

                                    </a>

                                </div>

                            </div>

                        </div>


                <?php } ?>

        </div>

    </div>

</div>
</body>

</html>
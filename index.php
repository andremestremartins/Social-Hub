<?php

require_once "includes/auth.php";
require_once "config/database.php";

$user_id = $_SESSION["user_id"];

$sql = "
SELECT
    posts.*,
    users.username,

    COUNT(DISTINCT likes.id) AS total_likes,
    COUNT(DISTINCT comments.id) AS total_comments,

    EXISTS(
        SELECT 1
        FROM likes l2
        WHERE l2.post_id = posts.id
        AND l2.user_id = '$user_id'
    ) AS liked

FROM posts

INNER JOIN users
ON posts.user_id = users.id

INNER JOIN followers
ON followers.following_id = users.id

LEFT JOIN likes
ON likes.post_id = posts.id

LEFT JOIN comments
ON comments.post_id = posts.id

WHERE followers.follower_id = '$user_id'

GROUP BY posts.id

ORDER BY posts.created_at DESC
";

$query = mysqli_query($conn, $sql);

$sqlPosts = "
SELECT *
FROM posts
WHERE user_id = '$user_id'
";

$totalPosts = mysqli_num_rows(mysqli_query($conn, $sqlPosts));

$sqlSeguidores = "
SELECT *
FROM followers
WHERE following_id = '$user_id'
";

$seguidores = mysqli_num_rows(mysqli_query($conn, $sqlSeguidores));

$sqlSeguindo = "
SELECT *
FROM followers
WHERE follower_id = '$user_id'
";

$seguindo = mysqli_num_rows(mysqli_query($conn, $sqlSeguindo));

?>
<?php $pageTitle = "SocialHub"; require_once "includes/html.php"; ?>
    <div class="home"> <?php include "includes/header.php" ?>
        <div class="homecontainer"> <!-- MAIN ZONE - PUBLICACOES -->
            <div class="mainzone">
                <?php

                if (mysqli_num_rows($query) == 0) {

                ?>

                    <div class="empty-feed">

                        <h2>Ainda não segues ninguém.</h2>

                        <p>Segue alguém para veres publicações.</p>

                    </div>

                <?php

                } else {

                    while ($publicacoes = mysqli_fetch_assoc($query)) {

                    ?>

                        <div class="post" onclick="window.location.href='post.php?id=<?php echo $publicacoes['id']; ?>'">

                            <div class="post-header">

                                <div class="post-avatar">

                                    <?php echo mb_strtoupper($publicacoes["username"], "UTF-8")[0]; ?>

                                </div>

                                <div class="post-user-info">

                                    <div class="post-username">

                                        <?php echo htmlspecialchars($publicacoes["username"]); ?>

                                    </div>

                                    <div class="post-timestamp">

                                        <?php echo $publicacoes["created_at"]; ?>

                                    </div>
                                
                                </div>

                            </div>

                            <div class="post-content">

                                <?php echo nl2br(htmlspecialchars($publicacoes["content"])); ?>

                            </div>
                            <div class="post-actions">

                                <a href="api/like.php?id=<?php echo $publicacoes["id"]; ?>"
                                   onclick="event.stopPropagation();">

                                    <button class="<?php echo $publicacoes["liked"] ? "active" : ""; ?>">

                                        <i class="fa-solid fa-heart"></i>

                                        <?php echo $publicacoes["total_likes"]; ?>

                                    </button>

                                </a>

                                <a href="post.php?id=<?php echo $publicacoes["id"]; ?>"
                                   onclick="event.stopPropagation();">

                                    <button>

                                        <i class="fa-solid fa-comment"></i>

                                        <?php echo $publicacoes["total_comments"]; ?>

                                    </button>

                                </a>

                            </div>

                        </div>

                <?php

                    }
                }

                ?>




            </div>
            <!-- RIGHT BAR -->
            <div class="rightbar"> <?php $ftperfil = mb_strtoupper($_SESSION["username"], 'UTF-8') ?> <!-- PERFIL -->
                <div class="rightbar-card">
                    <h3>O meu perfil</h3>
                    <div class="profile-info">
                        <div class="profile-header">
                            <div class="profile-avatar"><?php echo $ftperfil[0] ?></div>
                            <h4><?php echo $_SESSION["username"] ?? "Utilizador"; ?></h4>
                        </div>
                        <div class="profile-stats">
                            <div>
                                <div class="num"><?php echo $totalPosts; ?></div>
                                <div class="label">Publicações</div>
                            </div>
                            <div>
                                <div class="num"><?php echo $seguidores; ?></div>
                                <div class="label">Seguidores</div>
                            </div>
                            <div>
                                <div class="num"><?php echo $seguindo; ?></div>
                                <div class="label">A seguir</div>
                            </div>
                        </div>
                    </div>
                </div>
                

            </div>
        </div>

</body>

</html>
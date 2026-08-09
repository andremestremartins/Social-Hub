<?php

require_once "includes/auth.php";
require_once "config/database.php";

/** @var PDO $pdo */

$user_id = $_SESSION["user_id"];

$sql = "
SELECT posts.*, users.username
FROM posts

INNER JOIN users
ON posts.user_id = users.id

INNER JOIN followers
ON followers.following_id = users.id

WHERE followers.follower_id = :user_id

ORDER BY posts.created_at DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([":user_id" => $user_id]);
$publicacoes_list = $stmt->fetchAll();

$stmtPosts = $pdo->prepare("SELECT COUNT(*) AS total FROM posts WHERE user_id = :user_id");
$stmtPosts->execute([":user_id" => $user_id]);
$totalPosts = $stmtPosts->fetch()["total"];

$stmtSeguidores = $pdo->prepare("SELECT COUNT(*) AS total FROM followers WHERE following_id = :user_id");
$stmtSeguidores->execute([":user_id" => $user_id]);
$seguidores = $stmtSeguidores->fetch()["total"];

$stmtSeguindo = $pdo->prepare("SELECT COUNT(*) AS total FROM followers WHERE follower_id = :user_id");
$stmtSeguindo->execute([":user_id" => $user_id]);
$seguindo = $stmtSeguindo->fetch()["total"];

?>
<?php $pageTitle = "SocialHub";
require_once "includes/html.php"; ?>
<div class="home"> <?php include "includes/header.php" ?>
    <div class="homecontainer"> <!-- MAIN ZONE - PUBLICACOES -->
        <div class="mainzone">
            <?php
            if (empty($publicacoes_list)) {
            ?>
                <div class="empty-feed">
                    <h2>Ainda não segues ninguém.</h2>
                    <p>Segue alguém para veres publicações.</p>
                </div>
                <?php
            } else {
                foreach ($publicacoes_list as $publicacoes) {

                    $post_id = $publicacoes["id"];

                    $stmtLikes = $pdo->prepare("SELECT COUNT(*) AS total FROM likes WHERE post_id = :post_id");
                    $stmtLikes->execute([":post_id" => $post_id]);
                    $total_likes = $stmtLikes->fetch()["total"];

                    $stmtLiked = $pdo->prepare("SELECT COUNT(*) AS total FROM likes WHERE post_id = :post_id AND user_id = :user_id");
                    $stmtLiked->execute([":post_id" => $post_id, ":user_id" => $user_id]);
                    $liked = $stmtLiked->fetch()["total"] > 0;

                    $stmtComments = $pdo->prepare("SELECT COUNT(*) AS total FROM comments WHERE post_id = :post_id");
                    $stmtComments->execute([":post_id" => $post_id]);
                    $total_comments = $stmtComments->fetch()["total"];
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
                            <a href="api/like.php?id=<?php echo $publicacoes["id"]; ?>" onclick="event.stopPropagation();">
                                <button class="<?php echo $liked ? "active" : ""; ?>">
                                    <i class="fa-solid fa-heart"></i>
                                    <?php echo $total_likes; ?>
                                </button>
                            </a>

                            <a href="post.php?id=<?php echo $publicacoes["id"]; ?>" onclick="event.stopPropagation();">
                                <button type="button">
                                    <i class="fa-solid fa-comment"></i>
                                    <?php echo $total_comments; ?>
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
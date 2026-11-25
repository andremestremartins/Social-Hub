 <?php

    require_once "includes/auth.php";
    require_once "config/database.php";

    /** @var PDO $pdo */

    if (!isset($_GET["id"])) {
        header("Location: index.php");
        exit();
    }

    $id = (int)$_GET["id"];
    $user_id = $_SESSION["user_id"];

    $stmtPost = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
    $stmtPost->execute([":id" => $id]);
    $post = $stmtPost->fetch();

    if (!$post) {
        header("Location: index.php");
        exit();
    }

    $stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
    $stmtUser->execute([":user_id" => $post["user_id"]]);
    $user = $stmtUser->fetch();

    $post["username"] = $user["username"];

    $stmtLikes = $pdo->prepare("SELECT COUNT(*) AS total FROM likes WHERE post_id = :post_id");
    $stmtLikes->execute([":post_id" => $id]);
    $total_likes = $stmtLikes->fetch()["total"];

    $stmtLiked = $pdo->prepare("SELECT COUNT(*) AS total FROM likes WHERE post_id = :post_id AND user_id = :user_id");
    $stmtLiked->execute([":post_id" => $id, ":user_id" => $user_id]);
    $liked = $stmtLiked->fetch()["total"] > 0;

    $stmtComments = $pdo->prepare("SELECT COUNT(*) AS total FROM comments WHERE post_id = :post_id");
    $stmtComments->execute([":post_id" => $id]);
    $total_comments = $stmtComments->fetch()["total"];

    $sqlComments = "
SELECT comments.*, users.username
FROM comments
INNER JOIN users
ON comments.user_id = users.id
WHERE comments.post_id = :post_id
ORDER BY comments.created_at DESC
";

    $commentsStmt = $pdo->prepare($sqlComments);
    $commentsStmt->execute([":post_id" => $id]);

    ?>
    <?php require_once "includes/html.php"; ?>

    <?php include "includes/header.php"; ?>
    <div class="containerComments">

        <div class="postcard">

            <div class="post-header">

                <div class="post-avatar">
                    <?php echo mb_strtoupper($post["username"], "UTF-8")[0]; ?>
                </div>

                <div class="post-user-info">

                    <div class="post-username">
                        <?php echo htmlspecialchars($post["username"]); ?>
                    </div>

                    <div class="post-timestamp">
                        <?php echo date("d/m/Y H:i", strtotime($post["created_at"])); ?>
                    </div>

                </div>

            </div>

            <div class="post-body">

                <?php if (!empty($post["image"])) { ?>

                    <img class="post-img"
                        src="<?php echo htmlspecialchars($post["image"]); ?>"
                        alt="Post">

                <?php } ?>

                <p>
                    <?php echo nl2br(htmlspecialchars($post["content"])); ?>
                </p>

                <div class="post-footer">

                    <span class="post-time">
                        <?php echo date("d/m/Y H:i", strtotime($post["created_at"])); ?>
                    </span>

                    <a class="post-likes"
                        href="post.php?id=<?php echo $post["id"]; ?>"
                        onclick="event.stopPropagation();">

                        <button type="button">

                            <i class="fa-solid fa-comment"></i>

                            <?php echo $total_comments; ?>

                        </button>

                    </a>

                    <a class="post-likes"
                        href="api/like.php?id=<?php echo $post["id"]; ?>"
                        onclick="event.stopPropagation();">

                        <button
                            type="button"
                            class="<?php echo $liked ? "active" : ""; ?>">

                            <i class="fa-solid fa-heart"></i>

                            <?php echo $total_likes; ?>

                        </button>

                    </a>

                </div>

            </div>

        </div>

        <div class="comments">
            <?php while ($comment = $commentsStmt->fetch()) { ?>
                <div class="comment">
                    <div class="comment-header">
                        <div class="comment-avatar">
                            <?php echo mb_strtoupper($comment["username"], "UTF-8")[0]; ?>
                        </div>
                        <div class="comment-user">
                            <?php echo htmlspecialchars($comment["username"]); ?>
                        </div>
                        <span class="comment-time">
                            <?php echo date("d/m/Y H:i", strtotime($comment["created_at"])); ?>
                        </span>
                    </div>
                    <div class="comment-body">
                        <?php echo nl2br(htmlspecialchars($comment["content"])); ?>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="comment-input-fixed">
            <form action="api/comment.php" method="POST" class="comment-form">
                <input type="hidden" name="post_id" value="<?php echo $id; ?>">
                <input type="text" name="content" placeholder="Escreve um comentário..." required>
                <button type="submit">Enviar</button>
            </form>
        </div>

    </div>

    </body>

    </html>
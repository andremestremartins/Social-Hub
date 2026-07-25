<?php

require_once "includes/auth.php";
require_once "config/database.php";

if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET["id"];

$user_id = $_SESSION["user_id"];

$sqlPosts = "SELECT
                posts.*,
                users.username,
                COUNT(DISTINCT likes.id) AS total_likes,
                COUNT(DISTINCT comments_count.id) AS total_comments,
                EXISTS(
                    SELECT 1 FROM likes l2
                    WHERE l2.post_id = posts.id AND l2.user_id = '$user_id'
                ) AS liked
             FROM posts
             INNER JOIN users ON posts.user_id = users.id
             LEFT JOIN likes ON likes.post_id = posts.id
             LEFT JOIN comments AS comments_count ON comments_count.post_id = posts.id
             WHERE posts.id = '$id'
             GROUP BY posts.id";
$query = mysqli_query($conn, $sqlPosts);

$post = mysqli_fetch_assoc($query);

$sqlComments = "SELECT
                    comments.*,
                    users.username
                FROM comments
                INNER JOIN users ON comments.user_id = users.id
                WHERE comments.post_id = '$id'
                ORDER BY comments.created_at DESC";
$commentsQuery = mysqli_query($conn, $sqlComments);


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

            <p><?php echo nl2br(htmlspecialchars($post["content"])); ?></p>

            <div class="post-footer">

                <span class="post-time">
                    <?php echo date("d/m/Y H:i", strtotime($post["created_at"])); ?>
                </span>

                <a class="post-likes" href="post.php?id=<?php echo $post["id"]; ?>"
                   onclick="event.stopPropagation();">

                    <button>

                        <i class="fa-solid fa-comment"></i>

                        <?php echo $post["total_comments"]; ?>

                    </button>

                </a>

                <a class="post-likes" href="api/like.php?id=<?php echo $post["id"]; ?>"
                   onclick="event.stopPropagation();">

                    <button class="<?php echo $post["liked"] ? "active" : ""; ?>">

                        <i class="fa-solid fa-heart"></i>

                        <?php echo $post["total_likes"]; ?>

                    </button>

                </a>

            </div>

        </div>

    </div>

    <div class="comments">
        <?php while ($comment = mysqli_fetch_assoc($commentsQuery)) { ?>
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
    <?php

    require_once "includes/auth.php";
    require_once "config/database.php";

    if (!isset($_GET["id"])) {
        header("Location: index.php");
        exit();
    }

    $id = (int)$_GET["id"];
    $user_id = $_SESSION["user_id"];

    $sqlPost = "SELECT *
FROM posts
WHERE id='$id'";

    $queryPost = mysqli_query($conn, $sqlPost);

    if (mysqli_num_rows($queryPost) == 0) {
        header("Location: index.php");
        exit();
    }

    $post = mysqli_fetch_assoc($queryPost);

    $sqlUser = "SELECT *
FROM users
WHERE id='" . $post["user_id"] . "'";

    $queryUser = mysqli_query($conn, $sqlUser);

    $user = mysqli_fetch_assoc($queryUser);

    $post["username"] = $user["username"];

    $sqlLikes = "SELECT *
FROM likes
WHERE post_id='$id'";

    $queryLikes = mysqli_query($conn, $sqlLikes);

    $total_likes = mysqli_num_rows($queryLikes);

    $sqlLiked = "SELECT *
FROM likes
WHERE post_id='$id'
AND user_id='$user_id'";

    $queryLiked = mysqli_query($conn, $sqlLiked);

    $liked = false;

    if (mysqli_num_rows($queryLiked) > 0) {
        $liked = true;
    }

    $sqlComments = "SELECT *
FROM comments
WHERE post_id='$id'";

    $queryComments = mysqli_query($conn, $sqlComments);

    $total_comments = mysqli_num_rows($queryComments);

    $sqlComments = "
SELECT comments.*, users.username
FROM comments
INNER JOIN users
ON comments.user_id = users.id
WHERE comments.post_id='$id'
ORDER BY comments.created_at DESC
";

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
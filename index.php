<?php

require_once "includes/auth.php";
require_once "config/database.php";
$user_id = $_SESSION["user_id"];

$sql = "
    SELECT posts.*, users.username
    FROM posts

    INNER JOIN users
    ON posts.user_id = users.id

    INNER JOIN followers
    ON followers.following_id = users.id

    WHERE followers.follower_id = '$user_id'

    ORDER BY posts.created_at DESC
    ";

$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SocialHub</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
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

                        <div class="post">

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

                            <?php if (!empty($publicacoes["image"])) { ?>

                                <img
                                    src="<?php echo htmlspecialchars($publicacoes["image"]); ?>"
                                    class="post-image"
                                    alt="Imagem da publicação">

                            <?php } ?>

                            <div class="post-actions">

                                <button>❤️ Like</button>

                                <button>💬 Comentar</button>

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
                                <div class="num">48</div>
                                <div class="label">Publicações</div>
                            </div>
                            <div>
                                <div class="num">127</div>
                                <div class="label">Seguidores</div>
                            </div>
                            <div>
                                <div class="num">89</div>
                                <div class="label">A seguir</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- SUGESTOES -->
                <div class="rightbar-card">
                    <h3>Sugestões para seguir</h3>
                    <div class="suggestion-item">
                        <div class="suggestion-avatar">D</div>
                        <div class="suggestion-info">
                            <div class="name">Diago Almeida</div>
                            <div class="handle">@diago_almeida</div>
                        </div> <span class="suggestion-follow">Seguir</span>
                    </div>
                    <div class="suggestion-item">
                        <div class="suggestion-avatar">I</div>
                        <div class="suggestion-info">
                            <div class="name">Inês Ribeiro</div>
                            <div class="handle">@ines_ribeiro</div>
                        </div> <span class="suggestion-follow">Seguir</span>
                    </div>
                    <div class="suggestion-item">
                        <div class="suggestion-avatar">T</div>
                        <div class="suggestion-info">
                            <div class="name">Tiago Pereira</div>
                            <div class="handle">@tiago_pereira</div>
                        </div> <span class="suggestion-follow">Seguir</span>
                    </div>
                    <div class="suggestion-item">
                        <div class="suggestion-avatar">L</div>
                        <div class="suggestion-info">
                            <div class="name">Leonor Sousa</div>
                            <div class="handle">@leonor_sousa</div>
                        </div> <span class="suggestion-follow">Seguir</span>
                    </div>
                </div>

            </div>
        </div>

</body>

</html>
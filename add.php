<?php
require_once "config/session.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta</title>
    <link rel="stylesheet" href="assets/css/add.css">
    <link rel="stylesheet" href="assets/css/auth.css">

</head>

<body>

    <div class="container">
        <div class="card">
            <h1>SocialHub</h1>
            <p>Adicione um Publicação</p>
            <form action="api/create_post.php" method="POST" enctype="multipart/form-data">

                <textarea
                    name="content"
                    placeholder="No que estás a pensar?"
                    required></textarea>

                <input
                    type="file"
                    name="image"
                    accept="image/*">

                <button type="submit">

                    Publicar

                </button>

            </form>

        </div>

</body>

</html>
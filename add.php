<?php
require_once "config/session.php";

?>
<?php $pageTitle = "Criar Publicação"; require_once "includes/html.php"; ?>

    <div class="container">
        <div class="card">
            <h1>SocialHub</h1>
            <p>Adicione um Publicação</p>
            <form action="api/create_post.php" method="POST" enctype="multipart/form-data">

                <textarea
                    name="content"
                    placeholder="No que estás a pensar?"
                    required></textarea>

                

                <button type="submit">

                    Publicar

                </button>

            </form>

        </div>

</body>

</html>
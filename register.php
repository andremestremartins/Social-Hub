<?php
require_once "config/session.php";

if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}
?>
<?php $pageTitle = "Criar Conta"; require_once "includes/html.php"; ?>

<div class="container">

    <div class="card">

        <h1>SocialHub</h1>

        <p>Criar uma nova conta</p>

        <form action="api/register.php" method="POST">

            <input
                type="text"
                name="username"
                placeholder="Nome de utilizador"
                required
            >

            <input
                type="text"
                name="nome"
                placeholder="Nome completo"
                required
            >

            <input
                type="email"
                name="email"
                placeholder="Email"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Password"
                required
            >

            <button type="submit">

                Criar Conta

            </button>

        </form>

        <a href="login.php">

            Já tenho uma conta

        </a>

    </div>

</body>

</html>

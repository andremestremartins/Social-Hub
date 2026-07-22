<?php
require_once "config/session.php";

if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Login</title>

    <link rel="stylesheet" href="assets/css/auth.css">

</head>

<body>

<div class="container">

    <div class="card">

        <h1>SocialHub</h1>

        <p>Bem-vindo de volta</p>

        <form action="api/login.php" method="POST">

            <input
                type="text"
                name="username"
                placeholder="Username"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Password"
                required
            >

            <button type="submit">

                Entrar

            </button>

        </form>

        <a href="register.php">

            Criar conta

        </a>

    </div>

</div>

</body>

</html>
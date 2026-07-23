<?php

require_once "../config/database.php";
require_once "../config/session.php";

$username = trim($_POST["username"]);
$nome = trim($_POST["nome"]);
$email = trim($_POST["email"]);
$password = $_POST["password"];


// Verificar se o username já existe
$check_username = mysqli_query($conn, "SELECT id FROM users WHERE username='$username'");

if (mysqli_num_rows($check_username) > 0) {
    die("Este nome de utilizador já existe.");
}

// Verificar se o email já existe
$check_email = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

if (mysqli_num_rows($check_email) > 0) {
    die("Este email já está registado.");
}

// Encriptar a password
$password = password_hash($password, PASSWORD_DEFAULT);

// Inserir utilizador
$sql = "INSERT INTO users (username, nome, email, password)
VALUES ('$username', '$nome', '$email', '$password')";

if (mysqli_query($conn, $sql)) {

    $_SESSION["user_id"] = mysqli_insert_id($conn);
    $_SESSION["username"] = $username;
    $_SESSION["nome"] = $nome;

    header("Location: ../index.php");
    exit();

} else {

    echo "Erro ao criar a conta.";

}

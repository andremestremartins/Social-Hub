<?php

require_once "../config/database.php";
require_once "../config/session.php";

/** @var PDO $pdo */

$username = trim($_POST["username"]);
$nome = trim($_POST["nome"]);
$email = trim($_POST["email"]);
$password = $_POST["password"];


// Verificar se o username já existe
$check_username = $pdo->prepare("SELECT id FROM users WHERE username = :username");
$check_username->execute([":username" => $username]);

if ($check_username->fetch()) {
    die("Este nome de utilizador já existe.");
}

// Verificar se o email já existe
$check_email = $pdo->prepare("SELECT id FROM users WHERE email = :email");
$check_email->execute([":email" => $email]);

if ($check_email->fetch()) {
    die("Este email já está registado.");
}

// Encriptar a password
$password = password_hash($password, PASSWORD_DEFAULT);

// Inserir utilizador
$stmt = $pdo->prepare("INSERT INTO users (username, nome, email, password)
                       VALUES (:username, :nome, :email, :password)");

if ($stmt->execute([
    ":username" => $username,
    ":nome" => $nome,
    ":email" => $email,
    ":password" => $password,
])) {

    $_SESSION["user_id"] = $pdo->lastInsertId();
    $_SESSION["username"] = $username;
    $_SESSION["nome"] = $nome;

    header("Location: ../index.php");
    exit();

} else {

    echo "Erro ao criar a conta.";

}
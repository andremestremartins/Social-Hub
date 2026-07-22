<?php

require_once "../config/database.php";
require_once "../config/session.php";

$username = trim($_POST["username"]);
$email = trim($_POST["email"]);
$password = $_POST["password"];
$confirm = $_POST["confirm_password"];

if ($password != $confirm) {
    die("As passwords não coincidem.");
}

// Check if username already exists
$check_username = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
if (mysqli_num_rows($check_username) > 0) {
    die("Este nome de utilizador já está em uso.");
}

// Check if email already exists
$check_email = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
if (mysqli_num_rows($check_email) > 0) {
    die("Este email já está a ser utilizado.");
}

$password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users(username,email,password)
VALUES('$username','$email','$password')";

if (mysqli_query($conn, $sql)) {
    header("Location: ../login.php");
} else {
    echo "Erro ao criar a conta.";
}

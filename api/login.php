<?php 

require_once "../config/database.php";
require_once "../config/session.php";

/** @var PDO $pdo */

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../login.php");
    exit();
}

$username = trim($_POST["username"]);
$password = $_POST["password"];

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute([":username" => $username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user["password"])) {

    $_SESSION["user_id"] = $user["id"];
    $_SESSION["username"] = $user["username"];
    $_SESSION["nome"] = $user["nome"];

    header("Location: ../index.php");
    exit();

} elseif ($user) {

    die("Password incorreta.");

} else {

    die("Utilizador não encontrado.");

}
<?php 

require_once "../config/database.php";
require_once "../config/session.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../login.php");
    exit();
}

$username = trim($_POST["username"]);
$password = $_POST["password"];

$sql = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {

    $user = mysqli_fetch_assoc($result);

    if (password_verify($password, $user["password"])) {

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["nome"] = $user["nome"];
        
        header("Location: ../index.php");
        exit();

    } else {

        die("Password incorreta.");

    }

} else {

    die("Utilizador não encontrado.");

}

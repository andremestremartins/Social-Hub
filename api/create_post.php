<?php

require_once "../config/database.php";
require_once "../config/session.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"]; 
$content = trim($_POST["content"]);

$image = NULL;

if(isset($_FILES["image"]) && $_FILES["image"]["error"] == 0){

    $extensao = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

    $permitidas = ["jpg","jpeg","png","webp"];

    if(in_array($extensao,$permitidas)){

        $nomeImagem = uniqid() . "." . $extensao;

        $destino = "../uploads/posts/" . $nomeImagem;

        move_uploaded_file($_FILES["image"]["tmp_name"], $destino);

        $image = "uploads/posts/" . $nomeImagem;

    }

}

$sql = "INSERT INTO posts(user_id,content,image)
VALUES(?,?,?)";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"iss",$user_id,$content,$image);

if(mysqli_stmt_execute($stmt)){

    header("Location: ../index.php");
    exit();

}else{

    echo "Erro ao publicar.";

}
<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "123",
    "socialhub"
);

if (!$conn) {
    die("Erro na ligação: " . mysqli_connect_error());
}

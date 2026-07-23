<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
            <span class="logo">SocialHub</span>
            <nav>
                <a href="add.php" class="add">+</a>
                <a href="index.php">Home</a>
                <a href="profile.php?id=<?php echo $_SESSION['user_id']; ?>">Profile</a>
                <a href="chat.php">Chat</a>
                <a href="logout.php">Terminar sessão</a>
                <form action="api/pesquisar.php" method="post">
                    <input type="text" placeholder="Pesquise..." name="pesquisa" required>

                    <button type="submit"><img src="assets/images/search-interface-symbol.png" alt=""></button>

                </form>
            </nav>
        </header>
</body>
</html>
<?php
require_once './auth.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Фотогалерея</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./styles.css">	
	<script src="./js/vote.js"></script>
</head>
<body>
    <nav class="nav">
        <div class="nav-brand">
            Фотогалерея
        </div>
        <div class="nav-links">
            <?php if (isLoggedIn()): ?>
                <a href="./upload.php" class="btn-primary">Загрузить фото</a>
                <a href="./logout.php">Выйти</a>
            <?php else: ?>
                <a href="./login.php">Войти</a>
                <a href="./register.php">Регистрация</a>
            <?php endif; ?>
        </div>
    </nav>

    <div id="greeting">
        <marquee><?php echo getGreeting(); ?>, 
        <?php echo isLoggedIn() ? $_SESSION['username'] : 'Гость'; ?>!</marquee>
    </div>

    <?php if (isset($_GET['uploaded'])): ?>
        <div class="success-message" style="margin: 20px auto; max-width: 800px;">
            Фотография успешно загружена!
        </div>
    <?php endif; ?>

    <div class="gallery">
        <?php
        $photos = getPhotos();
        foreach ($photos as $photo) {
            echo "<div class='photo-card'>";
            echo "<a href='{$photo['image_path']}' target='_blank'><img src='{$photo['image_path']}' alt='{$photo['description']}'></a>";
            echo "<div class='photo-info'>";
            if (!empty($photo['description'])) {
                echo "<p class='photo-description'>{$photo['description']}</p>";
            }
            echo "<div class='rating'>Рейтинг: {$photo['rating']}</div>";
            if (isLoggedIn()) {
                echo "<button class='btn-vote' onclick='vote({$photo['id']})'>Голосовать</button>";
            }
            echo "</div></div>";
        }
        ?>
    </div>
</body>
</html>

<?php
require_once './auth.php';
require_once './config.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!file_exists($TARGET_DIR)) {
		// Установка прав доступа на каталог.
        mkdir($TARGET_DIR, 0777, true);
    }

    $imageFileType = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
    $targetFile = $TARGET_DIR . uniqid() . "." . $imageFileType;
    
    // Проверка файла.
    if (!in_array($imageFileType, $VALID_EXTENSIONS)) {
        $error = "Допускаются только изображения форматов JPG, JPEG, PNG и GIF.";
    } elseif ($_FILES["photo"]["size"] > $MAX_UPLOAD_FILE_SIZE) {
        $error = "Файл слишком большой. Максимальный размер - ".$MAX_UPLOAD_FILE_SIZE." байт.";
    } else {
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
            $conn = connectDB();
            $description = $_POST['description'];
            $userId = $_SESSION['user_id'];
            
            $stmt = $conn->prepare("INSERT INTO photos (user_id, image_path, description) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $userId, $targetFile, $description);
            
            if ($stmt->execute()) {
                header("Location: index.php?uploaded=1");
                exit;
            } else {
                $error = "Ошибка при сохранении данных в базу.";
            }
            $conn->close();
        } else {
            $error = "Ошибка при загрузке файла.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Загрузка фотографии</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <div class="upload-container">
        <h2>Загрузка новой фотографии</h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="upload-form">
            <div class="form-group">
                <label for="photo">Выберите фотографию:</label>
                <input type="file" id="photo" name="photo" accept="image/*" required>
            </div>

            <div class="form-group">
                <label for="description">Описание:</label>
                <textarea id="description" name="description" rows="3"></textarea>
            </div>

            <button type="submit" class="btn-primary">Загрузить</button>
        </form>
        
        <div class="back-link">
            <a href="./index.php">Вернуться в галерею</a>
        </div>
    </div>
</body>
</html>
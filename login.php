<?php
require_once './auth.php';

// Проверка возможности авторизоваться.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (login($username, $password)) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Неверное имя пользователя или пароль.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Вход</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <div class="auth-container">
        <h2>Вход</h2>
        
        <?php if (isset($_GET['registered'])): ?>
            <div class="success-message">Регистрация успешна! Теперь вы можете войти.</div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" class="auth-form">
            <div class="form-group">
                <label for="username">Имя пользователя:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-primary">Войти</button>
        </form>
        
        <div class="auth-links">
            Нет аккаунта? <a href="./register.php">Зарегистрироваться</a>
        </div>
    </div>
</body>
</html>

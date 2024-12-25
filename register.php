<?php
require_once './auth.php';

// Проверка на возможность зарегистрироваться.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = trim($_POST['email']);
    
    if (empty($username) || empty($password) || empty($email)) {
        $error = "Все поля обязательны для заполнения";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Некорректный email адрес";
    } else {
        if (register($username, $password, $email)) {
            header("Location: login.php?registered=1");
            exit;
        } else {
            $error = "Ошибка регистрации. Пользователь уже существует.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Регистрация</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <div class="auth-container">
        <h2>Регистрация</h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" class="auth-form">
            <div class="form-group">
                <label for="username">Имя пользователя:</label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-primary">Зарегистрироваться</button>
        </form>
        
        <div class="auth-links">
            Уже есть аккаунт? <a href="./login.php">Войти</a>
        </div>
    </div>
</body>
</html>

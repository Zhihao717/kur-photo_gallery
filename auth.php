<?php
require_once './config.php';

session_start();

// Конфигурация базы данных.
define('DB_HOST', $DB_HOST);
define('DB_USER', $DB_USER);
define('DB_PASS', $DB_PASS);
define('DB_NAME', $DB_NAME);

// Подключение к базе данных.
function connectDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Не удалость подключиться к базе данных: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");
    return $conn;
}

// Функция регистрации.
function register($username, $password, $email) {
    $conn = connectDB();
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $email);
    
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    
    return $result;
}

// Функция авторизации.
function login($username, $password) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;
            $stmt->close();
            $conn->close();
            return true;
        }
    }
    
    $stmt->close();
    $conn->close();
    return false;
}

// Функция проверки авторизации.
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Функция выхода.
function logout() {
    session_destroy();
    header("Location: index.php");
}

// Функция получения приветствия.
function getGreeting() {
    $hour = date('H');
    if ($hour >= 5 && $hour < 12) {
        return "Доброе утро";
    } elseif ($hour >= 12 && $hour < 18) {
        return "Добрый день";
    } elseif ($hour >= 18 && $hour < 23) {
        return "Добрый вечер";
    } else {
        return "Доброй ночи";
    }
}

// Получение фотографий с сортировкой по рейтингу.
function getPhotos() {
    $conn = connectDB();
    $result = $conn->query("SELECT * FROM photos ORDER BY rating DESC");
    $photos = [];
    while ($row = $result->fetch_assoc()) {
        $photos[] = $row;
    }
    $conn->close();
    return $photos;
}

// Функция голосования.
function vote($photo_id) {
    if (!isLoggedIn()) {
        return ['success' => false, 'message' => 'Требуется авторизация'];
    }
    
    $conn = connectDB();
    $user_id = $_SESSION['user_id'];
    
    // Проверка существования фотографии.
    $check_photo = $conn->prepare("SELECT id FROM photos WHERE id = ?");
    $check_photo->bind_param("i", $photo_id);
    $check_photo->execute();
    if ($check_photo->get_result()->num_rows === 0) {
        $check_photo->close();
        $conn->close();
        return ['success' => false, 'message' => 'Фотография не найдена'];
    }
    
    // Проверка на повторное голосование.
    $check_vote = $conn->prepare("SELECT id FROM votes WHERE user_id = ? AND photo_id = ?");
    $check_vote->bind_param("ii", $user_id, $photo_id);
    $check_vote->execute();
    
    if ($check_vote->get_result()->num_rows > 0) {
        $check_vote->close();
        $conn->close();
        return ['success' => false, 'message' => 'Вы уже голосовали за эту фотографию'];
    }
    $check_vote->close();
    
    // Начинаем транзакцию.
    $conn->begin_transaction();
    
    try {
        // Добавляем голос.
        $add_vote = $conn->prepare("INSERT INTO votes (user_id, photo_id) VALUES (?, ?)");
        $add_vote->bind_param("ii", $user_id, $photo_id);
        $add_vote->execute();
        
        // Обновляем рейтинг.
        $update_rating = $conn->prepare("UPDATE photos SET rating = rating + 1 WHERE id = ?");
        $update_rating->bind_param("i", $photo_id);
        $update_rating->execute();
        
        $conn->commit();
        $conn->close();
        
        return ['success' => true, 'message' => 'Голос успешно учтен'];
    } catch (Exception $e) {
        $conn->rollback();
        $conn->close();
        return ['success' => false, 'message' => 'Ошибка при голосовании'];
    }
}
?>

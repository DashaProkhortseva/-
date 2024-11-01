<?php
session_start();
include 'db.php';

$error = ""; // Переменная для хранения ошибок

// Проверяем, был ли отправлен запрос методом POST и содержат ли данные поля username и password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Подготавливаем SQL-запрос для поиска пользователя с введенным именем пользователя
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $mysql->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Проверка существования пользователя
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Проверяем пароль
        if (password_verify($password, $user['password'])) {
            // Устанавливаем сессионные данные после успешного входа
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            header("Location: index.php"); // Перенаправляем на главную страницу
            exit();
        } else {
            $error = "Неверный пароль.";
        }
    } else {
        $error = "Пользователь не найден.";
    }

    $stmt->close();
}

$mysql->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="styles/reg.css"> <!-- Подключение файла стилей -->
</head>
<body>
    <!-- Шапка сайта с навигационной панелью -->
    <header>
        <div class="container">
            <h1>Магазин книг</h1>
            <nav class="navbar">
                <ul>
                    <li><a href="index.php">Главная</a></li>
                    <li><a href="shop.php">Магазин</a></li>
                    <li><a href="contacts.php">Контакты</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Основная часть страницы -->
    <main>
        <div class="container">
            <h2>Вход в аккаунт</h2>
            
            <!-- Отображение сообщения об ошибке, если есть -->
            <?php if (!empty($error)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <!-- Форма входа -->
            <form action="login.php" method="POST">
                <div>
                    <label for="username">Имя пользователя:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div>
                    <label for="password">Пароль:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Войти</button>
            </form>
            <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
        </div>
    </main>

    <!-- Футер с контактной информацией -->
    <footer>
        <div class="container">
            <p>Контакты: info@bookstore.com | Телефон: +7 (123) 456-7890</p>
            <p>&copy; 2024 Магазин книг</p>
        </div>
    </footer>
</body>
</html>

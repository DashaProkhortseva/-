<?php
// Подключение к базе данных
require_once "db.php";

$error = "";

// Проверка, отправлена ли форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    
    // Валидация полей
    if (empty($username) || empty($email) || empty($password)) {
        $error = "Все поля обязательны для заполнения.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Некорректный email.";
    } else {
        // Хеширование пароля
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Проверка, существует ли пользователь с таким email
        $stmt = $mysql->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Пользователь с таким email уже существует.";
        } else {
            // Вставка нового пользователя в базу данных
            $stmt = $mysql->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $password_hash);
            
            if ($stmt->execute()) {
                header("Location: login.php"); // Перенаправление на страницу входа
                exit();
            } else {
                $error = "Ошибка при регистрации. Попробуйте еще раз.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="styles/reg.css">
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
            <h2>Регистрация</h2>
            
            <!-- Отображение сообщения об ошибке, если есть -->
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>

            <!-- Форма регистрации -->
            <form action="register.php" method="POST">
                <label for="username">Имя пользователя</label>
                <input type="text" name="username" id="username" required>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>

                <label for="password">Пароль</label>
                <input type="password" name="password" id="password" required>

                <button type="submit">Зарегистрироваться</button>
            </form>

            <p>Уже зарегистрированы? <a href="login.php">Войти</a></p>
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

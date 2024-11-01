<?php
session_start();
include 'db.php';

$product_id = $_GET['id'];

// Запрос для получения продукта по ID
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $mysql->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $product = $result->fetch_assoc();
} else {
    // Если продукт не найден, перенаправляем на главную страницу
    header("Location: index.php");
    exit();
}

$stmt->close();
$mysql->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="styles/product.css"> <!-- Подключение файла стилей -->
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
                <div class="auth-links">
                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                        <span>Привет, <?php echo htmlspecialchars($_SESSION['username']); ?></span> | <a href="logout.php">Выйти</a>
                    <?php else: ?>
                        <a href="login.php">Вход</a> | <a href="register.php">Регистрация</a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>

    <!-- Основная часть страницы -->
    <main>
        <div class="container">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <img src="images/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="200">
            <p><?php echo nl2br(htmlspecialchars($product['description_full'])); ?></p>
            <p>Цена: <?php echo htmlspecialchars($product['price']); ?> руб.</p>
            <p>Количество в наличии: <?php echo htmlspecialchars($product['stock']); ?></p>

            
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

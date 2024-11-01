<?php
session_start();
include 'db.php';

// Запрос для получения всех продуктов из таблицы "products"
$query = "SELECT * FROM products";
$result = $mysql->query($query);

// Проверка на наличие продуктов
$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин книг</title>
    <link rel="stylesheet" href="styles/shop.css"> <!-- Подключение файла стилей -->
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
                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                        <li><a href="cart.php">Корзина</a></li>
                    <?php endif; ?>
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
            <h2>Наши книги</h2>
            <?php if (count($products) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Изображение</th>
                            <th>Название</th>
                            <th>Краткое описание</th>
                            <th>Цена</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><img src="images/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="100"></td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo htmlspecialchars($product['description_short']); ?></td>
                                <td><?php echo htmlspecialchars($product['price']); ?> руб.</td>
                                <td>
                                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                                        <form action="add_to_cart.php" method="POST">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <button type="submit">Добавить в корзину</button>
                                        </form>
                                        <a href="product.php?id=<?php echo $product['id']; ?>" class="details-btn">Подробнее</a>
                                    <?php else: ?>
                                        <p><a href="login.php">Войдите</a> для добавления в корзину.</p>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>В магазине пока нет товаров.</p>
            <?php endif; ?>
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

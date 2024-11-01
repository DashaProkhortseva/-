<?php
session_start();
include 'db.php';

// Проверка авторизации
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

// Получение ID авторизованного пользователя
$user_id = $_SESSION['user_id'];

// Получение товаров из корзины пользователя
$query = "
    SELECT c.id as cart_id, p.id as product_id, p.name, p.price, p.image_url, c.quantity
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
";
$stmt = $mysql->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total = 0;
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total += $row['price'] * $row['quantity'];
    }
}

// Обновление количества или удаление товара из корзины
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_quantity'])) {
        $cart_id = $_POST['cart_id'];
        $quantity = $_POST['quantity'];
        $update_query = "UPDATE cart SET quantity = ? WHERE id = ?";
        $update_stmt = $mysql->prepare($update_query);
        $update_stmt->bind_param("ii", $quantity, $cart_id);
        $update_stmt->execute();
        header("Location: cart.php");
        exit();
    }

    if (isset($_POST['remove_item'])) {
        $cart_id = $_POST['cart_id'];
        $delete_query = "DELETE FROM cart WHERE id = ?";
        $delete_stmt = $mysql->prepare($delete_query);
        $delete_stmt->bind_param("i", $cart_id);
        $delete_stmt->execute();
        header("Location: cart.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина</title>
    <link rel="stylesheet" href="styles/cart.css"> <!-- Подключение файла стилей для корзины -->
</head>
<body>
    <header>
        <div class="container">
            <h1>Магазин книг</h1>
            <nav class="navbar">
                <ul>
                    <li><a href="index.php">Главная</a></li>
                    <li><a href="shop.php">Магазин</a></li>
                    <li><a href="contacts.php">Контакты</a></li>
                    <li><a href="cart.php">Корзина</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Ваша корзина</h2>
            <?php if (count($cart_items) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Изображение</th>
                            <th>Название</th>
                            <th>Цена</th>
                            <th>Количество</th>
                            <th>Сумма</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><img src="images/<?php echo htmlspecialchars($item['image_url']); ?>" width="50" alt="<?php echo htmlspecialchars($item['name']); ?>"></td>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo htmlspecialchars($item['price']); ?> руб.</td>
                                <td>
                                    <form method="POST" action="cart.php">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" style="width: 50px;">
                                        <button type="submit" name="update_quantity">Обновить</button>
                                    </form>
                                </td>
                                <td><?php echo htmlspecialchars($item['price'] * $item['quantity']); ?> руб.</td>
                                <td>
                                    <form method="POST" action="cart.php">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                        <button type="submit" name="remove_item">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p><strong>Итого: <?php echo $total; ?> руб.</strong></p>
            <?php else: ?>
                <p>Ваша корзина пуста.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>Контакты: info@bookstore.com | Телефон: +7 (123) 456-7890</p>
            <p>&copy; 2024 Магазин книг</p>
        </div>
    </footer>
</body>
</html>

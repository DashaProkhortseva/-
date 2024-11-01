<?php
session_start();
include 'db.php';

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php"); // Перенаправление на страницу входа
    exit();
}

// Проверка наличия product_id в POST-запросе
if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    
    // Добавление товара в корзину (в таблицу cart)
    $user_id = $_SESSION['user_id']; // ID текущего пользователя
    $stmt = $mysql->prepare("INSERT INTO cart (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $product_id);
    
    if ($stmt->execute()) {
        // Успешно добавлено
        header("Location: shop.php?success=added"); // Перенаправление обратно в магазин
        exit();
    } else {
        // Ошибка при добавлении
        header("Location: shop.php?error=add_failed"); // Перенаправление обратно с ошибкой
        exit();
    }
    
    $stmt->close();
} else {
    // Если product_id не установлен
    header("Location: shop.php?error=invalid_request");
    exit();
}

$mysql->close();
?>

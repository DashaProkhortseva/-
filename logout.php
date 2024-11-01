<?php
session_start(); // Запускаем сессию

// Удаляем все переменные сессии
$_SESSION = [];

// Если необходимо, уничтожаем сессию
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Уничтожаем сессию
session_destroy();

// Перенаправление на главную страницу или страницу входа
header("Location: index.php");
exit();
?>

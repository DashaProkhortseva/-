<?php 
    define("DB_HOST", "localhost"); // Хост 
    define("DB_USER", "u2363381_Dasha");      // Имя пользователя 
    define("DB_PASSWORD", "06012005Dasha");      // Пароль пользователя 
    define("DB_NAME", "u2363381_Book"); // Имя базы данных
   
    $mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // Проверка подключения
    if ($mysql->connect_error) {
        die("Ошибка подключения: " . $mysql->connect_error);
    }
    $mysql->set_charset("utf8mb4");
?>
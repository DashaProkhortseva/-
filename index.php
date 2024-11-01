<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин книг</title>
    <link rel="stylesheet" href="styles/index.css"> <!-- Подключение файла стилей -->
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
            <section class="intro">
                <h2>О нашем магазине</h2>
                <p>Добро пожаловать в наш интернет-магазин, где каждый найдет что-то для себя. Мы предлагаем широкий ассортимент книг для всех возрастов и интересов. Наша миссия — способствовать развитию культуры чтения и предоставлять доступ к качественной литературе.</p>
                <p>Наша команда ежедневно работает над тем, чтобы обеспечить вас самыми интересными и актуальными книгами, от классики до новинок, которые только появились на рынке. Мы гордимся тем, что можем предложить вам не только популярные произведения, но и уникальные издания, которые редко встречаются в обычных магазинах.</p>
                <p>Мы стремимся к тому, чтобы процесс покупки был максимально удобным и приятным для вас. В нашем магазине вы найдете дружелюбный интерфейс, продуманные категории и рекомендации, которые помогут вам сделать выбор. Благодарим вас за доверие и надеемся, что вы найдете в нашем магазине книги, которые подарят вам удовольствие и вдохновение.</p>
            </section>
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

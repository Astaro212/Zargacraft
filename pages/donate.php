<?php
require_once '../modules/shop.php';
require_once '../config.php';

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

$shopConfig = [
    'donate' => $donate,
    'server' => $server
];

$shop = new Shop($shopConfig);



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $itemKey = $_POST['item_key'];

    if ($action === 'add' && !empty($itemKey)) {
        $shop->add($itemKey);
    }

    if ($action === 'remove' && !empty($itemKey)) {
        $shop->remove($itemKey);
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

$errors = $_SESSION['errors'] ?? [];
$messages = $_SESSION['messages'] ?? [];
unset($_SESSION['errors'], $_SESSION['messages']);

if (isset($_POST['pay'])) {
    $shop->username = $_POST['username'];
    $shop->amount = $_POST['amount'];
    $sign = $shop->getSign();
}

if (isset($_POST['MERCHANT_ORDER_ID'])) {
    $username = $_POST['MERCHANT_ORDER_ID'];
    $result = $shop->checkout($username);
    /*if ($result['status']) {
        echo "Успех: " . $result['message'];
    } else {
        echo "Ошибка: " . $result['message'];
    }*/
}

?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <title>ZargaCraft - Магазин</title>
    <link rel="stylesheet" href="../styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
</head>

<body>

    <div class="mainwrapper">
        <header>
            <div class="nav">
                <label for="checkbox">
                    <i class="fa fa-bars menu-icon"></i>
                </label>
                <input type="checkbox" id="checkbox">
                <nav class="menu">
                    <ul>
                        <li><a href="#">ГЛАВНАЯ</a></li>
                        <li><img src="assets/img/logo.png" alt="logo"></li>
                        <li><a href="/donate">МАГАЗИН</a></li>
                        <li><a href="/rules">ПРАВИЛА</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <main>

            <h3>Привилегии</h3>
            <ul class="donate">
                <?php foreach ($donate as $item):
                    $key = $item['key']; ?>
                    <li>
                        <div style="flex-grow:1;">
                            <strong><?= htmlspecialchars($item['name']) ?></strong> - <?= htmlspecialchars($item['cost']) ?> &#8381;
                        </div>
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="item_key" value="<?= htmlspecialchars($key) ?>">
                            <button type="submit" class="action">Добавить в корзину</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>

            <h2>Корзина</h2>

            <?php if ($shop->getCart()): ?>
                <form method="post" action="">
                    <table class="cart">
                        <tr>
                            <th>Товар</th>
                            <th>Количество</th>
                            <th>Цена за единицу</th>
                            <th>Общая цена</th>
                            <th>Действие</th>
                        </tr>
                        <?php
                        $totalCost = 0;

                        foreach ($_SESSION['cart'] as $prodId => $qty):
                            $item = null;
                            foreach ($donate as $prod) {
                                if ($prod['key'] == $prodId) {
                                    $item = $prod;
                                    break;
                                }
                            }
                            // Если товар не найден — пропускаем
                            if (!$item) {
                                continue;
                            }



                            $nameItem = htmlspecialchars($item['name']);
                            $pricePerUnit = intval($item['cost']);
                            $subTotal = $pricePerUnit * intval($qty);

                        ?>
                            <tr>
                                <td><?= $nameItem ?></td>
                                <td><?= htmlspecialchars((int)$qty); ?></td>
                                <td><?= $pricePerUnit ?> &#8381;</td>
                                <td><?= $subTotal ?> &#8381;</td>
                                <td>
                                    <form method="POST" action="">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="item_key" value="<?= htmlspecialchars($prodId) ?>">
                                        <button type="submit" class="action-link">Удалить</button>
                                    </form>
                                </td>
                            </tr>

                        <?php
                            $totalCost += $subTotal;

                        endforeach;

                        // Итоговая строка
                        ?>
                        <tr class='total-row'>
                            <td colspan='3'>ИТОГО:</td>
                            <td colspan='2'><?= htmlspecialchars($totalCost); ?> &#8381;</td>
                        </tr>

                    </table>



                <?php else : ?>
                    <p style='color:#f39c12;font-weight:bold; margin:20px;'>В корзине пусто.</p>
                <?php endif; ?>

                <h3>Оформление заказа</h3>
                <div class="pay">
                    <form method="POST" action="">
                        <input type='text' name='username' placeholder='Имя игрока' required>
                        <input type='hidden' name="amount" value="<?= htmlspecialchars(number_format(floatval($totalCost), 2, '.', '')) ?>" required>
                        <button type='submit' name='pay' value='Оплатить' class='submit-btn'>Оплатить!</button>
                    </form>
                </div>
                <!-- Вывод ошибок и сообщений -->
                <?php if (!empty($errors)): ?>
                    <div class='message-error'>
                        <?php foreach ($errors as $error): echo htmlspecialchars($error) . "<br>";
                        endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($messages)): ?>
                    <div class='message-success'>
                        <?php foreach ($messages as $msg): echo htmlspecialchars($msg) . "<br>";
                        endforeach; ?>
                    </div>
                <?php endif; ?>


                <!-- Подвал -->
                <footer>
                    <div class='policies'>
                        <a href='#'>Публичная оферта </a> <a href='#'>Политика конфиденциальности </a> <a href='#'>Email:
                            zargacraft@gmail.com </a>
                    </div>
                    <p class='after'>©Zargacraft,2025.<br>Zargacraft не связан с MojangAB.</p>
                </footer>

        </main>

    </div>
    <?php if (isset($_POST['pay'])): ?>
        <form id="autoPayForm" method="GET" action="https://pay.fk.money/" style="display:none;">
            <input type="hidden" name="oa" value="<?= htmlspecialchars($shop->amount) ?>">
            <input type="hidden" name="m" value="<?= htmlspecialchars($shop->merchantId) ?>">
            <input type="hidden" name="o" value="<?= htmlspecialchars($shop->username) ?>">
            <input type="hidden" name="currency" value="<?= htmlspecialchars($shop->currency) ?>">
            <input type="hidden" name="s" value="<?= htmlspecialchars($sign) ?>">
        </form>
        <script>
            window.onload = function() {
                document.getElementById('autoPayForm').submit();
            };
        </script>
    <?php endif; ?>

</body>

</html>
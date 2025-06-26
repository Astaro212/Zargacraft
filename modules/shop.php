<?php

require_once 'rcon/rcon.php';
class Shop
{
    private $config;
    public  $errors = [];
    public  $messages = [];

    public  $username;
    public $amount = 0;
    public  $currency = 'RUB';

    public int $merchantId = 0;
    public $donateAssoc;

    private $secretWord1 = "";
    public function __construct($config)
    {
        $this->config = $config;
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $this->donateAssoc = [];
        foreach ($this->config['donate'] as $item) {
            $this->donateAssoc[$item['key']] = $item;
        }
    }

    public function addToCart($id, $qty = 1)
    {
        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = 0;
        }
        $_SESSION['cart'][$id] +=  $qty;
    }

    public function removeFromCart($id)
    {
        unset($_SESSION['cart'][$id]);
    }

    public function getCart()
    {
        return $_SESSION['cart'];
    }

    public function getCommand($donateKey)
    {
        foreach ($this->config['donate'] as $item) {
            if ($item['key'] === $donateKey) {
                return $item['command'];
            }
        }
        return null;
    }

    public function processCommand($command)
    {
        foreach ($this->config['server'] as $srv) {
            try {
                // Предполагается, что класс Rcon подключен и работает
                $rcon = new Rcon($srv['rcon_host'], $srv['rcon_port'], $srv['rcon_password'], 3);
                if ($rcon->connect()) {
                    $rcon->sendCommand($command);
                    $rcon->disconnect();
                    break; // Успешное выполнение, выходим из цикла
                }
            } catch (Exception $ex) {
                $this->errors[] = "Ошибка: " . htmlspecialchars($ex->getMessage());
            }
        }
    }

    public function add($id, $qty = 1)
    {
        $this->addToCart($id, $qty);
    }

    public function remove($id)
    {
        $this->removeFromCart($id);
    }



    // В методе checkout()
    public function checkout($username)
    {
        if (empty($_SESSION['cart'])) {
            return ['status' => false, 'message' => "Корзина пуста."];
        }

        foreach ($_SESSION['cart'] as $id => $qty) {
            $key = is_numeric($id) ? (string)$id : $id;
            if (isset($this->donate_assoc[$key])) {
                $cmd = $this->getCommand($key);
                if ($cmd) {
                    for ($i = 0; $i < $qty; $i++) {
                        $cmdToSend = str_replace('[name]', htmlspecialchars($username), $cmd);
                        $this->processCommand($cmdToSend);
                    }
                } else {
                    return ['status' => false, 'message' => "Команда для доната '{$key}' не найдена."];
                }
            } else {
                // Обработка других товаров или игнорирование
                return ['status' => false, 'message' => "Товар с id '{$id}' не является донатом."];
            }
        }

        $_SESSION['cart'] = [];
        return ['status' => true, 'message' => "Покупка успешно завершена."];
    }


    public function getSign()
    {
        $params = [
            'MERCHANT_ID' => $this->merchantId,
            'INVOICE' => $this->username,
            'AMOUNT' => number_format(floatval($this->amount), 2, '.', ''),
            'CURRENCY' => $this->currency,
        ];

        $sign_str = implode(':', [
            $params['MERCHANT_ID'],
            $params['AMOUNT'],
            $this->secretWord1,
            $params['CURRENCY'],
            $params['INVOICE']
        ]);

        return md5($sign_str);
    }
}

<?php

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Notify 
{

    public function getIp()
    {
        if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            return $_SERVER['HTTP_X_REAL_IP'];
        }
        return $_SERVER['REMOTE_ADDR'];
    }

    public function checkIp()
    {
        if (!in_array($this->getIP(), ['168.119.157.136', '168.119.60.227', '178.154.197.79', '51.250.54.238'])) die("Hacking attempt!");
    }

    public function getFormData()
    {
        if (isset($_POST)) {
            $this->checkIp();
            $username = $_POST['MERCHANT_ORDER_ID'];
            return $username;
        }
    }
}
?>
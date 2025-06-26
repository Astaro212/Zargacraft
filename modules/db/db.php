<?php

class Db{
    private $host = "94.232.246.111";

    private $port = 3306;
    private $username = "shop";
    private $password = "FerroCyanidumK2CN3";

    private $dbName = "shop";


    public function connect(){
       $mysqli = mysqli_connect($this->host, $this->username, $this->password, $this->dbName,$this->port) or die(mysqli_connect_error());
       return $mysqli;
    }

}

?>
<?php

class Connection
{
    private $host = 'localhost';
    private $port = '3306';
    private $db = 'renidb';
    private $username = 'rahman';
    private $password = 'rahman';

    private $pdo;

    public function connect()
    {
        $dsn = "mysql:host=$this->host;port=$this->port;dbname=$this->db";
        $p = new PDO($dsn, $this->username, $this->password);
        $p->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo = $p;
    }

    public function close()
    {
        $this->pdo = null;
    }

    public function query($sql)
    {
        return $this->pdo->query($sql);
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    public function execute($stmt, $params)
    {
        return $stmt->execute($params);
    }
}

$connection = new Connection();
$connection->connect();
?>
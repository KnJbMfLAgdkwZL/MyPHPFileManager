<?php

class ActiveRecord
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    private static $DBH;

    public function __construct()
    {
        $config = Config::get();
        $this->host = $config['database']['host'];
        $this->db_name = $config['database']['db_name'];
        $this->username = $config['database']['username'];
        $this->password = $config['database']['password'];
    }

    private function connect()
    {
        $check = new Check();
        if (!$check->exists(self::$DBH)) {
            $connection = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8';
            self::$DBH = new PDO($connection, $this->username, $this->password);
        }
    }

    public static function disconnect()
    {
        $check = new Check();
        if ($check->exists(self::$DBH)) {
            self::$DBH = null;
        }
    }

    public function execute($sql, $params = null)
    {
        $this->connect();
        $stm = self::$DBH->prepare($sql);
        $stm->execute($params);
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
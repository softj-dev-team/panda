<?php
class DB extends PDO
{
    const DB_HOST = 'localhost';
    const DB_USER = 'asssahcom9';
    const DB_PASSWORD = 'soulvocal7!!';

    public function __construct($database = 'asssahcom9')
    {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8', self::DB_HOST, $database);
        parent::__construct($dsn, self::DB_USER, self::DB_PASSWORD, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_COMPRESS => true,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ));
    }
}

<?php

class Model 
{
  protected static $db;

  protected static function connect()
  {
    if (!isset(self::$db)) {
      try {
        $host = 'localhost';
        $dbname = 'gameandplaydb';
        $username = 'simon_vrignaud';
        $password = 'Cl0udstr1fe';

        self::$db = new PDO("mysql:host=$host;gameandplaydb=$dbname;charset=utf8, $username, $password");

        self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
        die("Erreur de connnexion à la base de données. : " . $e->getMessage());
      }
    }
    return self::$db;
  }
}
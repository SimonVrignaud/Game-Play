<?php

require_once 'model.php';

class User extends Model {
  public static function register($name, $email, $password)
  {
    $db = self::connect();

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    return $stmt->execute([$name, $email, $password]);
  }

  public static function login($email, $password)
  {
    $db = self::connect();

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
      return $user;
    }
    return false;
  }

  public static function getById($id)
  {
    $db = self::connect();
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public static function emailExists($email)
  {
    $db = self::connect();
    $stmt = $d->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch() !== alse;

  }
}
<?php

require_once 'controllers/ProductController.php';

$controllerName = $_GET['controller'] ?? 'product';
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

switch (strtolower($controllerName)) {
  case 'product':
    $controller = new ProductController();
    break;

    default:
      die("erreur : controleur '$controllerName' non reconnu.");
}

if (!method_exists($controller, $action)) {
  die("erreur : Action '$action' non disponible dans le controleur '$controllerName'.");
}

if ($id !== null) {
  $controller->$action($id);
} else {
  $controller->$action();
}


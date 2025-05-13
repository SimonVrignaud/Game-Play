<?php

require_once 'controllers/Productontroller.php';

$controller = new ProductController();
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

switch ($action) {
  case 'index':
    $controller->index();
    break;
  case 'show':
    $controller->show($id);
    break;
  case 'create':
    $controller->show($id);
    break;
  case 'store':
    $controller->index();
    break;
  case 'edit':
    $controller->index();
    break;
  case 'update';
    $controller->index();
    break;
  case 'delete';
    $controller->index();
    break;
  default:
    echo "action no reconnue";
}
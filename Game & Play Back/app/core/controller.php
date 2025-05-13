<?php

// inclusion du controleur.
require_once 'controllers/ProductController.php';

// Récuparation des paramétres de l'URL. / Recuperation of the URL parametres.
$controllerName = $_GET['controller'] ?? 'product';
$action = $_GET['action'] ?? 'index';
$id = $_GET['product'] ?? null;

// Sélection du bon controleur. / Selection of the controler.
switch (strtlower($controllerName)) {
  case 'product':
    $controller = new ProductController();
    break;
  default:
    die("controleur inconnu.");
}

// Appel dynamique de la méthode selon l'action. / Dynamic method call depending on action.
if (!method_exists($controller, $action)) {
  die("Action '$action' inconnue dans le controleur.");
}

if ($id !== null) {
  $controller->$action($id);
}else{
  $controller->$action();
}


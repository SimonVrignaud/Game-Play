<?php
// Démarre la session si elle n'est pas déjà démarrée
session_start();

// Supprime toutes les variables de session
$_SESSION = [];

// Supprime le cookie de session si nécessaire
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),        // Nom du cookie
        '',                    // Valeur vide
        time() - 42000,        // Expire dans le passé
        $params["path"], 
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Détruit la session
session_destroy();

// Redirige vers la page de connexion ou d'accueil
header("Location: login.php");
exit;

<?php


if (!isset($_SESSION["login_attempts"])) {
  $_SESSION["login_attempts"] = 0;
  $_SESSION["last_attempt_time"] = time();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST["email"] ?? "";
  $password = $_POST["password"] ?? "";

  // Si bloqué, empêche la connexion
  if ($_SESSION["login_attempts"] >= MAX_ATTEMPTS) {
      $remaining = BLOCK_TIME - (time() - $_SESSION["last_attempt_time"]);
      if ($remaining > 0) {
          $error = "Trop de tentatives. Réessayez dans " . ceil($remaining / 60) . " minutes.";
      } else {
          // Débloque après délai
          $_SESSION["login_attempts"] = 0;
      }
  }

}


$code = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 5);
$_SESSION["captcha"] = $code;

// Crée une image
$image = imagecreatetruecolor(120, 40);
$bg = imagecolorallocate($image, 255, 255, 255); // blanc
$text_color = imagecolorallocate($image, 0, 0, 0); // noir
$line_color = imagecolorallocate($image, 100, 100, 100); // gris

imagefilledrectangle($image, 0, 0, 120, 40, $bg);

// Bruit
for ($i = 0; $i < 10; $i++) {
    imageline($image, rand(0,120), rand(0,40), rand(0,120), rand(0,40), $line_color);
}

// Texte
imagettftext($image, 20, 0, 15, 30, $text_color, __DIR__.'/arial.ttf', $code);

// En-tête image
header("Content-type: image/png");
imagepng($image);
imagedestroy($image);




session_start();
require "ressources/services/_pdo.php";

$pdo = connexionPDO();
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(["email" => $email]);
    $user = $stmt->fetch();

    if ($user) {
        $now = new DateTime();

        // Si le compte est bloqué
        if ($user["locked_until"] && new DateTime($user["locked_until"]) > $now) {
            $message = " Votre compte est verrouillé jusqu'à " . $user["locked_until"];
        } else {
            // Vérification du mot de passe
            if (password_verify($password, $user["password"])) {
                // Réinitialisation des tentatives
                $sql = $pdo->prepare("UPDATE users SET login_attempts = 0, locked_until = NULL WHERE idUser = :id");
                $sql->execute(["id" => $user["idUser"]]);

                $_SESSION["idUser"] = $user["idUser"];
                $_SESSION["email"] = $user["email"];
                header("Location: dashboard.php");
                exit;
            } else {
                // Mauvais mot de passe
                $attempts = $user["login_attempts"] + 1;
                $lockedUntil = null;

                if ($attempts >= 3) {
                    $lockedUntil = (new DateTime())->modify("+15 minutes")->format("Y-m-d H:i:s");
                    $message = " Trop de tentatives. Compte verrouillé 15 minutes.";
                } else {
                    $message = " Mot de passe incorrect. Tentative $attempts/3.";
                }

                $sql = $pdo->prepare("UPDATE users SET login_attempts = :att, locked_until = :lu WHERE idUser = :id");
                $sql->execute([
                    "att" => $attempts,
                    "lu" => $lockedUntil,
                    "id" => $user["idUser"]
                ]);
            }
        }
    } else {
        $message = " Email non reconnu.";
    }
}

require "ressources/services/_pdo.php";
$pdo = connexionPDO();

$ip = $_SERVER['REMOTE_ADDR'];
$now = new DateTime();
$windowMinutes = 10;
$limitAttempts = 20;
$blockDuration = "+30 minutes";

// 1. Vérifier si l’IP est déjà bloquée
$sql = $pdo->prepare("SELECT * FROM ip_logs WHERE ip_address = :ip ORDER BY access_time DESC LIMIT 1");
$sql->execute(['ip' => $ip]);
$lastAccess = $sql->fetch();

if ($lastAccess && $lastAccess['blocked_until'] && new DateTime($lastAccess['blocked_until']) > $now) {
    // IP bloquée
    die(" Accès temporairement bloqué pour votre adresse IP.");
}

// 2. Vérifier le nombre d'accès récents (dans une fenêtre de temps)
$sql = $pdo->prepare("SELECT COUNT(*) FROM ip_logs WHERE ip_address = :ip AND access_time > :time_window");
$sql->execute([
    'ip' => $ip,
    'time_window' => $now->modify("-{$windowMinutes} minutes")->format('Y-m-d H:i:s')
]);
$attemptCount = $sql->fetchColumn();

// 3. Bloquer si trop de tentatives
if ($attemptCount >= $limitAttempts) {
    $blockedUntil = (new DateTime())->modify($blockDuration)->format("Y-m-d H:i:s");
    $sql = $pdo->prepare("INSERT INTO ip_logs (ip_address, access_time, blocked_until) VALUES (:ip, NOW(), :blocked)");
    $sql->execute(['ip' => $ip, 'blocked' => $blockedUntil]);
    die(" Trop de requêtes. IP bloquée pour 30 minutes.");
}

// 4. Sinon, enregistrer l’accès normalement
$sql = $pdo->prepare("INSERT INTO ip_logs (ip_address, access_time) VALUES (:ip, NOW())");
$sql->execute(['ip' => $ip]);







// Connexion à la base de données via PDO
try {
  $pdo = new PDO("mysql:host=localhost;dbname=ma_base", "mon_user", "mon_mot_de_passe");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Erreur de connexion : " . $e->getMessage());
}

// Nettoyage des données
function cleanData($data) {
  return htmlspecialchars(trim($data));
}

// Récupération des données envoyées par l'utilisateur
$username = cleanData($_POST['username'] ?? '');
$password = cleanData($_POST['password'] ?? '');

// Vérification si les champs sont remplis
if (!empty($username) && !empty($password)) {

  // Préparation de la requête pour éviter l'injection SQL
  $sql = $pdo->prepare("SELECT * FROM users WHERE username = :username");
  $sql->bindParam(':username', $username);
  $sql->execute();

  $user = $sql->fetch();

  // Vérification du mot de passe haché (si haché avec password_hash)
  if ($user && password_verify($password, $user['password'])) {
      echo "Connexion réussie !";
      // Lancer la session utilisateur ici
  } else {
      echo "Identifiants incorrects.";
  }

} else {
  echo "Veuillez remplir tous les champs.";
}

?>
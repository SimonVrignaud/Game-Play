<?php
session_start();
require "../ressources/services/_shouldBeLogged.php";
shouldBeLogged(true, "./exercice/formateur/connexion.php");

require "../ressources/services/_pdo.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"] ?? null;
    $password = $_POST["password"] ?? "";

    // Vérifie que l'ID correspond à l'utilisateur connecté
    if ($id != $_SESSION["idUser"]) {
        $error = "ID incorrect.";
    } else {
        $pdo = connexionPDO();
        $sql = $pdo->prepare("SELECT password FROM users WHERE idUser = :id");
        $sql->bindParam(":id", $id);
        $sql->execute();
        $user = $sql->fetch();

        if ($user && password_verify($password, $user["password"])) {
            // Suppression du compte
            $delete = $pdo->prepare("DELETE FROM users WHERE idUser = :id");
            $delete->bindParam(":id", $id);
            $delete->execute();

            // Destruction de la session
            session_destroy();
            session_start();
            $_SESSION["flash"] = "Votre compte a été supprimé.";
            header("Location: ./connexion.php");
            exit;
        } else {
            $error = "Mot de passe incorrect.";
        }
    }
}

$title = "Supprimer mon compte";
require("../ressources/templates/_header.php");
?>

<h2>Supprimer mon compte</h2>

<?php if (!empty($error)): ?>
  <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form action="delete.php" method="POST">
    <label for="id">Votre ID :</label><br>
    <input type="text" name="id" id="id" required><br><br>

    <label for="password">Mot de passe :</label><br>
    <input type="password" name="password" id="password" required><br><br>

    <input type="submit" value="Confirmer la suppression" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.')">
</form>

<?php require("../ressources/template/_footer.php"); ?>

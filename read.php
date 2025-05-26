<?php
require "./ressources/services/_pdo.php";
$pdo = connexionPDO();

$sql = $pdo->query("SELECT idUser, firstname, lastName, birthDate, adress, zipCode, phone, email, password, passwordBis, cardNumber, cryptogram FROM users");

$users = $sql->fetchall();

$title = " CRUD - Read ";
require("../resources/template/_header.php");

?>

<h3>Liste Utilisateurs</h3>


<?php if($users): ?>
  <table>
    <thead>
      <tr>
        <th>id</th>
        <th>Nom de Famille</th>
        <th>Prénom</th>
        <th>Date de Naissance</th>
        <th>Adresse</th>
        <th>Zipcode</th>
        <th>N° de téléphone</th>
        <th>Adresse Mail</th>
        <th>Mot de passe</th>
        <th>Vérification du mot de passe</th>
        <th>N° de carte</th>
        <th>Cryptogramme</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach($users as $user): ?>
        <tr>
          <td><?= $user['iduser']?></td>
          <td><?= htmlspecialchars($user['firstName']) ?></td> <!--htmlspecialchars() évite les failles XSS lors de l'affichage. -->
          <td><?= htmlspecialchars($user['lastName']) ?></td>
          <td><?= htmlspecialchars($user['birthDate']) ?></td>
          <td><?= htmlspecialchars($user['adress']) ?></td>
          <td><?= htmlspecialchars($user['zipCode']) ?></td>
          <td><?= htmlspecialchars($user['phone']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td><?= htmlspecialchars($user['password']) ?></td>
          <td><?= htmlspecialchars($user['passwordBis']) ?></td>
          <td><?= htmlspecialchars($user['cardNumber']) ?></td>
          <td><?= htmlspecialchars($user['cryptogram']) ?></td>
          <td>
            <!-- Cela limite l’édition/suppression à l’utilisateur actuellement connecté -->
            <a href="./exercice/blog/read.php?id<?= $user['idUser'] ?>">Voir</a>
            <?php if(isset($_SESSION["idUser"]) && $_SESSION["idUser"] == $user["idUser"]):?>
              <a href="03-update.php?id=<?= $user['idUser'] ?>">Modifier</a> |
              <a href="04-delete.php?id=<?= $user['idUser'] ?>">Supprimer</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <?php else: ?>
    <p>Aucun utilisateur trouvé</p>
<?php
endif;
// La ligne suivante sera probablement amenée à changer.
require("../ressources/template/_footer.php");
?>

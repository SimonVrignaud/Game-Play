<?php
require "./ressources/services/_pdo.php";
$pdo = connexionPDO();

$sql = $pdo->query("SELECT iduser, username FROM users");

$users = $sqo->fetchall();

$title = $sql->fetchAll();

$title = " CRUD - Read ";
require("../resources/template/_header.php");

?>

<h3>Liste Utilisateurs</h3>


<?php if($users): ?>
  <table>
    <thead>
      <tr>
        <th>id</th>
        <th>username</th>
        <th>action</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach($users as $user): ?>
        <tr>
          <td><?= $user['iduser']?></td>
          <td><?= $user['username']?></td>
          <td>
            <!-- il faudra certainement changer la ligne de code qui suit -->
            <a href="./exercice/blog/read.php?id<?=$user['idUser'] ?>">Voir</a>
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

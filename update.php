<?php
require "../ressources/services/_shouldBeLogged.php";
shouldBeLogged(true, "./exercice/formateur/connexion.php");

if(empty($_GET["id"]) || $_SESSION["idUser"] != $_GET["id"])
{
    $_SESSION["idUser"] = "Accés Interdit !";
    header("Location: ./02-read.php");
    exit;
}

require "../ressources/services/_csrf.php";
require "../ressources/services/_pdo.php";

// Connexion à la base de données et récupération des informations de l’utilisateur à modifier.
$pdo = connexionPDO();
$sql = $pdo->prepare("SELECT * FROM users WHERE idUser = :id");
$sql->bindParam(":id", $_GET["id"]);
$sql->execute();
$user = $sql->fetch();

// Initialisation des variables du formulaire et définition d’une regex pour valider les mots de passe.
$firstName = $lastName = $birthDate = $adress = $zipCode = $phone = $email = $password = $passwordBis = $cardNumber = $cryptogram = "";
$error = [];
$regexPass = "/^(?=.*[!?@#$%^&*.,+-])(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}$/";

// Si le formulaire a été soumis, on va verifierque le champ est renseigné et respecte une regex.
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['update']))
{
  if(empty($_POST["username"]))
  {
    $username = $user["username"];
  }else{
    $username = cleanData($_POST["username"]);
    if(!preg_match("/^[a-zA-Z' -]{2,25}$/", $username))
    {
      $error["username"] = "Veuillez saisir un nom d'utilisateur valide.";
    }
  }

  if(empty($_POST["email"]))
  {
    $email = $user["email"];
  }else{
    $email = cleanData($_POST["email"]);
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
      $error["email"] = "Veuillez saisir une adresse email valide";
    }
    if($email != $user["eamil"])
    {

      $sql->bindParam(':em', $email);
      $sql->execute();

      $resultat = $sql->fetch();
      if($resultat)
        {
          $error["email"] = "Cette adresse mail est déja utilisée.";
        }
    }
  }
  if(empty($_POST["password"]))
  {
    $password = $user["password"];
  }
  else
  {
    $password = trim($_POST["password"]);
    if(empty($_POST["passwordBis"]))
    {
      $error["passwordBis"] = "Veuillez confirmar votre mot de passe";
    }
    elseif($_POST["password"] != $_POST["passwordBis"])
    {
      $error["passwordBis"] = "Les mots de passe ne sont pas les meme.";
    }
    if(!preg_match($regexPass, $password))
    {
      $error["password"] = "Veuillez saisir un mot de passe valide.";
    }
    else{
      $password = password_hash($password,  PASSWORD_DEFAULT);
    }
  }
  if(empty($error))
  {
    $sql = $pdo->prepare("UPDATE = users SET firstName = :fn, lastName = :ln, birthDate = :bd, adress = :ad, zipCode = :zc, phone = :ph, email = :em, password = :mdp, passwordBis = :mdpb, cardNumber = :cdn, cryptogram = :cpt, WHEREidUSER = :id");
    $sql->execute([
      "fn" => $firstName,
      "ln" => $lastName,
      "bd" => $birthDate,
      "ad" => $adress,
      "zc" => $zipCode,
      "ph" => $phone,
      "em" => $email,
      "mdp" => $password,
      "mdpb" => $passwordBis,
      "cdn" => $cardNumber,
      "cpt" => $cryptogram,
      "id" => $user["idUser"]
    ]);
    $_SESSION["firstName"] = $firstName;
    $_SESSION["flash"] = "Votre profil a bien été mis à jour.";
    header("Location: /");
    exit;
  }
}

$title = " CRUD - Update ";
require("../ressources/templates/_header.php");
if($user):
?>
<form action="" method="post">

  <label for="firstName"></label>
  <input type:="text" name="firstName" id="firstName" value="<?php echo $user["firstName"] ?>">
  <span class="erreur"><?php echo $error["firstName"]??""; ?></span>
  <br>

  <label for="lastName"></label>
  <input type:="text" name="lastName" id="lastName" value="<?php echo $user["lastName"] ?>">
  <span class="erreur"><?php echo $error["lastName"]??""; ?></span>
  <br>

  <label for="birthDate"></label>
  <input type:="text" name="birthDate" id="birthDate" value="<?php echo $user["birthDate"] ?>">
  <span class="erreur"><?php echo $error["birthDate"]??""; ?></span>
  <br>

  <label for="adress"></label>
  <input type:="text" name="adress" id="adress" value="<?php echo $user["adress"] ?>">
  <span class="erreur"><?php echo $error["adress"]??""; ?></span>
  <br>

  <label for="zipCode"></label>
  <input type:="text" name="zipCode" id="zipCode" value="<?php echo $user["zipCode"] ?>">
  <span class="erreur"><?php echo $error["zipCode"]??""; ?></span>
  <br>

  <label for="phone"></label>
  <input type:="text" name="phone" id="phone" value="<?php echo $user["phone"] ?>">
  <span class="erreur"><?php echo $error["phone"]??""; ?></span>
  <br>

  <label for="email"></label>
  <input type:="email" name="email" id="email" value="<?php echo $user["email"] ?>">
  <span class="erreur"><?php echo $error["email"]??""; ?></span>
  <br>

  <label for="password"></label>
  <input type:="password" name="password" id="password">
  <span class="erreur"><?php echo $error[""]??""; ?></span>
  <br>

  <label for="passwordBis"></label>
  <input type:="password" name="passwordBis" id="passwordBis">
  <span class="erreur"><?php echo $error["passwordBis"]??""; ?></span>
  <br>

  <label for="cardNumber"></label>
  <input type:="text" name="cardNumber" id="cardNumber" value="<?php echo $user["cardNumber"] ?>">
  <span class="erreur"><?php echo $error["cardNumber"]??""; ?></span>
  <br>

  <label for="cryptogram"></label>
  <input type:="text" name="cryptogram" id="cryptogram" value="<?php echo $user["cryptogram"] ?>">
  <span class="erreur"><?php echo $error["cryptogram"]??""; ?></span>
  <br>

  <input type="submit" value="Mettre à jour" name="update">
</form>
<?php else: ?>
  <p>Aucun utilisateur trouvé.</p>
  <?php
  endif;
  require("../ressources/template/_footer.php");

  // Génération du token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Dans le formulaire
<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

// Vérification lors de l'envoi
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Tentative CSRF détectée !");
}

  ?>

  
<?php
require "../ressources/services/_shouldBeLogged.php";
shouldBeLogged(true, "./exercice/formateur/connexion.php");

if(empty($_GET["id"]) || $_SESSION["idUser"] != $_GET["id"])
{
    $_SESSION["idUser"] = "Accés Interdit !";
    headr("Location: ./02-read.php");
    exit;
}

require "../ressources/services/_csrf.php";
require "../ressources/services/_pdo.php";

$pdo = connexionPDO();
$sql = $pdo->prepare("SELECT * FROM users WHERE idUser = :id");
$sql->bindParam(":id", $_GET["id"]);
$sql->execute();
$user = $sql->fetch();

$username = $password = $email = "";
$error = [];
$regexPass = "/^(?=.*[!?@#$%^&*.,+-])(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}$/";

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
    if($emal != $user["eamil"])
    {
      $sql = $pdo->prepare("SELECT * FROM users WHERE email=:em");

      $sql = bindParam(':em', $email);
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
    $sql = $pdo->prepare("UPDATE = users SET username = :us, email = :em, password = :mdp WHEREidUSER = :id");
    $sql->execute([
      "us" => $username,
      "em" => $email,
      "mdp" => $password,
      "id" => $user["idUser"]
    ]);
    $_SESSION["username"] = $username;
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

  <label for="username"></label>
  <input type:="text" name="username" id="username" value="<?php echo $user["username"] ?>">
  <span class="erreur"><?php echo $error["username"]??""; ?></span>
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

  <input type="submit" value="Mettre à jour" name="update">
</form>
<?php else: ?>
  <p>Aucun utilisateur trouvé.</p>
  <?php
  endif;
  require("../ressources/template/_footer.php");
  ?>
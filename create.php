<?php

require "../ressources/services/_shouldBeLogged.php";
shouldBeLogged(false, "/");

$username = $email = $password = "";
$error = [];

$regexPass = "/^(?=.*[!?@#$%^&*+-])(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}$/";

if($SERVER['REQUEST_METHOD']==='POST' && isset($_POST['inscription']))
{
  require "../ressources/services/_csrf.php";
  require "../ressources/services/_pdo.php";

  $pdo = connexionPDO();

  if(empty($_POST["username"]))
  {
    $error["username"] = "Veuillez saisir un nom d'utilisateur";
  }else
  {
    $username = cleandata($_POST["username"]);
    
    if(!preg_match("/^[a-zA-Z' -]{2,25}$/", $username))
      {
        $error["username"] = "Veuillez saisir un nom d'utilisateur valide.";
      }
  }

  if(empty($_POST["email"]))
    {
      $error["email"] = 
      "Veuillez saisir une adresse mail";
    }else{
      $email = cleanData($_POST["email"]);
      if(!filter_var($email,FILTER_VALIDATE_EMAIL))
        {
          $error["email"] = "Veuillez saisir une adresse email valide.";
        }

        $sql->bindParam(':em', $email);
        $sql->execute();

        $sql->bindParam(':em', $email);
        $sql->execute();

        $resultat = $sql->fetch();
        if($resultat)
        {
          $error["email"] = "Cette adresse email est déja utilisée.";
        }
    }

    if(empty($_POST["password"]))
      {
        $error["password"] = "veuillez saisir un mot de passe";
      }else
      {
        $password = trim($_POST["password"]);
        if(!preg_match($regexPass, $password))
          {
            $error["password"] = "Veuillez saisir un mot de passe valide.";
          }
          else{
            $password = password_hash($password, PASSWORD_DEFAULT);
          }
      }

    if(empty($_POST["passwordBis"]))
      {
        $error["passwordBis"] = "Veuillez saisir à nouveau votre mot de passe.";
      }

      else if($_POST["passwordBis"] !== $_POST["password"])
        {
          $error["passwordBis"] = "Les mots de passe ne correspinde pas.";
        }

    if(empty($error))
      {
        $sql = $pdo->prepare("INSERT INTO users(username, password, email) VALUES(?,?,?)");
        $sql->bindParam(1, $_POST["username"], PDO::PARAM_STR);
        $sql->bindParam(2, $password, PDO::PARAM_STR);
        $sql->bindParam(3, $email, PDO::PARAM_STR);
        $sql->execute();

        $_SESSION["flash"] = "Inscription prise en compte, veuillez vous connecter";

        header("Location: ./");
        exit;
      }
}

$title = " CRUD - Create ";
require("../resources/template/_header.php");
?>
<form action="" method="post">
  <label for="username">Nom d'Utilisateur :</label>
  <input type="text" name="username" id="username" required>
  <span class="erreur"><?php echo $error["username"]??""; ?></span>
  <br>
  <label for="email">Adresse Email :</label>
  <input type="email" name="email" id="email" required>
  <span class="erreur"><?php echo $error["email"]??""; ?></span>
  <br>
  <label for="password">Mot de passe :</label>
  <input type="password" name="password" id="password" required>
  <span class="erreur"><?php echo $error["email"]??""; ?></span>
  <br>
  <label for="passwordBis">Confirmation de mot de passe :</label>
  <input type="password" name="passwordBis" id="passwordBis" required>
  <span class="erreur"><?php echo $error["passwordBis"]??""; ?></span>
  <br>

  <input type="submit" value="Inscription" name="inscription">
</form>
<?php
require("../ressources/template/_footer.php")
?>
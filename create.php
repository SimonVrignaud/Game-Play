<?php

require "../ressources/services/_shouldBeLogged.php";
shouldBeLogged(false, "/");

$firstName = $lastName = $birthDate = $adress = $zipcode = $phone = $email = $password = $passwordBis = $cardNumber = $cryptogram = "";
$error = [];

$regexPass = "/^(?=.*[!?@#$%^&*+-])(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}$/";

if($SERVER['REQUEST_METHOD']==='POST' && isset($_POST['inscription']))
{
  require "../ressources/services/_csrf.php";
  require "../ressources/services/_pdo.php";

  $pdo = connexionPDO();

  if(empty($_POST["firstName"]))
  {
    $error["firstName"] = "Veuillez saisir votre nom de Famille";
  }else
  {
    $firstName = cleandata($_POST["firstName"]);
    
    if(!preg_match("/^[a-zA-Z' -]{2,25}$/", $firstName))
      {
        $error["firstName"] = "Veuillez saisir votre nom de Famille.";
      }
  }

  if(empty($_POST["lastName"]))
  {
    $error["lastName"] = "Veuillez saisir votre Prénom.";
  }else
  {
    $lastName = cleandata($_POST["lastName"]);

    if(!preg_match("/^[a-zA-Z' -]{2,25}$/", $lastName))
      {
        $error["firstName"] = "Veuillez saisir votre Prénom";
      }
  }

  if(empty($_POST["birthDate"]))
  {
    $error["birthdate"] = "Veuillez saisir votre date de Naissance";
  }else
  {
    $birthDate = cleandata($_POST["birthDate"]);
      /*Il faudra également voir avec Nolween pourquoi il y a u nprobleme ici*/ 
    if(!preg_match(^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[0-2])/(19|20)\d{2}$, $birthDate))
      {
        $error["birthDate"] = "Veuillez saisir votre date de Naissance.";
      }
  }

  if(empty($_POST["adress"]))
  {
    $error["adress"] = "Veuillez saisir votre adresse.";
  }else
  {
    $adress = cleandata($_POST["adress"]);

    if(!preg_match())/* petit probleme avec la regex, il faudra demander à Nolween*/ 
      {
        $error["adress"] = "Veuillez saisir votre adresse.";
      }
  }

  if(empty($_POST["zipCode"]))
  {
    $error["zipCode"] = "Veuillez saisir votre code postal.";
  }else
  {
    $zipCode = cleandata($_POST["zipCode"]);

    if(!pref_match(^\d{5}$, $zipCode))
      {
        $error["zipCode"] = "Veuilez saisir vote code postal.";
      }
  }

  if(empty($_POST["phone"]))
  {
    $error["phone"] = "Veuillez saisir votre Numéro de téléphone.";
  }else
  {
    $phone = cleandata($_POST["phone"]);

    if(!preg_match(^(0[6-7]\d{8}|[1-9]\d{9})$, $phone))
      {
        $error["phone"] = "Veuillez saisir votre numéro de téléphone.";
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

  if(empty($_POST["cardNumber"]))
  {
    $error["cardNumber"] = "Veuillez saisir votre numéro de carte.";
  }
  else
  {
    $cardNumber = cleandata($_POST["cardNumber"]);

    if(!preg_match(^[0-9]{16}$, $cardNumber))
      {
        $error["cardNumber"] = "Veuillez saisir votre numéro de carte.";
      }
  }

  if(empty($_POST["cryptogram"]))
    {
      $error["cryptogram"] = "Veuillez saisir le cryptoigramme au dos de votre carte.";
    }else
    {
      $cryptogram = cleandata($_POST["cryptogram"]);

      if(!preg_match(^\d{3}$, $cryptogram))
        {
          $error["cryptogram"] = "Veuillez saisir le cryptogramme au dos de votre carte.";
        }
    }

    if(empty($error))
      {
        $sql = $pdo->prepare("INSERT INTO users(firstName, lastName, birthDate, adress, zipCode, phone, email, password, passwordBis, cardNumber, cryptogram) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
        $sql->bindParam(1, $firstName, PDO::PARAM_STR);
        $sql->bindParam(2, $lastName, PDO::PARAM_STR);
        $sql->bindParam(3, $birthDate, PDO::PARAM_STR);
        $sql->bindParam(4, $adress, PDO::PARAM_STR);
        $sql->bindParam(5, $zipCode, PDO::PARAM_STR);
        $sql->bindParam(6, $phone, PDO::PARAM_STR);
        $sql->bindParam(7, $email, PDO::PARAM_STR);
        $sql->bindParam(8, $password, PDO::PARAM_STR);
        $sql->bindParam(9, $passwordBis, PDO::PARAM_STR);
        $sql->bindParam(10, $cardNumber, PDO::PARAM_STR);
        $sql->bindParam(11, $cryptogram, PDO::PARAM_STR);
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
  <label for="firstName">Nom :</label>
  <input type="text" name="firstName" id="firstName" required>
  <span class="erreur"><?php echo $error["firstName"]??""; ?></span>
  <br>
  <label for="lastName">Prénom :</label>
  <input type="text" name="lastName" id="lastName" required>
  <span class="erreur"><?php echo $error["lastName"]??""; ?></span>
  <br>
  <label for="birthDate">Date de Naissance :</label>
  <input type="text" name="birthDate" id="birthDate" required>
  <span class="erreur"><?php echo $error["birthDate"]??""; ?></span>
  <br>
  <label for="adress">Adresse :</label>
  <input type="text" name="adress" id="adress" required>
  <span class="erreur"><?php echo $error["adress"]??""; ?></span>
  <br>
  <label for="zipCode">Code Postal :</label>
  <input type="text" name="zipCode" id="zipCode" required>
  <span class="erreur"><?php echo $error["zipCode"]??""; ?></span>
  <br>
  <label for="phone">N° de téléphone :</label>
  <input type="text" name="phone" id="phone" required>
  <span class="erreur"><?php echo $error["phone"]??""; ?></span>
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
  <label for="cardNumber">N° de Carte :</label>
  <input type="text" name="cardNumber" id="cardNumber" required>
  <span class="erreur"><?php echo $error["cardNumber"]??""; ?></span>
  <br>
  <label for="cryptogram">Cryptogramme :</label>
  <input type="text" name="cryptogram" id="cryptogram" required>
  <span class="erreur"><?php echo $error["cryptogram"]??""; ?></span>
  <br>

  <input type="submit" value="Inscription" name="inscription">
</form>
<?php
require("../ressources/template/_footer.php")
?>
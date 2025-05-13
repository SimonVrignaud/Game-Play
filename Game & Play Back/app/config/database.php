<?php

// Base de donée php / database php

class Database {
  private $host = "localhost"; // adresse du serveur de base de donnée / database server adress.
  private $dbname = "gameandplaydb"; // nom de la base de donnée / database name.
  private $username = "simon_vrignaud"; // nom de l'utilisateur / user name.
  private $password = "Cl0udstr1fe"; // mot de passe de l'utilisateur / user password.
  private $conn; // variable qui servira à stocker la connexion PDO / variable that will serve to stock the PDO connexion.

  // fonction pour établir la connexion / connexion function.
  public function connect() {
    $this->$conn = null; // On demarre avec une connexion nulle / we start with zero connexion.

    try {

      // On crée la connexion PDO avec les paramètres de connexion. / We create the PDO connexion with the connexion setings.
      $this->conn = new PDO(
        "mysql:host=" .$this->host . ";game&playdb=" . $this->gameandplaydb,
        $this->simon_vrignaud,
        $this->Cl0udstr1fe
      );

    // On définit le mode d'érreur de PDO sur Exception pour capter facilement les erreurs. / We set PDO's error mode to Exception to easily catch errors.
    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      // Si une erreur se produit, on fera apparraitre un message d'érreur. / If an error occur, we sent an error message.
      echo "Erreur de connexion: " . $e->getMessage();
    }
    return $this->conn;
  }
}
<?php
class Connexion
{
  protected $pdo, $serveur, $utilisateur, $motDePasse, $dataBase;
  
  public function __construct($serveur, $utilisateur, $motDePasse, $dataBase)
  {
    $this->serveur = $serveur;
    $this->utilisateur = $utilisateur;
    $this->motDePasse = $motDePasse;
    $this->dataBase = $dataBase;
    
    $this->setPDO();
  }
  
  protected function setPDO()
  {
    $this->pdo = new PDO('mysql:host='.$this->serveur.';dbname='.$this->dataBase, $this->utilisateur, $this->motDePasse);
  }

  public function getPDO()
  {
    return $this->pdo;
  }

  public function __sleep()
  {
    return ['serveur', 'utilisateur', 'motDePasse', 'dataBase'];
  }
  
  public function __wakeup()
  {
    $this->setPDO();
  }
}
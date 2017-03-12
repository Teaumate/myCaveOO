<?php
//  ------------------------ Design pattern Singleton
class Connexion
{
  private static $_instance = null;

  protected $pdo, $serveur, $utilisateur, $motDePasse, $dataBase;
  
  private function __construct($serveur, $utilisateur, $motDePasse, $dataBase)
  {
    $this->serveur = $serveur;
    $this->utilisateur = $utilisateur;
    $this->motDePasse = $motDePasse;
    $this->dataBase = $dataBase;
    
    $this->setPDO();
  }

  // public function __destruct() {
  //   print "Destroying ";
  // }

  public static function getInstance($serveur, $utilisateur, $motDePasse, $dataBase) {
 
     if(is_null(self::$_instance)) {
       self::$_instance = new Connexion($serveur, $utilisateur, $motDePasse, $dataBase);  
     }
 
     return self::$_instance;
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
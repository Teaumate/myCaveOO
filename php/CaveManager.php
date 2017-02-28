<?php
class CaveManager
{
  private $_db; // Instance de PDO
  
  public function __construct($db)
  {
    $this->setDb($db);
  }
  
  public function add(Bottle $bouteille)
  {
    $req = $this->_db->prepare("INSERT INTO mycave( name, year, grapes, country, region, description, picture) VALUES (?,?,?,?,?,?,?)");
    $req->execute(array($bouteille->name(), 
                        $bouteille->year(), 
                        $bouteille->grapes(), 
                        $bouteille->country(), 
                        $bouteille->region(), 
                        $bouteille->description(), 
                        $bouteille->picture())
                  );
                        
    $bouteille->hydrate([
      'id' => $this->_db->lastInsertId()
    ]);
  }
  
  public function delete(Bottle $bouteille)
  {
    $this->_db->exec('DELETE FROM mycave WHERE id = '.$bouteille->id());
  }
  
  public function update(Bottle $bouteille)
  {
    $req = $this->_db->prepare("UPDATE `mycave` SET `name`=?,`year`=?,`grapes`=?,`country`=?,`region`=?,`description`=?,`picture`=? WHERE id=?");
    $req->execute(array($bouteille->name(), 
                        $bouteille->year(), 
                        $bouteille->grapes(), 
                        $bouteille->country(), 
                        $bouteille->region(), 
                        $bouteille->description(), 
                        $bouteille->picture(),
                        $bouteille->id())
                  );
  }
  
  public function count()
  {
    return $this->_db->query('SELECT COUNT(*) FROM personnages')->fetchColumn();
  }

  public function exists($info)
  {
    if (is_int($info)) // On veut voir si tel personnage ayant pour id $info existe.
    {
      return (bool) $this->_db->query('SELECT COUNT(*) FROM personnages WHERE id = '.$info)->fetchColumn();
    }
    
    // Sinon, c'est qu'on veut vérifier que le nom existe ou pas.
    
    $q = $this->_db->prepare('SELECT COUNT(*) FROM personnages WHERE nom = :nom');
    $q->execute([':nom' => $info]);
    
    return (bool) $q->fetchColumn();
  }
  
  public function get($info)
  {
    if (is_int($info))
    {
      $q = $this->_db->query('SELECT id, nom, degats FROM personnages WHERE id = '.$info);
      $donnees = $q->fetch(PDO::FETCH_ASSOC);
      
      return new Personnage($donnees);
    }
    else
    {
      $q = $this->_db->prepare('SELECT id, nom, degats FROM personnages WHERE nom = :nom');
      $q->execute([':nom' => $info]);
    
      return new Personnage($q->fetch(PDO::FETCH_ASSOC));
    }
  }
  
  public function getList($nom)
  {
    $persos = [];
    
    $q = $this->_db->prepare('SELECT id, nom, degats FROM personnages WHERE nom <> :nom ORDER BY nom');
    $q->execute([':nom' => $nom]);
    
    while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
    {
      $persos[] = new Personnage($donnees);
    }
    
    return $persos;
  }

  public function setDb(PDO $db)
  {
    $this->_db = $db;
  }
}
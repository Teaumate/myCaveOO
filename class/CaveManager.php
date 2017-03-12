<?php
//  ------------------------ Design pattern Singleton
class CaveManager
{
  private static $_instance = null;

  private $_db; // Instance de PDO
  
  private function __construct($db)
  {
    $this->setDb($db);
  }
  
  public static function getInstance($db) {
 
     if(is_null(self::$_instance)) {
       self::$_instance = new CaveManager($db);  
     }
 
     return self::$_instance;
  }

  public function setDb(PDO $db)
  {
    $this->_db = $db;
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
                        $bouteille->id()));
  }
  
  public function count()
  {
    return $this->_db->query('SELECT COUNT(*) FROM mycave')->fetchColumn();
  }

  public function getList()
  {
    $q = $this->_db->query("SELECT * FROM mycave ORDER BY name");   // récupère toute la base
    $ListBottles = $q->fetchAll(PDO::FETCH_ASSOC);
    return $ListBottles;
  }

}
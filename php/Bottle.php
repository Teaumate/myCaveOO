<?php
class Bottle
{
  private $_id,
          $_name,
          $_year,
          $_grapes,
          $_country,
          $_region,
          $_description,
          $_picture;
  
  public function __construct(array $donnees)
  {
    $this->hydrate($donnees);
  }
    
  public function hydrate(array $donnees)
  {
    foreach ($donnees as $key => $value)
    {
      $method = 'set'.ucfirst($key);
      
      if (method_exists($this, $method))
      {
        $this->$method($value);
      }
    }
  }
    
  // GETTERS //
  
  public function id()
  {
    return $this->_id;
  }
  public function name()
  {
    return $this->_name;
  }
  public function year()
  {
    return $this->_year;
  }
    public function grapes()
  {
    return $this->_grapes;
  }
    public function country()
  {
    return $this->_country;
  }
    public function region()
  {
    return $this->_region;
  }
    public function description()
  {
    return $this->_description;
  }
    public function picture()
  {
    return $this->_picture;
  }

  // SETTERS 

  public function setName($name)
  {
    $this->_name = filter_var(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  }
  public function setYear($year)
  {
    $this->_year = filter_var(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT);
  }
  public function setGrapes($grapes)
  {
    $this->_grapes = filter_var(INPUT_POST, 'grapes', FILTER_SANITIZE_STRING);
  }
  public function setCountry($country)
  {
    $this->_country = filter_var(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
  }
  public function setRegion($region)
  {
    $this->_region = filter_var(INPUT_POST, 'region', FILTER_SANITIZE_STRING);
  }
  public function setDescription($description)
  {
    $this->_description = filter_var(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
  }
  public function setPicture($picture)
  {
    $this->_picture = filter_var(INPUT_POST, 'picture', FILTER_SANITIZE_STRING);
  }
}
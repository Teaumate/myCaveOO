<?php
class UserService
{
    protected $_login;    // using protected so they can be accessed
    protected $_password; // and overidden if necessary

    protected $_db;       // stores the database handler
    protected $_user;     // stores the user data

    public function __construct(PDO $db, $login, $password) 
    {
       $password = filter_var($password, FILTER_SANITIZE_STRING);
       $login = filter_var($login, FILTER_SANITIZE_STRING);
       $this->_db = $db;
       $this->_login = $login;
       $this->_password = $password;
    }

    public function login()
    {
        $user = $this->_checkCredentials();
        if ($user) {
            $this->_user = $user; // store it so it can be accessed later
            $_SESSION['user_id'] = $user['id'];
            return $user['id'];
        }
        return false;
    }

    protected function _checkCredentials()
    {
        $stmt = $this->_db->prepare('SELECT * FROM user WHERE login=?');
        $stmt->execute(array($this->_login));
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $submitted_pass = sha1($this->_password);
            if ($submitted_pass == $user['pswd']) {
                return $user;
            }
        }
        return false;
    }

    public function getUser()
    {
        return $this->_user;
    }
}
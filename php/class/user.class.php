<?php
class user
{
    function __construct()
    {
        $this->online = false;
        $this->offline = true;
        $this->l = 0;
        if(isset($_SESSION['id']))
        {
            $this->id = $_SESSION['id'];
            $this->l = $_SESSION['livello'];
            $this->user = $_SESSION['user'];
            $this->online = true;
            $this->offline = false;
        }
    }

    function login($usern,$pass)
    {
        global $db;
        $sql = "SELECT id,livello,username FROM utenti WHERE username = '".$db->escape_string($usern)."' AND password = '".$db->escape_string($pass)."' LIMIT 1";
        $result = $db->query($sql);
        if($result->num_rows > 0)
        {
            $row = $result->fetch_assoc();
            $_SESSION['id'] = $row['id'];
            $_SESSION['livello'] = $row['livello'];
            $_SESSION['user'] = $row['username'];
        return true;
        }
    return false;
    }

    function logout()
    {
        $_SESSION = array();
        session_destroy();
        return true;
    }

}
?>